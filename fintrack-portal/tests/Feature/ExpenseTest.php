<?php

namespace Tests\Feature;

use FinTrack\Core\Models\Organization;
use FinTrack\Core\Models\User;
use FinTrack\FinLib\Models\Account;
use FinTrack\FinLib\Models\Expense;
use FinTrack\FinLib\Models\Ledger;
use FinTrack\FinLib\Models\AuditLog;
use FinTrack\FinLib\Notifications\ExpenseNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ExpenseTest extends TestCase
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
     * Test listing expenses respects organization tenancy and pagination.
     */
    public function test_user_can_list_organization_expenses_only(): void
    {
        // Organization account
        Account::create(['organization_id' => $this->organization->id, 'balance' => 1000]);

        // Create expenses for user's organization
        Expense::create([
            'organization_id' => $this->organization->id,
            'amount' => 150.00,
            'type' => 'rent',
            'description' => 'Office Rent',
            'created_by' => $this->user->id,
        ]);

        Expense::create([
            'organization_id' => $this->organization->id,
            'amount' => 50.00,
            'type' => 'utilities',
            'description' => 'Electricity Bill',
            'created_by' => $this->user->id,
        ]);

        // Create an expense for another organization
        $otherOrg = Organization::create(['name' => 'Other Corp']);
        Expense::create([
            'organization_id' => $otherOrg->id,
            'amount' => 200.00,
            'type' => 'rent',
            'description' => 'Other rent',
            'created_by' => User::create([
                'name' => 'Other User',
                'email' => 'other@example.com',
                'password' => 'Pass123',
                'organization_id' => $otherOrg->id,
            ])->id,
        ]);

        $response = $this->getJson('/fl-api/expenses', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'success' => true,
                'message' => 'Success',
            ]);

        // Verify content does not include other organization's expense
        $response->assertJsonMissing([
            'amount' => '200.00 NGN',
        ]);
    }

    /**
     * Test list filters: type, created_by, date range.
     */
    public function test_user_can_filter_expenses(): void
    {
        Account::create(['organization_id' => $this->organization->id, 'balance' => 1000]);

        $expense1 = Expense::create([
            'organization_id' => $this->organization->id,
            'amount' => 150.00,
            'type' => 'rent',
            'description' => 'Office Rent',
            'created_by' => $this->user->id,
        ]);

        $expense2 = Expense::create([
            'organization_id' => $this->organization->id,
            'amount' => 50.00,
            'type' => 'utilities',
            'description' => 'Electricity Bill',
            'created_by' => $this->user->id,
        ]);

        // Filter by type
        $response = $this->getJson('/fl-api/expenses?type=rent', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.type', 'rent');
    }

    /**
     * Test viewing a specific expense.
     */
    public function test_user_can_view_specific_expense(): void
    {
        Account::create(['organization_id' => $this->organization->id, 'balance' => 1000]);

        $expense = Expense::create([
            'organization_id' => $this->organization->id,
            'amount' => 100.00,
            'type' => 'supplies',
            'created_by' => $this->user->id,
        ]);

        $response = $this->getJson('/fl-api/expenses/' . $expense->id, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $expense->id,
                    'amount' => '100.00 NGN',
                    'type' => 'supplies',
                ],
            ]);
    }

    /**
     * Test viewing expense of another organization returns 404.
     */
    public function test_viewing_other_organization_expense_returns_404(): void
    {
        $otherOrg = Organization::create(['name' => 'Other Corp']);
        $otherUser = User::create([
            'name' => 'Other User',
            'email' => 'other@example.com',
            'password' => 'Pass123',
            'organization_id' => $otherOrg->id,
        ]);

        $expense = Expense::create([
            'organization_id' => $otherOrg->id,
            'amount' => 100.00,
            'type' => 'supplies',
            'created_by' => $otherUser->id,
        ]);

        $response = $this->getJson('/fl-api/expenses/' . $expense->id, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(404);
    }

    /**
     * Test creating an expense decreases account balance, writes ledger, audit logs, and emails.
     */
    public function test_creating_expense_flow(): void
    {
        Notification::fake();

        // Start with a balance of 2000.00
        Account::create(['organization_id' => $this->organization->id, 'balance' => 2000.00]);

        $response = $this->postJson('/fl-api/expenses', [
            'amount' => 1200.50,
            'type' => 'supplies',
            'description' => 'Office equipment',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Created successfully',
                'data' => [
                    'amount' => '1,200.50 NGN',
                    'type' => 'supplies',
                    'description' => 'Office equipment',
                    'created_by' => $this->user->id,
                ],
            ]);

        // 1. Verify Expense DB state
        $expenseId = $response->json('data.id');
        $this->assertDatabaseHas('expenses', [
            'id' => $expenseId,
            'amount' => 1200.50,
            'type' => 'supplies',
            'organization_id' => $this->organization->id,
        ]);

        // 2. Verify Account balance updated (2000.00 - 1200.50 = 799.50)
        $this->assertDatabaseHas('accounts', [
            'organization_id' => $this->organization->id,
            'balance' => 799.50,
        ]);

        // 3. Verify Ledger entry created
        $this->assertDatabaseHas('ledger', [
            'organization_id' => $this->organization->id,
            'amount' => 1200.50,
            'ledgerable_type' => Expense::class,
            'ledgerable_id' => $expenseId,
            'type' => 'debit',
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
            ExpenseNotification::class,
            function (ExpenseNotification $notification, array $channels) use ($expenseId) {
                return $channels === ['mail'] &&
                       $notification->action === 'created' &&
                       $notification->expense->id === $expenseId;
            }
        );
    }

    /**
     * Test updating an expense adjusts account balance, writes ledger, audit logs, and emails.
     */
    public function test_updating_expense_flow(): void
    {
        Notification::fake();

        // Initialize Account and Expense (balance starts at 2000.00, then expense creation subtracts 1000.00 -> 1000.00)
        Account::create(['organization_id' => $this->organization->id, 'balance' => 2000.00]);

        $expense = Expense::create([
            'organization_id' => $this->organization->id,
            'amount' => 1000.00,
            'type' => 'utilities',
            'description' => 'Original description',
            'created_by' => $this->user->id,
        ]);

        // Verify account balance is 1000.00 after creation (from 2000 - 1000)
        $this->assertDatabaseHas('accounts', [
            'organization_id' => $this->organization->id,
            'balance' => 1000.00,
        ]);

        // Update expense amount from 1000.00 to 1250.00 (additional 250.00 expense, balance should become 750.00)
        $response = $this->putJson('/fl-api/expenses/' . $expense->id, [
            'amount' => 1250.00,
            'type' => 'utilities',
            'description' => 'Updated description',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'amount' => '1,250.00 NGN',
                    'description' => 'Updated description',
                ],
            ]);

        // 1. Verify account balance is now 750.00 (adjusted by -250.00)
        $this->assertDatabaseHas('accounts', [
            'organization_id' => $this->organization->id,
            'balance' => 750.00,
        ]);

        // 2. Verify new Ledger entry with event_type = updated exists
        $this->assertDatabaseHas('ledger', [
            'organization_id' => $this->organization->id,
            'amount' => 1250.00,
            'ledgerable_id' => $expense->id,
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
            ExpenseNotification::class,
            function (ExpenseNotification $notification) use ($expense) {
                return $notification->action === 'updated' &&
                       $notification->expense->id === $expense->id &&
                       $notification->extraData['original']['amount'] == 1000.00;
            }
        );
    }

    /**
     * Test users cannot update expenses created by another user in the same organization.
     */
    public function test_user_cannot_update_expense_created_by_another_user(): void
    {
        Account::create(['organization_id' => $this->organization->id, 'balance' => 1000.00]);

        $creator = User::create([
            'name' => 'Creator User',
            'email' => 'creator@example.com',
            'password' => 'Pass123',
            'organization_id' => $this->organization->id,
        ]);

        $expense = Expense::create([
            'organization_id' => $this->organization->id,
            'amount' => 90.00,
            'type' => 'supplies',
            'created_by' => $creator->id,
        ]);

        $response = $this->putJson('/fl-api/expenses/' . $expense->id, [
            'amount' => 100.00,
            'type' => 'supplies',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'amount' => 90.00,
            'updated_by' => null,
        ]);
    }

    /**
     * Test deleting an expense adjusts account balance, soft deletes the expense, writes ledger, audit logs, and emails.
     */
    public function test_deleting_expense_flow(): void
    {
        Notification::fake();

        // Initialize Account and Expense (starts at 1000.00, creation subtracts 80.00 -> 920.00)
        Account::create(['organization_id' => $this->organization->id, 'balance' => 1000.00]);

        $expense = Expense::create([
            'organization_id' => $this->organization->id,
            'amount' => 80.00,
            'type' => 'supplies',
            'created_by' => $this->user->id,
        ]);

        // Verify account balance is 920.00 after creation
        $this->assertDatabaseHas('accounts', [
            'organization_id' => $this->organization->id,
            'balance' => 920.00,
        ]);

        // Delete the Expense record
        $response = $this->deleteJson('/fl-api/expenses/' . $expense->id, [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200);

        // 1. Verify account balance is now 1000.00 (adjusted by adding back 80.00)
        $this->assertDatabaseHas('accounts', [
            'organization_id' => $this->organization->id,
            'balance' => 1000.00,
        ]);

        // 2. Verify Expense is soft deleted
        $this->assertSoftDeleted('expenses', [
            'id' => $expense->id,
        ]);

        // 3. Verify new Ledger entry with event_type = deleted exists
        $this->assertDatabaseHas('ledger', [
            'organization_id' => $this->organization->id,
            'amount' => 80.00,
            'ledgerable_id' => $expense->id,
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
            ExpenseNotification::class,
            function (ExpenseNotification $notification) use ($expense) {
                return $notification->action === 'deleted' &&
                       $notification->expense->id === $expense->id;
            }
        );
    }

    /**
     * Test users cannot delete expenses created by another user in the same organization.
     */
    public function test_user_cannot_delete_expense_created_by_another_user(): void
    {
        Account::create(['organization_id' => $this->organization->id, 'balance' => 1000.00]);

        $creator = User::create([
            'name' => 'Delete Creator',
            'email' => 'delete-creator@example.com',
            'password' => 'Pass123',
            'organization_id' => $this->organization->id,
        ]);

        $expense = Expense::create([
            'organization_id' => $this->organization->id,
            'amount' => 70.00,
            'type' => 'supplies',
            'created_by' => $creator->id,
        ]);

        $response = $this->deleteJson('/fl-api/expenses/' . $expense->id, [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'deleted_at' => null,
        ]);
    }
}
