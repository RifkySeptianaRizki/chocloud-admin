<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['name' => 'Popular']);
        Category::create(['name' => 'Truffle']);
        Category::create(['name' => 'Tiramisu']);
        Category::create(['name' => 'Special']);
    }
}
