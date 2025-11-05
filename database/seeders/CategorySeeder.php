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
        Category::create([
            'name'=>'Proyector'
        ]);
        Category::create([
            'name'=>'Alargador'
        ]);
        Category::create([
            'name'=>'Salon de Actos'
        ]);
        Category::create([
            'name'=>'HDMI'
        ]);
          Category::create([
            'name'=>'Sala Virtual'
        ]);
          Category::create([
            'name'=>'Notebook'
        ]);
    }
}
