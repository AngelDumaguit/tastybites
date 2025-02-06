<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Purchase;

class PurchaseStatusNotification extends Notification
{
    use Queueable;

    protected $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    public function via($notifiable)
    {
        return ['database'];
    }


    public function toArray(object $notifiable): array
    {
        return [
            'message' => "The status of your purchase for the recipe '{$this->purchase->recipe->name}' has been updated to '{$this->purchase->status}'.",
            'purchase_id' => $this->purchase->id,
            'status' => $this->purchase->status,
        ];
    }
}
