<?php

namespace Tests\Feature\Auth;

use FinTrack\Core\Models\Organization;
use FinTrack\Core\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful registration with a custom organization name.
     */
    public function test_user_can_register_with_organization(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Pass123',
            'organization' => 'Doe Enterprises',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
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
            ])
            ->assertJson([
                'success' => true,
                'message' => 'User registered successfully.',
                'data' => [
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'organization' => [
                        'name' => 'Doe Enterprises',
                    ],
                ],
            ]);

        $this->assertDatabaseHas('organizations', [
            'name' => 'Doe Enterprises',
        ]);

        $organization = Organization::where('name', 'Doe Enterprises')->first();

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'organization_id' => $organization->id,
        ]);
    }

    /**
     * Test successful registration without an organization name (falls back to user's name).
     */
    public function test_user_can_register_without_organization_falls_back_to_name(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'Pass123',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Jane Doe',
                    'email' => 'jane@example.com',
                    'organization' => [
                        'name' => 'Jane Doe',
                    ],
                ],
            ]);

        $organization = Organization::where('name', 'Jane Doe')->first();
        $this->assertNotNull($organization);

        $this->assertDatabaseHas('users', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'organization_id' => $organization->id,
        ]);
    }

    /**
     * Test validation rules for registration.
     */
    public function test_registration_validation_rules(): void
    {
        // 1. Short name (less than 3 chars)
        $response = $this->postJson('/api/register', [
            'name' => 'Jo',
            'email' => 'jo@example.com',
            'password' => 'Pass123',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

        // 2. Invalid email
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'not-an-email',
            'password' => 'Pass123',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        // 3. Password too short (less than 4 chars)
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'P12',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);

        // 4. Password missing uppercase
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'pass123',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);

        // 5. Password missing lowercase
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'PASS123',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);

        // 6. Password missing number
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test duplicate email registration fails.
     */
    public function test_user_cannot_register_with_existing_email(): void
    {
        // Pre-create a user and organization using Core models
        $org = Organization::create(['name' => 'Existing Org']);
        User::create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => 'Pass123',
            'organization_id' => $org->id,
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'existing@example.com',
            'password' => 'Pass123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
