<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorUser;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_page_requires_authentication(): void
    {
        $response = $this->get('/report');
        $response->assertRedirect('/login');
    }

    public function test_report_page_renders_successfully_for_authorized_admin(): void
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

        // Create transaction to verify aggregates
        Transaction::create([
            'invoice_number' => 'INV-001',
            'user_id' => $user->id,
            'branch_id' => $branch->id,
            'total_amount' => 150000,
            'paid_amount' => 200000,
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
            ->get('/report');

        $response->assertStatus(200);
        $response->assertSee('Laporan Penjualan');
        $response->assertSee('INV-001');
        $response->assertSee('Rp 150.000');
    }

    public function test_report_filters_by_monthly_and_yearly(): void
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
            ->get('/report?filter_type=monthly&month=' . date('Y-m'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Bulanan');

        $responseYearly = $this
            ->actingAs($user)
            ->withSession([
                'active_branch_id' => $branch->id,
                'active_branch_name' => $branch->name,
                'active_vendor_id' => $vendor->id,
                'active_vendor_name' => $vendor->name,
                'active_role' => 'owner',
            ])
            ->get('/report?filter_type=yearly&year=' . date('Y'));

        $responseYearly->assertStatus(200);
        $responseYearly->assertSee('Laporan Tahunan');
    }
}
