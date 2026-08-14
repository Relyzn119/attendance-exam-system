<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $email = 'testuser' . time() . '@example.com';

    $response = $this->post('/register', [
        'nama' => 'Test User',
        'email' => $email,
        'password' => 'password',
        'password_confirmation' => 'password',
        'jenis_kelamin' => 'L',
        'alamat' => 'Jl. Kebon Sirih No. 12',
        'no_hp' => '081234567890',
        'nik' => '999' . rand(10000000000, 99999999999),
        'npwp' => '12345678901234',
    ]);

    $response->assertSessionHasNoErrors();
    
    // Verifikasi user benar-benar terbuat di DB
    $this->assertDatabaseHas('users', [
        'email' => $email,
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));
});