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
        Category::firstOrCreate(['name' => 'منزل']); // House
        Category::firstOrCreate(['name' => 'شقة']); // Apartment
        Category::firstOrCreate(['name' => 'شاليه']); // Chalet
        Category::firstOrCreate(['name' => 'أرض']); // Land
        Category::firstOrCreate(['name' => 'مسبح']); // Pool
        Category::firstOrCreate(['name' => 'فيلا']); // Villa
        Category::firstOrCreate(['name' => 'مكتب']); // Office
        Category::firstOrCreate(['name' => 'محل تجاري']); // Commercial Shop
    }
}
