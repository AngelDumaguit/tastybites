<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\http\Controllers\ViewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\BuyRequestController;
use App\Http\Controllers\ProfileController;

Route::get('/login', [ViewController::class, 'login'])->name('login');
Route::get('/register', [ViewController::class, 'register'])->name('register');

Route::post('/register', [AuthController::class, 'registerPost'])->name('registerPost');
Route::post('/login', [AuthController::class, 'loginPost'])->name('loginPost');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/adminDashboard', [ViewController::class, 'admindashboard'])->name('admindashboard');
    Route::post('/blockAccount/{id}', [AdminController::class, 'blockAccount']);
    Route::post('/deleteAccount/{id}', [AdminController::class, 'deleteAccount']);
    Route::post('/acceptChef/{id}', [AdminController::class, 'acceptChef']);
    Route::post('/deleteChef/{id}', [AdminController::class, 'deleteChef']);
    Route::post('/addRecipe', [RecipeController::class, 'addRecipe'])->name('addRecipe');
    Route::post('/recipes/{id}', [RecipeController::class, 'updateRecipe']);
    Route::delete('/recipes/{id}', [RecipeController::class, 'deleteRecipe']);

    Route::get('/userDashboard', [ViewController::class, 'userdashboard'])->name('userdashboard');
    Route::get('/chefDashboard', [ViewController::class, 'chefdashboard'])->name('chefdashboard');

    Route::post('/recipes/{recipe}/toggle-reaction', [RecipeController::class, 'toggleReaction'])->name('recipe.toggleReaction');

    

});

Route::middleware(['auth'])->group(function () {
    Route::post('/purchases/{recipeId}/buy', [BuyRequestController::class, 'buyRequest'])->name('buyRequest');
    Route::post('/update-purchase-status', [BuyRequestController::class, 'updatePurchaseStatus'])->name('confirmPurchase');
    Route::delete('/delete/notifications/{id}', [BuyRequestController::class, 'deleteNotification'])->name('deleteNotification');

    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/chef/profile/update', [ProfileController::class, 'updateChefProfile'])->name('chef.profile.update');
});


Route::get('/adminMain', [ViewController::class, 'adminMain'])->name('adminMain');
Route::get('/userProfile', [ViewController::class, 'userProfile'])->name('userProfile');

