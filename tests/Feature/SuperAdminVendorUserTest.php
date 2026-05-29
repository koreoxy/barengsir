<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminVendorUserTest extends TestCase
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

        // 2. Setup a dummy Vendor and Branch (no factories, created via Eloquent)
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
     * Test authentication and authorization boundaries (403 or redirects).
     */
    public function test_only_super_admin_can_access_creation_pages_and_actions(): void
    {
        // 1. Guest redirected to login
        $this->get(route('superadmin.vendors.users.create', $this->vendor))
            ->assertRedirect('/login');

        $this->post(route('superadmin.vendors.users.store', $this->vendor), [])
            ->assertRedirect('/login');

        // 2. Regular Admin redirected / forbidden (role middleware redirects or aborts)
        $this->actingAs($this->regularAdmin)
            ->get(route('superadmin.vendors.users.create', $this->vendor))
            ->assertStatus(302); // Redirect back or out of superadmin route group

        $this->actingAs($this->regularAdmin)
            ->post(route('superadmin.vendors.users.store', $this->vendor), [])
            ->assertStatus(302);

        // 3. Super Admin gets OK
        $this->actingAs($this->superAdmin)
            ->get(route('superadmin.vendors.users.create', $this->vendor))
            ->assertStatus(200);
    }

    /**
     * Test validation requires branch_id for cashier role.
     */
    public function test_cashier_role_requires_branch_id(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.vendors.users.store', $this->vendor), [
                'name' => 'Budi Cashier',
                'email' => 'budi@cashier.com',
                'password' => 'password123',
                'role' => 'cashier',
                'branch_id' => '', // Empty branch
                'is_active' => '1',
            ]);

        $response->assertSessionHasErrors(['branch_id']);
    }

    /**
     * Test validation that the branch_id must belong to the active vendor.
     */
    public function test_branch_must_belong_to_the_current_vendor(): void
    {
        // Create another vendor and their branch
        $otherVendor = Vendor::create([
            'name' => 'Toko Sebelah',
            'slug' => 'toko-sebelah-xyz2',
            'email' => 'sebelah@toko.com',
            'is_active' => true,
        ]);

        $otherBranch = Branch::create([
            'vendor_id' => $otherVendor->id,
            'name' => 'Cabang Sebelah',
            'code' => 'BR-SEB01',
            'is_active' => true,
        ]);

        // Attempt to assign the new cashier of "Toko Berkah" to the branch of "Toko Sebelah"
        $response = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.vendors.users.store', $this->vendor), [
                'name' => 'Randi Cashier',
                'email' => 'randi@cashier.com',
                'password' => 'password123',
                'role' => 'cashier',
                'branch_id' => $otherBranch->id, // Invalid branch for this vendor!
                'is_active' => '1',
            ]);

        $response->assertSessionHasErrors(['branch_id']);
    }

    /**
     * Test that store successfully creates user and attaches it inside pivot.
     */
    public function test_super_admin_can_successfully_create_vendor_user(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.vendors.users.store', $this->vendor), [
                'name' => 'Dewi Cashier',
                'email' => 'dewi@cashier.com',
                'password' => 'password123',
                'role' => 'cashier',
                'branch_id' => $this->branch->id,
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('superadmin.vendors.show', $this->vendor));
        $response->assertSessionHas('success');

        // Check if user is created globally in 'users' table
        $this->assertDatabaseHas('users', [
            'name' => 'Dewi Cashier',
            'email' => 'dewi@cashier.com',
            'role' => 'user', // global role
        ]);

        // Retrieve created user
        $user = User::where('email', 'dewi@cashier.com')->first();

        // Check if pivot link is established in 'vendor_users' table
        $this->assertDatabaseHas('vendor_users', [
            'user_id' => $user->id,
            'vendor_id' => $this->vendor->id,
            'branch_id' => $this->branch->id,
            'role' => 'cashier',
            'is_active' => true,
        ]);
    }

    /**
     * Test that when role is 'owner', the branch_id is automatically ignored and set to null.
     */
    public function test_owner_role_ignores_branch_id_setting_it_to_null(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('superadmin.vendors.users.store', $this->vendor), [
                'name' => 'Hendro Owner',
                'email' => 'hendro@owner.com',
                'password' => 'password123',
                'role' => 'owner',
                'branch_id' => $this->branch->id, // Sent but should be set to null in pivot
                'is_active' => '1',
            ]);

        $user = User::where('email', 'hendro@owner.com')->first();

        $this->assertDatabaseHas('vendor_users', [
            'user_id' => $user->id,
            'vendor_id' => $this->vendor->id,
            'branch_id' => null, // Verified to be null!
            'role' => 'owner',
        ]);
    }

    /**
     * Test Superadmin can safely delete user access and delete global user records.
     */
    public function test_super_admin_can_safely_delete_vendor_user(): void
    {
        // 1. Create a user manually and link to vendor
        $user = User::factory()->create([
            'name' => 'Staf Hapus',
            'email' => 'staf@hapus.com',
            'role' => 'user',
        ]);

        $this->vendor->users()->attach($user->id, [
            'branch_id' => $this->branch->id,
            'role' => 'cashier',
            'is_active' => true,
        ]);

        // 2. Perform deletion action
        $response = $this->actingAs($this->superAdmin)
            ->delete(route('superadmin.vendors.users.destroy', [$this->vendor, $user]));

        $response->assertRedirect(route('superadmin.vendors.show', $this->vendor));
        $response->assertSessionHas('success');

        // 3. Verify record is detached from pivot and completely deleted from global users
        $this->assertDatabaseMissing('vendor_users', [
            'user_id' => $user->id,
            'vendor_id' => $this->vendor->id,
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
