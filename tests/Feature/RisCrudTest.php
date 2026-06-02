<?php

namespace Tests\Feature;

use App\Models\ApprovalMatrixStep;
use App\Models\Division;
use App\Models\Item;
use App\Models\RisHeader;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RisCrudTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(string $role = null): User
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => $role ? "{$role}@example.com" : 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        if ($role) {
            Role::create(['name' => $role]);
            $user->assignRole($role);
        }

        return $user;
    }

    private function createDivision(): Division
    {
        return Division::create(['name' => 'Test Division', 'code' => 'DIV-001']);
    }

    private function createItem(): Item
    {
        return Item::create([
            'stock_no' => 'STK-001',
            'item_code' => 'ITEM-001',
            'description' => 'Test Item',
            'unit' => 'pcs',
            'category_id' => null,
            'quantity_on_hand' => 100,
            'reorder_level' => 10,
            'status' => 'active',
        ]);
    }

    private function risPayload(int $divisionId, int $itemId): array
    {
        return [
            'entity_name' => 'Test Entity',
            'fund_cluster' => '100',
            'division_id' => $divisionId,
            'office' => 'Main Office',
            'responsibility_center_code' => 'RCC-01',
            'purpose' => 'This is a test requisition.',
            'details' => [
                [
                    'item_id' => $itemId,
                    'qty_requested' => 1,
                    'remarks' => 'Test item request',
                ],
            ],
        ];
    }

    public function test_ris_can_be_created(): void
    {
        $user = $this->createUser();
        Sanctum::actingAs($user);

        $division = $this->createDivision();
        $item = $this->createItem();

        $response = $this->postJson('/api/ris', $this->risPayload($division->id, $item->id));

        $response->assertOk()
            ->assertJsonPath('data.status', 'draft')
            ->assertJsonPath('data.entity_name', 'Test Entity')
            ->assertJsonPath('data.details.0.qty_requested', '1.00');

        $this->assertDatabaseHas('ris_headers', [
            'entity_name' => 'Test Entity',
            'office' => 'Main Office',
            'status' => 'draft',
        ]);

        $this->assertDatabaseHas('ris_details', [
            'stock_no' => 'STK-001',
            'qty_requested' => '1.00',
        ]);
    }

    public function test_ris_can_be_listed(): void
    {
        $user = $this->createUser();
        Sanctum::actingAs($user);

        $division = $this->createDivision();
        $item = $this->createItem();

        $this->postJson('/api/ris', $this->risPayload($division->id, $item->id));
        $this->postJson('/api/ris', $this->risPayload($division->id, $item->id));

        $response = $this->getJson('/api/ris');

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_ris_can_be_submitted(): void
    {
        $user = $this->createUser();
        Sanctum::actingAs($user);

        $division = $this->createDivision();
        $item = $this->createItem();
        $risResponse = $this->postJson('/api/ris', $this->risPayload($division->id, $item->id));

        $risId = $risResponse->json('data.id');

        $response = $this->postJson("/api/ris/{$risId}/submit");

        $response->assertOk()
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('ris_headers', ['id' => $risId, 'status' => 'pending']);
    }

    public function test_ris_can_be_approved(): void
    {
        $user = $this->createUser('admin');
        Sanctum::actingAs($user);

        ApprovalMatrixStep::create([
            'module' => 'ris',
            'level' => 1,
            'role_name' => 'admin',
            'action_label' => 'Approve',
            'is_final' => true,
            'is_active' => true,
        ]);

        $division = $this->createDivision();
        $item = $this->createItem();
        $risResponse = $this->postJson('/api/ris', $this->risPayload($division->id, $item->id));
        $risId = $risResponse->json('data.id');

        $this->postJson("/api/ris/{$risId}/submit");
        $response = $this->postJson("/api/ris/{$risId}/approve", ['remarks' => 'Approved by admin']);

        $response->assertOk()
            ->assertJsonPath('data.status', 'approved')
            ->assertJsonPath('data.current_approval_level', 1);

        $this->assertDatabaseHas('ris_headers', [
            'id' => $risId,
            'status' => 'approved',
            'approved_by' => $user->id,
        ]);
    }

    public function test_ris_can_be_issued(): void
    {
        $user = $this->createUser('admin');
        Sanctum::actingAs($user);

        ApprovalMatrixStep::create([
            'module' => 'ris',
            'level' => 1,
            'role_name' => 'admin',
            'action_label' => 'Approve',
            'is_final' => true,
            'is_active' => true,
        ]);

        $division = $this->createDivision();
        $item = $this->createItem();
        $risResponse = $this->postJson('/api/ris', $this->risPayload($division->id, $item->id));
        $risId = $risResponse->json('data.id');

        $this->postJson("/api/ris/{$risId}/submit");
        $this->postJson("/api/ris/{$risId}/approve", ['remarks' => 'Approved by admin']);

        $detailId = $risResponse->json('data.details.0.id');
        $response = $this->postJson("/api/ris/{$risId}/issue", [
            'details' => [
                [
                    'id' => $detailId,
                    'qty_issued' => 5,
                    'remarks' => 'Issue test quantity',
                ],
            ],
        ]);

        $response->assertOk()
            ->assertJsonPath('data.status', 'issued')
            ->assertJsonPath('data.details.0.qty_issued', '5.00');

        $this->assertDatabaseHas('ris_details', [
            'id' => $detailId,
            'qty_issued' => '5.00',
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'quantity_on_hand' => '95.00',
        ]);
    }
}
