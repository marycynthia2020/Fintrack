<?php

namespace Tests\Feature;

use FinTrack\Core\Models\Organization;
use FinTrack\Core\Models\User;
use FinTrack\FinLib\Models\Account;
use FinTrack\FinLib\Models\Income;
use FinTrack\FinLib\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
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
     * Test retrieving the dashboard summary stats.
     */
    public function test_user_can_retrieve_dashboard_summary(): void
    {
        // 1. Create account balance
        Account::create([
            'organization_id' => $this->organization->id,
            'balance' => 5000.00
        ]);

        // 2. Create incomes for user's organization
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

        // 3. Create expenses for user's organization
        Expense::create([
            'organization_id' => $this->organization->id,
            'amount' => 600.00,
            'type' => 'rent',
            'description' => 'Office Rent',
            'created_by' => $this->user->id,
        ]);

        Expense::create([
            'organization_id' => $this->organization->id,
            'amount' => 400.00,
            'type' => 'utilities',
            'description' => 'Electricity Bill',
            'created_by' => $this->user->id,
        ]);

        // 4. Create income and expense for another organization (should be excluded)
        $otherOrg = Organization::create(['name' => 'Other Corp']);
        $otherUser = User::create([
            'name' => 'Other User',
            'email' => 'other@example.com',
            'password' => 'Pass123',
            'organization_id' => $otherOrg->id,
        ]);

        Income::create([
            'organization_id' => $otherOrg->id,
            'amount' => 9999.00,
            'type' => 'salary',
            'created_by' => $otherUser->id,
        ]);

        Expense::create([
            'organization_id' => $otherOrg->id,
            'amount' => 8888.00,
            'type' => 'rent',
            'created_by' => $otherUser->id,
        ]);

        // Make requests to dashboard summary
        $response = $this->getJson('/fl-api/dashboard/summary', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Success',
                'data' => [
                    'balance' => '6,000.00 NGN',
                    'total_income' => '2,000.00 NGN',
                    'total_expense' => '1,000.00 NGN',
                    'income_count' => 2,
                    'expense_count' => 2,
                    'total_transactions' => 4,
                ]
            ]);
    }

    /**
     * Test retrieving the dashboard summary when no records exist.
     */
    public function test_user_retrieves_empty_dashboard_summary_if_no_records(): void
    {
        $response = $this->getJson('/fl-api/dashboard/summary', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Success',
                'data' => [
                    'balance' => '0.00 NGN',
                    'total_income' => '0.00 NGN',
                    'total_expense' => '0.00 NGN',
                    'income_count' => 0,
                    'expense_count' => 0,
                    'total_transactions' => 0,
                ]
            ]);
    }

    /**
     * Test unauthenticated users cannot access the dashboard summary.
     */
    public function test_unauthenticated_user_cannot_access_dashboard_summary(): void
    {
        $response = $this->getJson('/fl-api/dashboard/summary');

        $response->assertStatus(401);
    }
}
