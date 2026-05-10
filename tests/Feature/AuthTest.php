<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the /auth/google route redirects to Google OAuth.
     */
    public function test_user_can_redirect_to_google(): void
    {
        $provider = \Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('redirect')
            ->once()
            ->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));

        Socialite::shouldReceive('driver')
            ->with('google')
            ->once()
            ->andReturn($provider);

        $response = $this->get('/auth/google');

        $response->assertRedirect();
    }

    /**
     * Test that a brand new user record is created on their first Google login.
     */
    public function test_new_user_created_on_first_google_login(): void
    {
        $this->assertDatabaseCount('users', 0);

        $abstractUser = \Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('123456');
        $abstractUser->shouldReceive('getName')->andReturn('Test User');
        $abstractUser->shouldReceive('getEmail')->andReturn('test@example.com');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://avatar.url');
        $abstractUser->id = '123456';
        $abstractUser->name = 'Test User';
        $abstractUser->email = 'test@example.com';
        $abstractUser->avatar = 'https://avatar.url';

        Socialite::shouldReceive('driver')->with('google')->andReturn(
            \Mockery::mock('Laravel\Socialite\Contracts\Provider', ['user' => $abstractUser])
        );

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/games');
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'google_id' => '123456',
            'name' => 'Test User',
        ]);
        $this->assertAuthenticated();
    }

    /**
     * Test that an existing user is logged in (not duplicated) on second login,
     * and their avatar is updated.
     */
    public function test_existing_user_logged_in_on_second_login(): void
    {
        $user = User::factory()->create([
            'email' => 'existing@example.com',
            'google_id' => '123456',
            'avatar' => 'https://old.avatar',
        ]);

        $abstractUser = \Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('123456');
        $abstractUser->shouldReceive('getName')->andReturn('Existing User');
        $abstractUser->shouldReceive('getEmail')->andReturn('existing@example.com');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://new.avatar');
        $abstractUser->id = '123456';
        $abstractUser->name = 'Existing User';
        $abstractUser->email = 'existing@example.com';
        $abstractUser->avatar = 'https://new.avatar';

        Socialite::shouldReceive('driver')->with('google')->andReturn(
            \Mockery::mock('Laravel\Socialite\Contracts\Provider', ['user' => $abstractUser])
        );

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/games');
        $this->assertDatabaseCount('users', 1);
        $this->assertEquals('https://new.avatar', $user->fresh()->avatar);
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test that unauthenticated guests are redirected from all protected routes.
     */
    public function test_guest_redirected_from_protected_routes(): void
    {
        $protectedRoutes = [
            '/games',
            '/leaderboard',
            '/rooms/create',
            '/profile',
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);
            $response->assertRedirect('/login');
        }
    }
}
