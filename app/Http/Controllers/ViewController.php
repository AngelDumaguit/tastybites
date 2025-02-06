<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Inertia\Inertia;
use App\Models\Account;
use App\Models\Chef;
use App\Models\User;
use App\Models\Recipe;
use App\Models\React;
use App\Models\Purchase;
use App\Notifications\PurchaseRequestNotification;
use App\Notifications\PurchaseStatusNotification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ViewController extends Controller
{
    public function login(){
        return Inertia::render('Auth/Login');
    }
    public function register(){
        return Inertia::render('Auth/Register');
    }

    public function admindashboard(){
        return Inertia::render('Admindashboard/AdminDashboard');
    }

    public function adminMain()
    {
        $users = User::all();
        $accounts = Account::all();
        $chefs = Account::where('role', 'Chef')->with('chef')->get();
        $recipes = Recipe::with([
            'account.chef',
            'reacts' => function ($query) {
                $query->where('reaction', 'like');
            }
        ])->withCount(['reacts' => function ($query) {
            $query->where('reaction', 'like');
        }])->get();

        // Group recipes by week for the current month
        $weeklyRecipes = Recipe::select(
            DB::raw('WEEK(created_at, 1) as week_number'), // Group by ISO week number
            DB::raw('COUNT(*) as recipe_count')
        )
        ->whereMonth('created_at', Carbon::now()->month) // Current month
        ->groupBy('week_number')
        ->orderBy('week_number')
        ->get();

        $reacts = React::all();
        $currentUserId = Auth::id();

        return Inertia::render('Admindashboard/AdminMain', [
            'users' => $users,
            'accounts' => $accounts,
            'chefs' => $chefs,
            'recipes' => $recipes,
            'weeklyRecipes' => $weeklyRecipes,
            'reacts' => $reacts,
            'currentUserId' => $currentUserId,
        ]);
    }
    public function userdashboard(){

        $recipes = Recipe::withCount(['reacts' => function ($query) {
            $query->where('reaction', 'like');
        }])->get();
        $reacts = React::all();
        $currentUserId = Auth::id();

        $notifications = Auth::user()->notifications()->get();

        $purchases = Purchase::where('user_id', $currentUserId)->get();

        $user = User::with('account') // Assuming the relationship is set up
                ->where('account_id', $currentUserId)
                ->first();
        
        

        return Inertia::render('Userdashboard/UserDashboard',[
            'recipes' => $recipes,
            'reacts' => $reacts,
            'currentUserId' => $currentUserId,
            'notifications' => $notifications,
            'purchases' => $purchases,
            'user' => $user,
        ]);
    }
    public function chefdashboard()
    {
        $recipes = Recipe::withCount(['reacts' => function ($query) {
            $query->where('reaction', 'like');
        }])->get();
        $reacts = React::all();
        $currentUserId = Auth::id();

        $chef = Chef::with('account')->where('account_id', $currentUserId)->first();

        $purchases = Purchase::where('user_id', $currentUserId)->get();

        // Fetch notifications associated with the logged-in user
        $notifications = Auth::user()->notifications()->get();

        return Inertia::render('Chefdashboard/ChefDashboard', [
            'recipes' => $recipes,
            'reacts' => $reacts,
            'currentUserId' => $currentUserId,
            'notifications' => $notifications,
            'purchases' => $purchases,
            'chef' => $chef,
        ]);
    }

}
