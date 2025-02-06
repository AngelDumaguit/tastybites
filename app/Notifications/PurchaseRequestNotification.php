<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchaseRequestNotification extends Notification
{
    protected $purchase;

    public function __construct($purchase)
    {
        $this->purchase = $purchase; 
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'purchase_id' => $this->purchase->id,
            'message' => 'A new purchase request has been submitted for your recipe: ' . $this->purchase->recipe->recipeName,
            'user_id' => $this->purchase->user_id,
            'status' => $this->purchase->status
        ];
    }
}
