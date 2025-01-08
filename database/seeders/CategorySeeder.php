<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Top-level categories (depth 0)
            [
                'name' => 'Electronics',
                'department_id' => 1, // assuming department_id 1 is for Electronics
                'parent_id' => null,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fashion',
                'department_id' => 2,
                'parent_id' => null,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Subcategories of Electronics (depth 1)
            [
                'name' => 'Computers',
                'department_id' => 1,
                'parent_id' => 1, // parent is Electronics
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Smartphones',
                'department_id' => 1,
                'parent_id' => 1, // parent is Electronics
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Subcategories of Computers (depth 2)
            [
                'name' => 'Laptops',
                'department_id' => 1,
                'parent_id' => 3, // parent is Computers
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desktops',
                'department_id' => 1,
                'parent_id' => 3, // parent is Computers
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Subcategories of Smartphones (depth 2)
            [
                'name' => 'Android',
                'department_id' => 1,
                'parent_id' => 4, // parent is Smartphones
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'IPhones',
                'department_id' => 1,
                'parent_id' => 4, // parent is Smartphones
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Category::insert($categories);
    }
}
