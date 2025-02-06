<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LikeNotification extends Notification
{
    use Queueable;

    protected $recipe;
    protected $likedBy;

    public function __construct($recipe, $likedBy)
    {
        $this->recipe = $recipe; // The liked recipe
        $this->likedBy = $likedBy; // The user who liked the recipe
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'A new like has been added to your recipe: ' . $this->recipe->recipeName,
            'recipe_id' => $this->recipe->id,
            'recipe_name' => $this->recipe->recipeName,
            'liked_by' => $this->likedBy->name,
            'liked_by_id' => $this->likedBy->id,
        ];
    }
}
