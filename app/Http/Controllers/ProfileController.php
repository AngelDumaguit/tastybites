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

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        // Get the authenticated account
        $account = Auth::user(); // Authenticated 'Account'

        // Find the user linked to this account
        $user = User::where('account_id', $account->id)->firstOrFail();

        // Validate the input
        $data = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        ]);

        // Update the user fields
        $user->update([
            'fullname' => $data['fullname'],
            'username' => $data['username'],
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
    public function updateChefProfile(Request $request)
    {
        $account = Auth::user(); 

        $chef = Chef::where('account_id', $account->id)->firstOrFail();

        // Validate the input
        $data = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $chef->id,
        ]);

        // Update the user fields
        $chef->update([
            'fullname' => $data['fullname'],
            'username' => $data['username'],
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
