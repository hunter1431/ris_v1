<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ApprovalMatrixStep;
use App\Models\Division;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        ApprovalMatrixStep::firstOrCreate(
            ['module' => 'ris', 'level' => 1],
            ['role_name' => 'Division Head', 'action_label' => 'Division Head Approval', 'is_final' => false, 'is_active' => true]
        );

        ApprovalMatrixStep::firstOrCreate(
            ['module' => 'ris', 'level' => 2],
            ['role_name' => 'Supply Officer', 'action_label' => 'Supply Office Approval', 'is_final' => true, 'is_active' => true]
        );

        $division = Division::firstOrCreate(['code' => 'ADMIN'], ['name' => 'Administrative Division']);
        $category = Category::firstOrCreate(['name' => 'Office Supplies']);

        Item::firstOrCreate(['stock_no' => 'PAP001'], ['item_code' => 'PAP-A4', 'description' => 'Bond Paper A4', 'unit' => 'Ream', 'category_id' => $category->id, 'quantity_on_hand' => 500, 'reorder_level' => 100]);
        Item::firstOrCreate(['stock_no' => 'PEN001'], ['item_code' => 'PEN-BLK', 'description' => 'Ballpen Black', 'unit' => 'Piece', 'category_id' => $category->id, 'quantity_on_hand' => 200, 'reorder_level' => 50]);
        Item::firstOrCreate(['stock_no' => 'INK001'], ['item_code' => 'INK-EPS', 'description' => 'Epson Ink', 'unit' => 'Bottle', 'category_id' => $category->id, 'quantity_on_hand' => 50, 'reorder_level' => 10]);

        $admin = User::firstOrCreate(['email' => 'admin@example.com'], ['name' => 'Super Admin', 'password' => Hash::make('password')]);
        $admin->assignRole('Super Admin');
    }
}
