<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminVendorBranchTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $regularAdmin;
    private Vendor $vendor;
    private Branch $branch;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup authorized and unauthorized users
        $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->regularAdmin = User::factory()->create(['role' => 'admin']);

        // 2. Setup a dummy Vendor and Branch
        $this->vendor = Vendor::create([
            'name' => 'Toko Berkah',
            'slug' => 'toko-berkah-abc1',
            'email' => 'berkah@toko.com',
            'phone' => '08123456789',
            'address' => 'Jl. Berkah No. 12',
            'is_active' => true,
        ]);

        $this->branch = Branch::create([
            'vendor_id' => $this->vendor->id,
            'name' => 'Cabang Surabaya',
            'code' => 'BR-SUB01',
            'phone' => '08123456780',
            'address' => 'Jl. Surabaya No. 10',
            'is_active' => true,
        ]);
    }

    /**
     * Test authorization boundaries (only Superadmin can access branch actions).
     */
    public function test_only_super_admin_can_access_branch_actions(): void
    {
        // 1. Guest redirected to login
        $this->get(route('superadmin.vendors.branches.create', $this->vendor))
            ->assertRedirect('/login');

        // 2. Regular Admin gets redirected/forbidden
        $this->actingAs($this->regularAdmin)
            ->get(route('superadmin.vendors.branches.create', $this->vendor))
            ->assertStatus(302);

        // 3. Super Admin gets OK
        $this->actingAs($this->superAdmin)
            ->get(route('superadmin.vendors.branches.create', $this->vendor))
            ->assertStatus(200);
    }

    /**
     * Test Superadmin can successfully create a new vendor branch.
     */
    public function test_super_admin_can_successfully_create_vendor_branch(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.vendors.branches.store', $this->vendor), [
                'name' => 'Cabang Malang',
                'code' => 'BR-MLG01',
                'phone' => '08123456781',
                'address' => 'Jl. Malang No. 5',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('superadmin.vendors.show', $this->vendor));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('branches', [
            'vendor_id' => $this->vendor->id,
            'name' => 'Cabang Malang',
            'code' => 'BR-MLG01',
            'phone' => '08123456781',
            'address' => 'Jl. Malang No. 5',
            'is_active' => true,
        ]);
    }

    /**
     * Test unique code validation (globally unique).
     */
    public function test_branch_code_must_be_globally_unique(): void
    {
        // 1. Attempt to create another branch with same code 'BR-SUB01' on same vendor
        $response = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.vendors.branches.store', $this->vendor), [
                'name' => 'Cabang Surabaya Kedua',
                'code' => 'BR-SUB01', // Already exists!
                'phone' => '08123456782',
                'address' => 'Jl. Surabaya No. 12',
                'is_active' => '1',
            ]);

        $response->assertSessionHasErrors(['code']);

        // 2. Attempt to create another branch with same code 'BR-SUB01' on DIFFERENT vendor
        $otherVendor = Vendor::create([
            'name' => 'Toko Sebelah',
            'slug' => 'toko-sebelah-xyz2',
            'email' => 'sebelah@toko.com',
            'is_active' => true,
        ]);

        $response2 = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.vendors.branches.store', $otherVendor), [
                'name' => 'Cabang Surabaya Toko Sebelah',
                'code' => 'BR-SUB01', // Already exists globally!
                'phone' => '08123456783',
                'address' => 'Jl. Surabaya No. 99',
                'is_active' => '1',
            ]);

        $response2->assertSessionHasErrors(['code']);
    }

    /**
     * Test Superadmin can successfully edit and update a branch.
     */
    public function test_super_admin_can_successfully_update_vendor_branch(): void
    {
        // 1. Verify edit page loads
        $this->actingAs($this->superAdmin)
            ->get(route('superadmin.vendors.branches.edit', [$this->vendor, $this->branch]))
            ->assertStatus(200);

        // 2. Perform update
        $response = $this->actingAs($this->superAdmin)
            ->put(route('superadmin.vendors.branches.update', [$this->vendor, $this->branch]), [
                'name' => 'Cabang Surabaya Update',
                'code' => 'BR-SUB01', // Same code, should be ignored by unique validation
                'phone' => '08999999999',
                'address' => 'Jl. Surabaya Baru No. 100',
                'is_active' => '0',
            ]);

        $response->assertRedirect(route('superadmin.vendors.show', $this->vendor));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('branches', [
            'id' => $this->branch->id,
            'name' => 'Cabang Surabaya Update',
            'phone' => '08999999999',
            'address' => 'Jl. Surabaya Baru No. 100',
            'is_active' => false,
        ]);
    }

    /**
     * Test Superadmin can safely delete a branch.
     */
    public function test_super_admin_can_safely_delete_vendor_branch(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->delete(route('superadmin.vendors.branches.destroy', [$this->vendor, $this->branch]));

        $response->assertRedirect(route('superadmin.vendors.show', $this->vendor));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('branches', [
            'id' => $this->branch->id,
        ]);
    }

    /**
     * Test that deletion is blocked when there are active registered staffs.
     */
    public function test_cannot_delete_branch_with_active_staff_assigned(): void
    {
        // Create a user and link to this branch
        $user = User::factory()->create(['role' => 'user']);
        $this->vendor->users()->attach($user->id, [
            'branch_id' => $this->branch->id,
            'role' => 'cashier',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->delete(route('superadmin.vendors.branches.destroy', [$this->vendor, $this->branch]));

        $response->assertRedirect(route('superadmin.vendors.show', $this->vendor));
        $response->assertSessionHas('error'); // Error message instead of success

        // Database should still contain this branch
        $this->assertDatabaseHas('branches', [
            'id' => $this->branch->id,
        ]);
    }
}
