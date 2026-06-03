<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class InventoryCrudTest extends TestCase
{
    use RefreshDatabase;

    private function createSupplyOfficer(): User
    {
        Permission::create(['name' => 'manage inventory', 'guard_name' => 'web']);
        $role = Role::create(['name' => 'Supply Officer', 'guard_name' => 'web']);
        $role->givePermissionTo('manage inventory');

        $user = User::create([
            'name' => 'Supply Officer',
            'email' => 'supply@example.com',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole($role);

        return $user;
    }

    public function test_supply_officer_can_create_item(): void
    {
        $user = $this->createSupplyOfficer();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/inventory', [
            'stock_no' => 'STK-CREATE',
            'item_code' => 'CODE-001',
            'description' => 'New item for inventory',
            'unit' => 'pcs',
            'quantity_on_hand' => 15,
            'reorder_level' => 5,
            'status' => 'active',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.stock_no', 'STK-CREATE')
            ->assertJsonPath('data.item_code', 'CODE-001');

        $this->assertDatabaseHas('items', [
            'stock_no' => 'STK-CREATE',
            'item_code' => 'CODE-001',
        ]);
    }

    public function test_supply_officer_can_update_item(): void
    {
        $user = $this->createSupplyOfficer();
        Sanctum::actingAs($user);

        $item = Item::create([
            'stock_no' => 'STK-UPD',
            'item_code' => 'CODE-002',
            'description' => 'Original item',
            'unit' => 'box',
            'quantity_on_hand' => 20,
            'reorder_level' => 10,
            'status' => 'active',
        ]);

        $response = $this->putJson("/api/inventory/{$item->id}", [
            'stock_no' => 'STK-UPD',
            'item_code' => 'CODE-002-EDIT',
            'description' => 'Updated item',
            'unit' => 'box',
            'quantity_on_hand' => 18,
            'reorder_level' => 8,
            'status' => 'low_stock',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.item_code', 'CODE-002-EDIT')
            ->assertJsonPath('data.status', 'low_stock');

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'item_code' => 'CODE-002-EDIT',
            'status' => 'low_stock',
        ]);
    }

    public function test_supply_officer_can_delete_item(): void
    {
        $user = $this->createSupplyOfficer();
        Sanctum::actingAs($user);

        $item = Item::create([
            'stock_no' => 'STK-DEL',
            'item_code' => 'CODE-003',
            'description' => 'Delete me',
            'unit' => 'pack',
            'quantity_on_hand' => 5,
            'reorder_level' => 2,
            'status' => 'active',
        ]);

        $response = $this->deleteJson("/api/inventory/{$item->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }
}
