<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get('/dashboard');

    // Sesuaikan dengan kebutuhan sistemmu:
    // Jika guest HARUS redirect ke login:
   $response->assertRedirect('/login');

    // ATAU jika /dashboard memang BISA diakses guest tanpa login, gunakan:
    // $response->assertStatus(200);
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
});