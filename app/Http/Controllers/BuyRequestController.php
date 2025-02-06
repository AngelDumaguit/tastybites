<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Purchase;

use App\Notifications\PurchaseRequestNotification;
use App\Notifications\PurchaseStatusNotification;
use Illuminate\Notifications\DatabaseNotification;

class BuyRequestController extends Controller
{
    public function buyRequest(Request $request, $recipeId)
    {
        $request->validate([
            'receipt' => 'required|image|max:22048', 
        ]);

        $recipe = Recipe::findOrFail($recipeId);

        $existingPurchase = Purchase::where('recipe_id', $recipe->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingPurchase) {
            return response()->json([
                'error' => 'You have already requested to purchase this recipe.',
            ], 400);
        }

        $receiptPath = $request->file('receipt')->store('receipts', 'public');

        $purchase = Purchase::create([
            'recipe_id' => $recipe->id,
            'user_id' => auth()->id(),
            'status' => 'pending',
            'receipt_path' => $receiptPath,
        ]);

        $recipe->account->notify(new PurchaseRequestNotification($purchase));

        auth()->user()->notify(new PurchaseRequestNotification($purchase));

        return response()->json([
            'message' => 'Purchase request sent successfully!',
            'purchase' => $purchase,
        ]);
    }
    public function updatePurchaseStatus(Request $request)
    {
        $request->validate([
            'purchaseId' => 'required|exists:purchases,id',
            'status' => 'required|in:owned,pending,rejected',
        ]);

        $purchase = Purchase::findOrFail($request->purchaseId);

        // Update the status
        $purchase->status = $request->status;
        $purchase->save();

        // Delete old notifications for this purchase
        $purchase->user->notifications()
            ->where('data->purchase_id', $purchase->id)
            ->delete();

        $purchase->recipe->account->notifications()
            ->where('data->purchase_id', $purchase->id)
            ->delete();

        // Send new notifications
        $purchase->user->notify(new PurchaseStatusNotification($purchase));
        $purchase->recipe->account->notify(new PurchaseStatusNotification($purchase));

        return response()->json(['message' => 'Purchase status updated successfully']);
    }

    public function deleteNotification($id)
    {
        $notification = DatabaseNotification::findOrFail($id);

        $notification->delete();

        return response()->json(['message' => 'Notification deleted successfully.']);
    }

}
