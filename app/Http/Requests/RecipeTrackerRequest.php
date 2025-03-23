<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RecipeTrackerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'ingredients' => 'required|string|max:255',
            'prep_time' => 'required|integer',
            'cook_time' => 'required|integer',
            'difficulty' => 'required|string|max:50',
            'description' => 'required|string|max:255',
        ];
    }
    
    public function messages()
    {
        return [
            'name.required' => 'The name is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name must not be greater than 255 characters.',
            'ingredients.required' => 'The ingredients are required.',
            'ingredients.string' => 'The ingredients must be a string.',
            'ingredients.max' => 'The ingredients must not be greater than 255 characters.',
            'prep_time.integer' => 'The prep time must be an integer.',
            'cook_time.integer' => 'The cook time must be an integer.',
            'difficulty.required' => 'The difficulty is required.',
            'difficulty.string' => 'The difficulty must be a string.',
            'difficulty.max' => 'The difficulty must not be greater than 50 characters.',
            'description.required' => 'The description is required.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description must not be greater than 255 characters.',
        ];
    }
    
    public function failedValidation(Validator $validator) 
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation Error',
            'errors' => $validator->errors()
        ], 422));
    }
}
