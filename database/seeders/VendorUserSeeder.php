<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendorA = \App\Models\Vendor::where('slug', 'vendor-a')->first();
        $branchA1 = \App\Models\Branch::where('code', 'BR-A1')->first();
        $branchA2 = \App\Models\Branch::where('code', 'BR-A2')->first();

        // Admin Vendor A
        $userOwnerA = \App\Models\User::updateOrCreate(
            ['email' => 'owner-a@example.com'],
            ['name' => 'Owner A', 'password' => \Illuminate\Support\Facades\Hash::make('password')]
        );
        \App\Models\VendorUser::updateOrCreate(
            ['user_id' => $userOwnerA->id, 'vendor_id' => $vendorA->id, 'branch_id' => null],
            ['role' => 'owner']
        );

        // Kasir Cabang A1
        $userCashierA1 = \App\Models\User::updateOrCreate(
            ['email' => 'cashier-a1@example.com'],
            ['name' => 'Kasir A1', 'password' => \Illuminate\Support\Facades\Hash::make('password')]
        );
        \App\Models\VendorUser::updateOrCreate(
            ['user_id' => $userCashierA1->id, 'vendor_id' => $vendorA->id, 'branch_id' => $branchA1->id],
            ['role' => 'cashier']
        );
    }
}
