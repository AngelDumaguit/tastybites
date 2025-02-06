<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\User;
use App\Models\Chef;
class AdminController extends Controller
{
    public function blockAccount($id, Request $request)
    {
        \Log::info("Blocking account with ID: {$id}");
        \Log::info("Requested status: " . $request->input('status'));

        $account = Account::findOrFail($id);
        $action = $request->input('status');

        // Validate the action
        if (!in_array($action, ['blocked', 'active'])) {
            return redirect()->back()->with('error', 'Invalid action');
        }

        // Update account status
        $account->status = $action;
        $account->save();

        return redirect()->back()->with('message', 'Account status updated successfully');
    }
    public function deleteAccount($id)
    {
        try {
            // Find the account
            $account = Account::findOrFail($id);
            
            // Find the associated user (based on foreign key in the User model)
            $user = $account->user; // This assumes the relationship is defined in the Account model

            // Check if the user exists
            if ($user) {
                // Delete the user
                $user->delete();
            }

            // Delete the account
            $account->delete();

            return redirect()->back()->with('message', 'Account and user deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting account and user');
        }
    }
    public function acceptChef($id)
    {
        $account = Account::findOrFail($id);
        $account->status = 'active';
        $account->save();

        return redirect()->back()->with('success', 'Chef status updated to active.');
    }

    public function deleteChef($id)
    {
        $chef = Chef::where('account_id', $id)->firstOrFail();
        $chef->delete();

        $account = Account::findOrFail($id);
        $account->delete();

        return redirect()->back()->with('success', 'Chef and account deleted successfully.');
    }



}
