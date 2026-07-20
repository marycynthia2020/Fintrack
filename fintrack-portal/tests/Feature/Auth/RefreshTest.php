<?php

namespace Tests\Feature\Auth;

use FinTrack\Core\Models\Organization;
use FinTrack\Core\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class RefreshTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful token refresh.
     */
    public function test_user_can_refresh_token(): void
    {
        $org = Organization::create(['name' => 'Acme Corp']);
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'Pass123',
            'organization_id' => $org->id,
        ]);

        $token = $user->createToken('login-token')->plainTextToken;

        $response = $this->postJson('/fc-api/refresh', [], [
            'Authorization' => 'Bearer ' . $token,
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
                'message' => 'Success',
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

        $newToken = $response->json('data.token');
        $this->assertNotEmpty($newToken);
        $this->assertNotEquals($token, $newToken);
    }

    /**
     * Test that refreshing a token invalidates/deletes the old token.
     */
    public function test_refresh_token_invalidates_old_token(): void
    {
        $org = Organization::create(['name' => 'Acme Corp']);
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'Pass123',
            'organization_id' => $org->id,
        ]);

        $token = $user->createToken('login-token')->plainTextToken;

        // Perform refresh
        $response = $this->postJson('/fc-api/refresh', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);

        // Clear resolved user to force Sanctum to re-evaluate the token
        $this->app['auth']->forgetUser();

        // Try to access user info or perform another refresh with the old token
        $retryResponse = $this->postJson('/fc-api/refresh', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $retryResponse->assertStatus(401);
    }

    /**
     * Test token refresh fails when unauthenticated.
     */
    public function test_refresh_fails_when_unauthenticated(): void
    {
        $response = $this->postJson('/fc-api/refresh');

        $response->assertStatus(401);
    }

    /**
     * Test that the new refreshed token is valid for exactly 48 hours.
     */
    public function test_refreshed_token_expires_in_48_hours(): void
    {
        $org = Organization::create(['name' => 'Acme Corp']);
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'Pass123',
            'organization_id' => $org->id,
        ]);

        $token = $user->createToken('login-token')->plainTextToken;

        $now = Carbon::now()->microsecond(0);
        Carbon::setTestNow($now);

        $response = $this->postJson('/fc-api/refresh', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);

        // Verify the only token is the new one (since the old one was deleted)
        $this->assertEquals(1, $user->tokens()->count());

        $tokenModel = $user->tokens()->first();
        $this->assertNotNull($tokenModel);

        $expectedExpiry = $now->copy()->addHours(48);
        $this->assertTrue($tokenModel->expires_at->equalTo($expectedExpiry));

        Carbon::setTestNow(); // Clean up test time
    }
}
