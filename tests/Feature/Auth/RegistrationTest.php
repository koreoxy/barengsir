<?php

namespace Tests\Feature\Auth;

use App\Models\Branch;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_vendor_can_register_and_syncs_branch_details(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Owner Name',
            'email'                 => 'owner@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'business_name'         => 'Mitra Sukses',
            'business_phone'        => '08123456789',
            'business_address'      => 'Jl. Pahlawan No. 45',
            'branch_name'           => 'Cabang Utama',
        ]);

        // Assert redirect ke login page
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        // Assert database memiliki user
        $this->assertDatabaseHas('users', [
            'name'  => 'Owner Name',
            'email' => 'owner@example.com',
            'role'  => 'admin',
        ]);

        // Assert database memiliki vendor
        $this->assertDatabaseHas('vendors', [
            'name'    => 'Mitra Sukses',
            'email'   => 'owner@example.com',
            'phone'   => '08123456789',
            'address' => 'Jl. Pahlawan No. 45',
        ]);

        // Assert database memiliki branch dengan data telepon & alamat yang tersinkronisasi
        $this->assertDatabaseHas('branches', [
            'name'    => 'Cabang Utama',
            'phone'   => '08123456789',
            'address' => 'Jl. Pahlawan No. 45',
        ]);
    }
}

