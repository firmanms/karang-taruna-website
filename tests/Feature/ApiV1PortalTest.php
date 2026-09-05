<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiV1PortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_public_news_api_endpoint(): void
    {
        $response = $this->getJson('/api/v1/news');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'meta' => ['current_page', 'per_page', 'total', 'last_page'],
            ]);
    }

    public function test_public_statistics_api_endpoint(): void
    {
        $response = $this->getJson('/api/v1/statistics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'total_districts',
                    'total_villages',
                    'total_units',
                    'total_members',
                    'total_events',
                    'total_programs',
                    'total_achievements',
                ],
            ]);
    }

    public function test_auth_login_and_sanctum_profile(): void
    {
        $user = User::first();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user',
                    'token',
                    'token_type',
                ],
            ]);

        $token = $response->json('data.token');

        // Test authenticated profile
        $profileResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/user/profile');

        $profileResponse->assertStatus(200)
            ->assertJsonPath('data.email', $user->email);
    }
}
