<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_is_disabled(): void
    {
        // Registrasi mandiri dinonaktifkan; akun dibuat lewat login NIK & Nama sesuai KTP
        $response = $this->get('/register');

        $response->assertRedirect(route('login'));
    }
}