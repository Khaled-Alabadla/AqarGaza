<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        City::firstOrCreate(['name' => 'غزة']); // Gaza
        City::firstOrCreate(['name' => 'خانيونس']); // Khan Yunis
        City::firstOrCreate(['name' => 'رفح']); // Rafah
        City::firstOrCreate(['name' => 'الوسطى']); // Central Governorate
        City::firstOrCreate(['name' => 'الشمال']); // North Governorate
    }
}
