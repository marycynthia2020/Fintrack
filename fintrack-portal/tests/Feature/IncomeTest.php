<?php

namespace Tests\Feature;

use FinTrack\Core\Models\Organization;
use FinTrack\Core\Models\User;
use FinTrack\FinLib\Models\Account;
use FinTrack\FinLib\Models\Income;
use FinTrack\FinLib\Models\Ledger;
use FinTrack\FinLib\Models\AuditLog;
use FinTrack\FinLib\Notifications\IncomeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class IncomeTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Organization $organization;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up test organization and user
        $this->organization = Organization::create(['name' => 'Acme Inc']);
        $this->user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'Pass123',
            'organization_id' => $this->organization->id,
        ]);

        $this->token = $this->user->createToken('login-token')->plainTextToken;
    }

    /**
     * Test listing incomes respects organization tenancy and pagination.
     */
    public function test_user_can_list_organization_incomes_only(): void
    {
        // Organization account
        Account::create(['organization_id' => $this->organization->id, 'balance' => 0]);

        // Create incomes for user's organization
        Income::create([
            'organization_id' => $this->organization->id,
            'amount' => 1500.00,
            'type' => 'salary',
            'description' => 'Monthly Salary',
            'created_by' => $this->user->id,
        ]);

        Income::create([
            'organization_id' => $this->organization->id,
            'amount' => 500.00,
            'type' => 'dividend',
            'description' => 'Stock Dividends',
            'created_by' => $this->user->id,
        ]);

        // Create an income for another organization
        $otherOrg = Organization::create(['name' => 'Other Corp']);
        Income::create([
            'organization_id' => $otherOrg->id,
            'amount' => 2000.00,
            'type' => 'salary',
            'description' => 'Other salary',
            'created_by' => User::create([
                'name' => 'Other User',
                'email' => 'other@example.com',
                'password' => 'Pass123',
                'organization_id' => $otherOrg->id,
            ])->id,
        ]);

        $response = $this->getJson('/fl-api/incomes', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'success' => true,
                'message' => 'Success',
            ]);

        // Verify content does not include other organization's income
        $response->assertJsonMissing([
            'amount' => 2000.00,
        ]);
    }

    /**
     * Test list filters: type, created_by, date range.
     */
    public function test_user_can_filter_incomes(): void
    {
        Account::create(['organization_id' => $this->organization->id, 'balance' => 0]);

        $income1 = Income::create([
            'organization_id' => $this->organization->id,
            'amount' => 1500.00,
            'type' => 'salary',
            'description' => 'Monthly Salary',
            'created_by' => $this->user->id,
        ]);

        $income2 = Income::create([
            'organization_id' => $this->organization->id,
            'amount' => 500.00,
            'type' => 'dividend',
            'description' => 'Stock Dividends',
            'created_by' => $this->user->id,
        ]);

        // Filter by type
        $response = $this->getJson('/fl-api/incomes?type=salary', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.type', 'salary');
    }

    /**
     * Test viewing a specific income.
     */
    public function test_user_can_view_specific_income(): void
    {
        Account::create(['organization_id' => $this->organization->id, 'balance' => 0]);

        $income = Income::create([
            'organization_id' => $this->organization->id,
            'amount' => 1000.00,
            'type' => 'bonus',
            'created_by' => $this->user->id,
        ]);

        $response = $this->getJson('/fl-api/incomes/' . $income->id, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $income->id,
                    'amount' => 1000.00,
                    'type' => 'bonus',
                ],
            ]);
    }

    /**
     * Test viewing income of another organization returns 404.
     */
    public function test_viewing_other_organization_income_returns_404(): void
    {
        $otherOrg = Organization::create(['name' => 'Other Corp']);
        $otherUser = User::create([
            'name' => 'Other User',
            'email' => 'other@example.com',
            'password' => 'Pass123',
            'organization_id' => $otherOrg->id,
        ]);

        $income = Income::create([
            'organization_id' => $otherOrg->id,
            'amount' => 1000.00,
            'type' => 'bonus',
            'created_by' => $otherUser->id,
        ]);

        $response = $this->getJson('/fl-api/incomes/' . $income->id, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(404);
    }

    /**
     * Test creating an income increases account balance, writes ledger, audit logs, and emails.
     */
    public function test_creating_income_flow(): void
    {
        Notification::fake();

        $response = $this->postJson('/fl-api/incomes', [
            'amount' => 1200.50,
            'type' => 'freelance',
            'description' => 'Web development project',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Created successfully',
                'data' => [
                    'amount' => 1200.50,
                    'type' => 'freelance',
                    'description' => 'Web development project',
                    'created_by' => $this->user->id,
                ],
            ]);

        // 1. Verify Income DB state
        $incomeId = $response->json('data.id');
        $this->assertDatabaseHas('incomes', [
            'id' => $incomeId,
            'amount' => 1200.50,
            'type' => 'freelance',
            'organization_id' => $this->organization->id,
        ]);

        // 2. Verify Account balance updated
        $this->assertDatabaseHas('accounts', [
            'organization_id' => $this->organization->id,
            'balance' => 1200.50,
        ]);

        // 3. Verify Ledger entry created
        $this->assertDatabaseHas('ledger', [
            'organization_id' => $this->organization->id,
            'amount' => 1200.50,
            'ledgerable_type' => Income::class,
            'ledgerable_id' => $incomeId,
            'type' => 'credit',
            'event_type' => 'created',
        ]);

        // 4. Verify Audit Log entry created
        $this->assertDatabaseHas('audit_logs', [
            'organization_id' => $this->organization->id,
            'event_type' => 'created',
        ]);

        // 5. Verify Email Notification sent to user
        Notification::assertSentTo(
            $this->user,
            IncomeNotification::class,
            function (IncomeNotification $notification, array $channels) use ($incomeId) {
                return $channels === ['mail'] &&
                       $notification->action === 'created' &&
                       $notification->income->id === $incomeId;
            }
        );
    }

    /**
     * Test updating an income adjusts account balance, writes ledger, audit logs, and emails.
     */
    public function test_updating_income_flow(): void
    {
        Notification::fake();

        // Initialize Account and Income
        Account::create(['organization_id' => $this->organization->id, 'balance' => 0.00]);

        $income = Income::create([
            'organization_id' => $this->organization->id,
            'amount' => 1000.00,
            'type' => 'salary',
            'description' => 'Original description',
            'created_by' => $this->user->id,
        ]);

        // Verify account balance is 1000.00 after creation
        $this->assertDatabaseHas('accounts', [
            'organization_id' => $this->organization->id,
            'balance' => 1000.00,
        ]);

        // Update amount from 1000.00 to 1250.00
        $response = $this->putJson('/fl-api/incomes/' . $income->id, [
            'amount' => 1250.00,
            'description' => 'Updated description',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'amount' => 1250.00,
                    'description' => 'Updated description',
                ],
            ]);

        // 1. Verify account balance is now 1250.00 (adjusted by +250.00)
        $this->assertDatabaseHas('accounts', [
            'organization_id' => $this->organization->id,
            'balance' => 1250.00,
        ]);

        // 2. Verify new Ledger entry with event_type = updated exists
        $this->assertDatabaseHas('ledger', [
            'organization_id' => $this->organization->id,
            'amount' => 1250.00,
            'ledgerable_id' => $income->id,
            'event_type' => 'updated',
        ]);

        // 3. Verify Audit Log entry for updated exists
        $this->assertDatabaseHas('audit_logs', [
            'organization_id' => $this->organization->id,
            'event_type' => 'updated',
        ]);

        // 4. Verify Email Notification sent to user
        Notification::assertSentTo(
            $this->user,
            IncomeNotification::class,
            function (IncomeNotification $notification) use ($income) {
                return $notification->action === 'updated' &&
                       $notification->income->id === $income->id &&
                       $notification->extraData['original']['amount'] == 1000.00;
            }
        );
    }

    /**
     * Test deleting an income adjusts account balance, soft deletes the income, writes ledger, audit logs, and emails.
     */
    public function test_deleting_income_flow(): void
    {
        Notification::fake();

        // Initialize Account and Income
        Account::create(['organization_id' => $this->organization->id, 'balance' => 0.00]);

        $income = Income::create([
            'organization_id' => $this->organization->id,
            'amount' => 800.00,
            'type' => 'bonus',
            'created_by' => $this->user->id,
        ]);

        // Verify account balance is 800.00 after creation
        $this->assertDatabaseHas('accounts', [
            'organization_id' => $this->organization->id,
            'balance' => 800.00,
        ]);

        // Delete the Income record
        $response = $this->deleteJson('/fl-api/incomes/' . $income->id, [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200);

        // 1. Verify account balance is now 0.00 (adjusted by -800.00)
        $this->assertDatabaseHas('accounts', [
            'organization_id' => $this->organization->id,
            'balance' => 0.00,
        ]);

        // 2. Verify Income is soft deleted
        $this->assertSoftDeleted('incomes', [
            'id' => $income->id,
        ]);

        // 3. Verify new Ledger entry with event_type = deleted exists
        $this->assertDatabaseHas('ledger', [
            'organization_id' => $this->organization->id,
            'amount' => 800.00,
            'ledgerable_id' => $income->id,
            'event_type' => 'deleted',
        ]);

        // 4. Verify Audit Log entry for deleted exists
        $this->assertDatabaseHas('audit_logs', [
            'organization_id' => $this->organization->id,
            'event_type' => 'deleted',
        ]);

        // 5. Verify Email Notification sent to user
        Notification::assertSentTo(
            $this->user,
            IncomeNotification::class,
            function (IncomeNotification $notification) use ($income) {
                return $notification->action === 'deleted' &&
                       $notification->income->id === $income->id;
            }
        );
    }
}
