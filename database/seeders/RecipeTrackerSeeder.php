<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\API\RecipeTracker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RecipeTrackerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        $json = file_get_contents(database_path('data/recipes.json'));

        
        $recipes = json_decode($json, true);

        
        foreach ($recipes as $recipe) {
            RecipeTracker::create($recipe);
        }
    }
}
