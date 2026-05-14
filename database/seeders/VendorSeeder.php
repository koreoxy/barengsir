<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Vendor::updateOrCreate(
            ['slug' => 'vendor-a'],
            [
                'name' => 'Vendor A',
                'email' => 'vendor-a@example.com',
                'phone' => '08123456789',
                'address' => 'Jl. Vendor A No. 1',
            ]
        );

        \App\Models\Vendor::updateOrCreate(
            ['slug' => 'vendor-b'],
            [
                'name' => 'Vendor B',
                'email' => 'vendor-b@example.com',
                'phone' => '08987654321',
                'address' => 'Jl. Vendor B No. 2',
            ]
        );
    }
}
