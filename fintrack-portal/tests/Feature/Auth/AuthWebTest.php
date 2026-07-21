<?php

namespace Tests\Feature\Auth;

use FinTrack\Core\Models\Organization;
use FinTrack\Core\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthWebTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test login page renders successfully.
     */
    public function test_login_page_renders(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200)
            ->assertSee('FinTrack')
            ->assertSee('Welcome back!')
            ->assertSee('Sign in to your account');
    }

    /**
     * Test registration page renders successfully.
     */
    public function test_register_page_renders(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200)
            ->assertSee('FinTrack')
            ->assertSee('Create Your Account')
            ->assertSee("Let's set up your account");
    }

    /**
     * Test successful registration via the web form.
     */
    public function test_user_can_register_via_web_form(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Pass123',
            'organization' => 'Doe Enterprises',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('status', 'Account created successfully! Please sign in.');

        $this->assertDatabaseHas('organizations', [
            'name' => 'Doe Enterprises',
        ]);

        $organization = Organization::where('name', 'Doe Enterprises')->first();
        $this->assertNotNull($organization);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'organization_id' => $organization->id,
        ]);
    }

    /**
     * Test registration validation errors.
     */
    public function test_register_validation_errors(): void
    {
        $response = $this->post('/register', [
            'name' => 'Jo', // too short
            'email' => 'invalid-email',
            'password' => '123', // too short, no mixedcase/numbers
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    /**
     * Test successful login via the web form.
     */
    public function test_user_can_login_via_web_form(): void
    {
        $org = Organization::create(['name' => 'Acme Corp']);
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'Pass123',
            'organization_id' => $org->id,
        ]);

        $response = $this->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'Pass123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        // Verify Sanctum token is saved in session
        $this->assertTrue(session()->has('api_token'));
    }

    /**
     * Test login failure.
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        $org = Organization::create(['name' => 'Acme Corp']);
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'Pass123',
            'organization_id' => $org->id,
        ]);

        $response = $this->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest('web');
    }

    /**
     * Test guest cannot access the dashboard.
     */
    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated user can access the dashboard.
     */
    public function test_authenticated_user_can_access_dashboard(): void
    {
        $org = Organization::create(['name' => 'Acme Corp']);
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'Pass123',
            'organization_id' => $org->id,
        ]);

        $response = $this->actingAs($user, 'web')->get('/dashboard');

        $response->assertStatus(200)
            ->assertSee('Welcome to the dashboard')
            ->assertSee('Jane Doe');
    }

    /**
     * Test user logout.
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

        // Login first and set session
        $this->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'Pass123',
        ]);

        $this->assertAuthenticated('web');

        // Perform logout
        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest('web');
        $this->assertFalse(session()->has('api_token'));
    }
}
