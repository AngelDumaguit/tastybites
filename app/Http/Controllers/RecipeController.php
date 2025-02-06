<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


use App\Notifications\LikeNotification;
use App\Models\Recipe;
use App\Models\Account;
use App\Models\React;
use Inertia\Inertia;

class RecipeController extends Controller
{
    public function addRecipe(Request $request)
    {
        $request->validate([
            'recipeName' => 'required|string|max:255',
            'cuisineType' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|array',
            'procedure' => 'required|array', 
            'videoLink' => 'nullable|string',
            'price' => 'nullable|integer|min:0',
            'gcashNumber' => 'nullable|string|max:255',
            'imagePath' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400',
        ]);

        $imagePath = null;
        if ($request->hasFile('imagePath')) {
            $file = $request->file('imagePath');
            $imagePath = $file->store('recipes/images', 'public');
        }

        $recipe = Recipe::create([
            'account_id' => auth()->id(),
            'recipeName' => $request->recipeName,
            'cuisineType' => $request->cuisineType,
            'description' => $request->description,
            'ingredients' => json_encode($request->ingredients), 
            'procedure' => json_encode($request->procedure),   
            'videoLink' => $request->videoLink,
            'price' => $request->price,
            'gcashNumber' => $request->gcashNumber,
            'imagePath' => $imagePath,
        ]);

        return response()->json(['message' => 'Recipe created successfully', 'recipe' => $recipe]);
    }
    public function updateRecipe(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);

        $data = $request->validate([
            'recipeName' => 'required|string|max:255',
            'cuisineType' => 'required|string|max:255',
            'ingredients' => 'required|array',
            'procedure' => 'required|array',
            'videoLink' => 'nullable|string|url',
            'imagePath' => 'nullable|file|image|max:102400',
        ]);

        if ($request->hasFile('imagePath')) {
            $filePath = $request->file('imagePath')->store('images', 'public');
            $data['imagePath'] = $filePath;
        } else {
            $data['imagePath'] = $recipe->imagePath;
        }

        $recipe->update($data);

        return response()->json(['message' => 'Recipe updated successfully']);
    }

    public function deleteRecipe($id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->delete();
        return response()->json(['message' => 'Recipe deleted successfully']);
    }

    public function toggleReaction(Request $request, Recipe $recipe)
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $reaction = React::firstOrNew([
            'recipe_id' => $recipe->id,
            'account_id' => $userId,
        ]);

        $reaction->reaction = ($reaction->exists && $reaction->reaction === 'like') ? 'dislike' : 'like';
        $reaction->save();

        $likeCount = $recipe->reacts()->where('reaction', 'like')->count();
        $dislikeCount = $recipe->reacts()->where('reaction', 'dislike')->count();

        // Notify the recipe owner only for likes
        if ($reaction->reaction === 'like' && $recipe->account_id !== $userId) {
            $recipeOwner = $recipe->account; // Assuming the `account_id` links to the `Account` model
            $recipeOwner->notify(new LikeNotification($recipe, Auth::user()));
        }

        return redirect()->back()->with([
            'likeCount' => $likeCount,
            'dislikeCount' => $dislikeCount,
        ]);
    }












}
