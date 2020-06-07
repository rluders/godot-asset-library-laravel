<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testProfileEditView(): void
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/profile/edit');
        $response->assertOk()->assertViewIs('profile.edit');
    }
}
