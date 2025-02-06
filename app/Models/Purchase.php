<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['recipe_id', 'user_id', 'status', 'receipt_path'];

    // Relationship with the recipe
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function user()
    {
        return $this->belongsTo(Account::class);
    }
    public function notification()
    {
        return $this->hasOne(Notification::class, 'purchase_id');
    }
}
