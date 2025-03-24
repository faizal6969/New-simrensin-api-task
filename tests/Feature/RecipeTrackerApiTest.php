<?php
namespace Tests\Feature;

//use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\API\RecipeTracker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RecipeTrackerApiTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_list_recipes()
    {
        $response = $this->actingAsUser()->get('/api/recipe/list');
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_a_recipe()
    {
        $data = [
            'name' => 'Test Recipe',
            'description' => 'Delicious and easy to make',
            'difficulty' => 'easy',
            'ingredients' => 'eggs, soya',
            'prep_time' => '15',
            'cook_time' => '25'
        ];
        $response = $this->actingAsUser()->post('/api/recipe/save', $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('recipe_trackers', $data);
    }

    /** @test */
    public function it_can_show_a_recipe()
    {
        $recipe = RecipeTracker::first();
                
        $this->assertNotNull($recipe, 'No recipe found in the database. Ensure you have seeded data.');
        // $recipe = RecipeTracker::first() ?? RecipeTracker::create([
        //     'name' => 'Test Recipe',
        //     'description' => 'Delicious and easy to make',
        //     'difficulty' => 'easy',
        //     'ingredients' => 'eggs, soya',
        //     'prep_time' => '15',
        //     'cook_time' => '25'
        // ]);

        $response = $this->actingAsUser()->get("/api/recipe/show/{$recipe->id}");

        $response->assertStatus(200);
    }


    /** @test */
    public function it_can_update_a_recipe()
    {
        $recipe = RecipeTracker::first();
        $this->assertNotNull($recipe, 'No recipe found in the database. Ensure you have seeded data.');

        // Update data
        $updateData = [
            'name' => 'Updated Recipe',
            'description' => 'Updated description',
            'difficulty' => 'medium',
            'ingredients' => 'eggs',
            'prep_time' => 10,
            'cook_time' => 15
        ];

        $response = $this->actingAsUser()->put('/api/recipe/update', array_merge(['id' => $recipe->id], $updateData));

        $response->assertStatus(200);
        $this->assertDatabaseHas('recipe_trackers', $updateData);
    }


    /** @test */
    public function it_can_delete_a_recipe()
    {
        $recipe = RecipeTracker::first();
        $response = $this->actingAsUser()->get("/api/recipe/delete/{$recipe->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('recipe_trackers', ['id' => $recipe->id]);
    }

    /** @test */
    public function it_can_filter_by_difficulty()
    {
        $recipe = RecipeTracker::where('difficulty', 'easy')->first();
        $this->assertNotNull($recipe, 'No recipe found with difficulty "easy". Ensure you have seeded data.');

        $response = $this->actingAsUser()->get('/api/recipe/difficulty/easy');
        $response->assertStatus(200);
    }

    private function actingAsUser()
    {
        $user = \App\Models\User::first();
        $this->assertNotNull($user, 'No user found in the database. Ensure you have seeded data.');
        return $this->actingAs($user);
    }

}
