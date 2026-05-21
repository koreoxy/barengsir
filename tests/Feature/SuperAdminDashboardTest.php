<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test super admin dashboard, reports, and settings pages render successfully for authorized users.
     */
    public function test_super_admin_pages_accessible_only_to_super_admin(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $regularAdmin = User::factory()->create(['role' => 'admin']);

        // 1. Verify Guest is redirected to login
        $this->get('/superadmin/dashboard')->assertRedirect('/login');
        $this->get('/superadmin/reports')->assertRedirect('/login');
        $this->get('/superadmin/settings')->assertRedirect('/login');

        // 2. Verify Regular Admin gets redirected (302)
        $this->actingAs($regularAdmin)->get('/superadmin/dashboard')->assertStatus(302);
        $this->actingAs($regularAdmin)->get('/superadmin/reports')->assertStatus(302);
        $this->actingAs($regularAdmin)->get('/superadmin/settings')->assertStatus(302);

        // 3. Verify Super Admin gets successful response (200 OK)
        $this->actingAs($superAdmin)->get('/superadmin/dashboard')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/superadmin/reports')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/superadmin/settings')->assertStatus(200);
    }

    /**
     * Test that system settings can be updated successfully by a super admin.
     */
    public function test_super_admin_can_update_global_system_settings(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($superAdmin)
            ->post('/superadmin/settings', [
                'system_name' => 'MegaPOS Platinum',
                'default_max_branches' => 25,
                'vendor_registration_status' => 'closed',
            ]);

        $response->assertRedirect('/superadmin/settings');
        $response->assertSessionHas('success');

        // Assert database updated successfully
        $this->assertDatabaseHas('settings', [
            'key' => 'system_name',
            'value' => 'MegaPOS Platinum',
            'branch_id' => null,
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'default_max_branches',
            'value' => '25',
            'branch_id' => null,
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'vendor_registration_status',
            'value' => 'closed',
            'branch_id' => null,
        ]);
    }
}
