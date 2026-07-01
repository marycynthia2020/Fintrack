<?php

namespace Tests\Feature\Auth;

use FinTrack\Core\Models\Organization;
use FinTrack\Core\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SessionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful login with correct credentials.
     */
    public function test_user_can_login_with_correct_credentials(): void
    {
        $org = Organization::create(['name' => 'Acme Corp']);
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'Pass123',
            'organization_id' => $org->id,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'jane@example.com',
            'password' => 'Pass123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'organization_id',
                        'organization' => [
                            'id',
                            'name',
                            'created_at',
                            'updated_at',
                        ],
                        'created_at',
                        'updated_at',
                    ],
                    'token',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Login successful.',
                'data' => [
                    'user' => [
                        'name' => 'Jane Doe',
                        'email' => 'jane@example.com',
                        'organization' => [
                            'name' => 'Acme Corp',
                        ],
                    ],
                ],
            ]);

        $this->assertNotEmpty($response->json('data.token'));
    }

    /**
     * Test login fails with invalid credentials.
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        $org = Organization::create(['name' => 'Acme Corp']);
        User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'Pass123',
            'organization_id' => $org->id,
        ]);

        // Wrong password
        $response = $this->postJson('/api/login', [
            'email' => 'jane@example.com',
            'password' => 'WrongPass',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials.',
            ]);

        // Non-existent email
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'Pass123',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials.',
            ]);
    }

    /**
     * Test that validation rules are enforced.
     */
    public function test_login_validation_rules(): void
    {
        // 1. Missing email
        $response = $this->postJson('/api/login', [
            'password' => 'Pass123',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        // 2. Missing password
        $response = $this->postJson('/api/login', [
            'email' => 'jane@example.com',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);

        // 3. Invalid email format
        $response = $this->postJson('/api/login', [
            'email' => 'not-an-email',
            'password' => 'Pass123',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test that the single active token policy is enforced.
     */
    public function test_login_enforces_single_active_token(): void
    {
        $org = Organization::create(['name' => 'Acme Corp']);
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'Pass123',
            'organization_id' => $org->id,
        ]);

        // Create two initial dummy tokens
        $user->createToken('old-token-1');
        $user->createToken('old-token-2');

        $this->assertEquals(2, $user->tokens()->count());

        // Perform login
        $response = $this->postJson('/api/login', [
            'email' => 'jane@example.com',
            'password' => 'Pass123',
        ]);

        $response->assertStatus(200);

        // Verify only one token remains
        $this->assertEquals(1, $user->tokens()->count());
    }

    /**
     * Test that the created token is valid for exactly 48 hours.
     */
    public function test_login_token_expires_in_48_hours(): void
    {
        $org = Organization::create(['name' => 'Acme Corp']);
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'Pass123',
            'organization_id' => $org->id,
        ]);

        $now = Carbon::now()->microsecond(0);
        Carbon::setTestNow($now);

        $response = $this->postJson('/api/login', [
            'email' => 'jane@example.com',
            'password' => 'Pass123',
        ]);

        $response->assertStatus(200);

        $tokenModel = $user->tokens()->first();
        $this->assertNotNull($tokenModel);
        
        $expectedExpiry = $now->copy()->addHours(48);
        $this->assertTrue($tokenModel->expires_at->equalTo($expectedExpiry));

        Carbon::setTestNow(); // Clean up test time
    }

    /**
     * Test successful logout.
     */
    public function test_user_can_logout(): void
    {
        $org = Organization::create(['name' => 'Acme Corp']);
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'Pass123',
            'organization_id' => $org->id,
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'jane@example.com',
            'password' => 'Pass123',
        ]);

        $token = $loginResponse->json('data.token');

        $this->assertEquals(1, $user->tokens()->count());

        // Call logout endpoint with token
        $response = $this->postJson('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logged out successfully.',
            ]);

        // Verify token is deleted
        $this->assertEquals(0, $user->tokens()->count());
    }

    /**
     * Test logout fails when unauthenticated.
     */
    public function test_logout_fails_when_unauthenticated(): void
    {
        $response = $this->postJson('/api/logout');

        // Should return 401 unauthenticated
        $response->assertStatus(401);
    }
}
