<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendorA = \App\Models\Vendor::where('slug', 'vendor-a')->first();
        $vendorB = \App\Models\Vendor::where('slug', 'vendor-b')->first();

        \App\Models\Branch::updateOrCreate(
            ['code' => 'BR-A1'],
            [
                'vendor_id' => $vendorA->id,
                'name' => 'Cabang A1',
                'address' => 'Alamat A1',
            ]
        );

        \App\Models\Branch::updateOrCreate(
            ['code' => 'BR-A2'],
            [
                'vendor_id' => $vendorA->id,
                'name' => 'Cabang A2',
                'address' => 'Alamat A2',
            ]
        );

        \App\Models\Branch::updateOrCreate(
            ['code' => 'BR-B1'],
            [
                'vendor_id' => $vendorB->id,
                'name' => 'Cabang B1',
                'address' => 'Alamat B1',
            ]
        );
    }
}
