<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $penduduk = \App\Models\Penduduk::create([
            'nik' => '3201010101010111',
            'nama' => 'Warga Test',
            'rt' => '01',
            'rw' => '01',
            'alamat' => 'Kp. Contoh',
        ]);

        $response = $this->post('/login', [
            'nik' => $penduduk->nik,
            'nama' => $penduduk->nama,
        ]);

        $this->assertAuthenticated('warga');
        $response->assertRedirect(route('warga.rt.surat.index', ['rt' => '01', 'rw' => '01']));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
