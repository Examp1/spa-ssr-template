<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MainRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_frontpage()
    {
        $response = $this->get('/');

        $response
            ->assertStatus(200)
            ->assertSee('Home');
    }

    public function test_admin_as_user()
    {
        $response = $this //->followingRedirects()
            ->get('/admin')
            // ->assertRedirect('admin.login');
            ->assertStatus(302);
    }

    public function test_admin_as_admin()
    {
        $admin = Admin::factory()->create(['role_id' => 1, 'status' => 1]);
        $this->assertNotNull($admin);
        $response = $this->actingAs($admin)
            ->get('/admin')
            ->assertStatus(200);
    }

    public function test_admin_login_as_user()
    {
        $user = Admin::factory()->create(['status' => 1]);
        $this->assertNotNull($user);
        $response = $this->followingRedirects()
            ->actingAs($user)
            ->post('/admin/login', [
                'email' => $user->email,
                'password' => 'password',
            ])
            ->assertSee('Home'); //redirect to back()
            //dd($response->getContent());

    }
    public function test_admin_login_as_admin()
    {
        $admin = Admin::factory()->create(['role_id' => 1, 'status' => 1]);
        $this->assertNotNull($admin);
        $response = $this->actingAs($admin)
            ->post('/admin/login', [
                'email' => $admin->email,
                'password' => 'password',
            ])
            ->assertStatus(302)
            ->assertRedirect('admin');
    }
}
