<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Account;
use App\Models\Chef;

class AuthController extends Controller
{
    public function registerPost(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'fullname' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required|string',
            'experience' => 'nullable|required_if:role,chef|integer',
            'credentials' => 'nullable|file|mimes:pdf|max:20480',
            'status' => 'nullable|string',
        ]);

        $status = $request->role === 'User' ? 'active' : 'pending';

        $account = Account::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $status,
        ]);

        if ($request->role === 'User') {
            User::create([
                'account_id' => $account->id,
                'fullname' => $request->fullname,
                'username' => $request->username,
            ]);
        } elseif ($request->role === 'Chef') {
            // Save credentials file
            $credentialsPath = $request->file('credentials')->store('credentials', 'public');
    
            Chef::create([
                'account_id' => $account->id,
                'fullname' => $request->fullname,
                'username' => $request->username,
                'experience' => $request->experience,
                'credentials_path' => $credentialsPath,
            ]);
        }
        return redirect('/login')->with([
            'success' => 'Registration successful. Please log in.',
        ]);
    }



    public function loginPost(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->status !== 'active') {
                return redirect()->route('registerPost')->withErrors([
                    'status' => 'Your account is not active. Please check your email for a confirmation link.',
                ]);
            }

            switch ($user->role) {
                case 'Admin':
                    return redirect()->route('admindashboard');
                case 'Chef':
                    return redirect()->route('chefdashboard');
                case 'User':
                    return redirect()->route('userdashboard');
                default:
                    Auth::logout();
                    return redirect('/login')->withErrors([
                        'role' => 'Invalid role.',
                    ]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function logout(Request $request)
    {
        // Log out the user
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        // Redirect to the login page
        return redirect()->route('login');
    }




}

