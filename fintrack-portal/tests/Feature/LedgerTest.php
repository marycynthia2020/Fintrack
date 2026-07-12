<?php

namespace Tests\Feature;

use FinTrack\Core\Models\Organization;
use FinTrack\Core\Models\User;
use FinTrack\FinLib\Models\Account;
use FinTrack\FinLib\Models\Income;
use FinTrack\FinLib\Models\Expense;
use FinTrack\FinLib\Models\Ledger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LedgerTest extends TestCase
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
     * Test retrieving the transactions list with tenancy isolation and resource transformation.
     */
    public function test_user_can_list_organization_transactions(): void
    {
        // Setup initial balance
        Account::create(['organization_id' => $this->organization->id, 'balance' => 1000.00]);

        // Creating income triggers a ledger entry (type: credit, event_type: created)
        $income = Income::create([
            'organization_id' => $this->organization->id,
            'amount' => 1500.00,
            'type' => 'salary',
            'description' => 'Monthly Salary',
            'created_by' => $this->user->id,
        ]);

        // Explicitly set the created_at of the income's ledger entry to be earlier
        Ledger::where('ledgerable_type', Income::class)
            ->where('ledgerable_id', $income->id)
            ->update(['created_at' => now()->subMinutes(5)]);

        // Creating expense triggers a ledger entry (type: debit, event_type: created)
        $expense = Expense::create([
            'organization_id' => $this->organization->id,
            'amount' => 300.00,
            'type' => 'rent',
            'description' => 'Office Rent',
            'created_by' => $this->user->id,
        ]);

        // Create transaction for another organization
        $otherOrg = Organization::create(['name' => 'Other Corp']);
        $otherUser = User::create([
            'name' => 'Other User',
            'email' => 'other@example.com',
            'password' => 'Pass123',
            'organization_id' => $otherOrg->id,
        ]);
        Account::create(['organization_id' => $otherOrg->id, 'balance' => 0.00]);
        Income::create([
            'organization_id' => $otherOrg->id,
            'amount' => 9999.00,
            'type' => 'bonus',
            'created_by' => $otherUser->id,
        ]);

        // Get transactions
        $response = $this->getJson('/fl-api/transactions', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.amount', '300.00 NGN')
            ->assertJsonPath('data.0.type', 'Expenses')
            ->assertJsonPath('data.0.ledgerable_type', Expense::class)
            ->assertJsonPath('data.0.ledgerable.id', $expense->id)
            ->assertJsonPath('data.0.creator.id', $this->user->id)
            ->assertJsonPath('data.1.amount', '1,500.00 NGN')
            ->assertJsonPath('data.1.type', 'Income')
            ->assertJsonPath('data.1.ledgerable_type', Income::class)
            ->assertJsonPath('data.1.ledgerable.id', $income->id)
            ->assertJsonMissing([
                'amount' => '9,999.00 NGN',
            ]);
    }

    /**
     * Test filtering ledger transactions by type, event_type, created_by, and date range.
     */
    public function test_user_can_filter_transactions(): void
    {
        Account::create(['organization_id' => $this->organization->id, 'balance' => 1000.00]);

        $income = Income::create([
            'organization_id' => $this->organization->id,
            'amount' => 1500.00,
            'type' => 'salary',
            'created_by' => $this->user->id,
        ]);

        $expense = Expense::create([
            'organization_id' => $this->organization->id,
            'amount' => 300.00,
            'type' => 'rent',
            'created_by' => $this->user->id,
        ]);

        // Filter by type = Income
        $response = $this->getJson('/fl-api/transactions?type=Income', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.type', 'Income');

        // Filter by type = Expenses
        $response = $this->getJson('/fl-api/transactions?type=Expenses', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.type', 'Expenses');

        // Filter by event_type = created
        $response = $this->getJson('/fl-api/transactions?event_type=created', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');

        // Filter by creator
        $response = $this->getJson('/fl-api/transactions?created_by=' . $this->user->id, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');

        // Filter by date range (start_date / end_date)
        $today = now()->format('Y-m-d');
        $response = $this->getJson("/fl-api/transactions?start_date={$today}&end_date={$today}", [
            'Authorization' => 'Bearer ' . $this->token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /**
     * Test unauthenticated access returns 401.
     */
    public function test_unauthenticated_user_cannot_access_transactions(): void
    {
        $response = $this->getJson('/fl-api/transactions');

        $response->assertStatus(401);
    }
}
