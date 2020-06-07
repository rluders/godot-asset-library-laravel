<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testAdminIndexNotLoggedIn(): void
    {
        $response = $this->get('/admin');
        $response->assertForbidden();
    }

    public function testAdminIndexLoggedInUser(): void
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/admin');
        $response->assertForbidden();
    }

    public function testAdminIndexLoggedInAdmin(): void
    {
        $user = factory(User::class)->create();
        $user->is_admin = true;
        $response = $this->actingAs($user)->get('/admin');
        $response->assertOk()->assertViewIs('admin.index');
    }
}
