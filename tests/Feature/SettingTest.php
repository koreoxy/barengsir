<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_setting_page_renders_with_vendor_and_branch(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $vendor = Vendor::create([
            'name' => 'Toko Pusat',
            'slug' => 'toko-pusat',
            'email' => 'toko@example.com',
            'phone' => '0812345678',
            'address' => 'Alamat Pusat',
        ]);
        $branch = Branch::create([
            'vendor_id' => $vendor->id,
            'name' => 'Cabang 1',
            'code' => 'BR001',
            'phone' => '0812345678',
            'address' => 'Alamat Pusat',
        ]);

        VendorUser::create([
            'vendor_id' => $vendor->id,
            'user_id' => $user->id,
            'branch_id' => null,
            'role' => 'owner',
        ]);

        $response = $this
            ->actingAs($user)
            ->withSession([
                'active_branch_id' => $branch->id,
                'active_branch_name' => $branch->name,
                'active_vendor_id' => $vendor->id,
                'active_vendor_name' => $vendor->name,
                'active_role' => 'owner',
            ])
            ->get('/setting');

        $response->assertStatus(200);
        $response->assertSee('Toko Pusat');
        $response->assertSee('Cabang 1');
    }

    public function test_vendor_and_branch_settings_can_be_updated(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $vendor = Vendor::create([
            'name' => 'Toko Pusat',
            'slug' => 'toko-pusat',
            'email' => 'toko@example.com',
            'phone' => '0812345678',
            'address' => 'Alamat Pusat',
        ]);
        $branch = Branch::create([
            'vendor_id' => $vendor->id,
            'name' => 'Cabang 1',
            'code' => 'BR001',
            'phone' => '0812345678',
            'address' => 'Alamat Pusat',
        ]);

        VendorUser::create([
            'vendor_id' => $vendor->id,
            'user_id' => $user->id,
            'branch_id' => null,
            'role' => 'owner',
        ]);

        $response = $this
            ->actingAs($user)
            ->withSession([
                'active_branch_id' => $branch->id,
                'active_branch_name' => $branch->name,
                'active_vendor_id' => $vendor->id,
                'active_vendor_name' => $vendor->name,
                'active_role' => 'owner',
            ])
            ->put('/setting/store', [
                'vendor_name' => 'Toko Pusat Updated',
                'vendor_email' => 'toko.updated@example.com',
                'vendor_phone' => '0899999999',
                'vendor_address' => 'Alamat Pusat Baru',
                'branch_name' => 'Cabang 1 Updated',
                'branch_phone' => '0877777777',
                'branch_address' => 'Alamat Cabang Baru',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $response->assertSessionHas('active_branch_name', 'Cabang 1 Updated');
        $response->assertSessionHas('active_vendor_name', 'Toko Pusat Updated');

        $this->assertDatabaseHas('vendors', [
            'id' => $vendor->id,
            'name' => 'Toko Pusat Updated',
            'email' => 'toko.updated@example.com',
            'phone' => '0899999999',
            'address' => 'Alamat Pusat Baru',
        ]);

        $this->assertDatabaseHas('branches', [
            'id' => $branch->id,
            'name' => 'Cabang 1 Updated',
            'phone' => '0877777777',
            'address' => 'Alamat Cabang Baru',
        ]);
    }
}
