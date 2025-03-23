<?php

namespace App\Models\API;

use Throwable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecipeTracker extends Model
{
    use HasFactory;
    protected $table = 'recipe_trackers';
    protected $guarded = [];


    public static function recipeListTracker($data) {
        try {
            
            $ingredients = isset($data['ingredients']) 
                ? array_map('strtolower', array_map('trim', explode(',', $data['ingredients'])))
                : [];
            $minTime = isset($data['min_time']) ? (int)$data['min_time'] : 0;
            $maxTime = isset($data['max_time']) ? (int)$data['max_time'] : PHP_INT_MAX;
    
            if ($minTime > $maxTime) {
                [$minTime, $maxTime] = [$maxTime, $minTime];
            }
    
            $recipes = RecipeTracker::all();
    
            $recipes = $recipes->map(function ($recipe) {
                $recipe->total_time = $recipe->prep_time + $recipe->cook_time;
                return $recipe;
            });
    
            //Filter recipes based on time
            $recipes = $recipes->filter(function ($recipe) use ($minTime, $maxTime) {
                return $recipe->prep_time >= $minTime && $recipe->cook_time <= $maxTime;
            });

           
    
            // Filter recipes based on ingredients
            if (!empty($ingredients)) {
                $recipes = $recipes->filter(function ($recipe) use ($ingredients) {
                    $recipeIngredients = array_map('trim', explode(',', strtolower($recipe->ingredients)));
                    return empty(array_diff($ingredients, $recipeIngredients));
                });
            }
            
            return $recipes->values();
    
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    

    public static function saveBasicData($request) {
        try {
            $recipeTracker = new RecipeTracker();
            $recipeTracker->name = $request['name'] ?? '';
            //$recipeTracker->ingredients = $request['ingredients'] ?? '';
            if (is_array($request['ingredients'] ?? '')) {
                $recipeTracker->ingredients = implode(',', $request['ingredients']);
            } else {
                $recipeTracker->ingredients = $request['ingredients'] ?? '';
            }
            $recipeTracker->prep_time = $request['prep_time'] ?? '';
            $recipeTracker->cook_time = $request['cook_time'] ?? '';
            $recipeTracker->difficulty = $request['difficulty'] ?? '';
            $recipeTracker->description = $request['description'] ?? '';
            $recipeTracker->save();
            return $recipeTracker;
            
        } catch (Throwable $th) {
            throw $th;
        }
    }


    //Update
    public static function updateBasicData($request) {
        try {
            
            $id = $request['id'];
            $recipeTracker = RecipeTracker::find($id);
            $recipeTracker->name = $request['name'] ?? '';
            if (is_array($request['ingredients'] ?? '')) {
                $recipeTracker->ingredients = implode(',', $request['ingredients']);
            } else {
                $recipeTracker->ingredients = $request['ingredients'] ?? '';
            }
            $recipeTracker->prep_time = $request['prep_time'] ?? '';
            $recipeTracker->cook_time = $request['cook_time'] ?? '';
            $recipeTracker->difficulty = $request['difficulty'] ?? '';
            $recipeTracker->description = $request['description'] ?? '';
            $recipeTracker->update();
            return $recipeTracker;
            
            
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    //Delete
    public static function deleteRecipeTracker($id) {
        $data = RecipeTracker::find($id);
    
        if (!$data) {
            return false; 
        }
         
        return $data->delete(); 
    }
    


    
}
