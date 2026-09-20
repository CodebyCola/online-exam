<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeader('Origin', 'http://localhost:3000');
    }

    public function test_user_dapat_login_dengan_kredensial_benar(): void
    {
        $user = User::factory()->create([
            'email' => 'dosen@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'dosen@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.email', 'dosen@example.com');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_gagal_dengan_password_salah(): void
    {
        User::factory()->create([
            'email' => 'dosen@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'dosen@example.com',
            'password' => 'password-salah',
        ]);

        $response->assertStatus(422);
        $this->assertGuest();
    }

    public function test_endpoint_me_menolak_request_tanpa_login(): void
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(401);
    }

    public function test_endpoint_me_mengembalikan_data_user_yang_login(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/auth/me');

        $response->assertStatus(200);
        $response->assertJsonPath('data.id', $user->id);
    }

    public function test_user_dapat_logout(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertStatus(200);

        // Baru logout, memakai cookie/session yang sama
        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(200);
        $this->assertGuest('web');
    }

    public function test_user_dapat_register_dengan_data_valid(): void
    {
        $this->postJson('/api/auth/register', [
            'name' => 'Budi Setiawan',
            'email' => "budi@example.com",
            'password' => 'password123',
            'role' => 'dosen',
        ])->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'role' => 'dosen',
        ]);

        $this->assertAuthenticated();
    }

    public function test_register_gagal_jika_email_sudah_dipakai(): void
    {
        User::factory()->create([
            'email' => 'sudah-ada@example.com',
        ]);

        $response = $this->postJson('/api/auth/register', [
            'name' => 'Nama Lain',
            'email' => 'sudah-ada@example.com',
            'password' => 'password123',
            'role' => 'mahasiswa',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_register_gagal_jika_role_tidak_valid(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Nama Test',
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'admin', // bukan dosen/mahasiswa
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('role');
    }

    public function test_register_gagal_jika_field_wajib_kosong(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Nama Test',
            // email sengaja dikosongkan
            'password' => 'password123',
            'role' => 'mahasiswa',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }
}
