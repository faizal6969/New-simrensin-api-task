<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Models\API\RecipeTracker;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecipeTrackerRequest;

class RecipeTrackerController extends Controller
{
    //List
    public function list(Request $request) {
        try {
            $data = RecipeTracker::list($request->all());

            if($data) {
                $array = [
                    'status' => true,
                    'total_count' => $data->count(),
                    'message' => 'Recipe Tracker Listing',
                    'data' => $data,
                ];
                return response()->json($array, 200);

            }else{
                $array = [
                    'status' => false,
                    'message' => 'No recipes found matching the criteria',
                    
                ];
                return response()->json($array, 404);
            }
           
            
        } catch (Exception $e) {
            $array = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
            return response()->json($array, 500);
        }
    }


    //Store Data
    public function save(RecipeTrackerRequest $request) {
        try {
           //$data = RecipeTracker::saveBasicData($request->all());
           $data = RecipeTracker::saveData($request->validated());
           if($data) {
            $array = [
                'status' => true,
                'message' => 'Recipe Tracker Added Successfully.',
                'data' => $data
            ];
            return response()->json($array, 200);
            }else{
                
                $array = [
                    'status' => false,
                    'message' => 'Recipe Tracker failed to add'
                ];
                return response()->json($array, 200);
            }
            
        } catch (\Exception $e) {

            Log::error('Error: ' . $e->getMessage());
            $array = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
            return response()->json($array, 500);
            
        }
    }

    //Show
    public function show($id)
    {
        $recipe = RecipeTracker::find($id);

        if (!$recipe) {
            return response()->json([
                'success' => false,
                'message' => 'Recipe not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $recipe
        ], 200);
    }

    public function edit($id) {
        try {

          $data = RecipeTracker::find($id);
          if($data) {
            $array = [
                'status' => true,
                'message' => 'Edit data',
                'data' => $data,
            ]; 
            return response()->json($array, 200);
          }else{
            $array = [
                'status' => false,
                'message' => 'Edit data not found',
                
            ]; 
            return response()->json($array, 404);
          }  
            
        } catch (\Exception $e) {
            $array = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
            return response()->json($array, 500);
        }
    }


    //Update
    public function update(Request $request) {
        try {
            $data = RecipeTracker::updateData($request->all());
           if($data) {
            $array = [
                'status' => true,
                'message' => 'Recipe Tracker Updated Successfully.',
                'data' => $data
            ];
            return response()->json($array, 200);
            }else{
                
                $array = [
                    'status' => false,
                    'message' => 'Recipe Tracker failed to add'
                ];
                return response()->json($array, 404);
            }
            
        } catch (\Exception $e) {
            throw $e;
        }
    }


    //Delete
    public function delete($id) {
        try {
            $data = RecipeTracker::deleteRecipeTracker($id);
            if($data) {
                $array = [
                    'status' => true,
                    'message' => 'Recipe Tracker Deleted Successfully..!',
                   
                ];
                return response()->json($array, 200);
            }else{
                $array = [
                    'status' => false,
                    'message' => 'Recipe Tracker Failed To Deleted..!',
                    
                ];
                return response()->json($array, 404);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    

    public function filterByDifficulty($level)
    {
        try {
        
            $validLevels = ['easy', 'medium', 'hard'];
            $level = strtolower(trim($level));

            if (!in_array($level, $validLevels)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid difficulty level. Please use easy, medium, or hard.',
                ], 400);
            }

            
            $recipes = RecipeTracker::where('difficulty', $level)->get();

            if ($recipes->isNotEmpty()) {
                return response()->json([
                    'status' => true,
                    'total_count' => $recipes->count(),
                    'message' => 'Recipe Tracker Listing',
                    'data' => $recipes,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'No recipes found with difficulty: ' . ucfirst($level),
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}
