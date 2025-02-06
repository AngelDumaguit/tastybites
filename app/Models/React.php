<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class React extends Model
{
    use HasFactory;

    // Table associated with the model (optional if it's the plural of the model name)
    protected $table = 'reacts'; 

    // Define the fillable properties (columns that can be mass assigned)
    protected $fillable = [
        'recipe_id',
        'account_id',
        'reaction',
    ];

    // Define relationships

    // A react belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A react belongs to a recipe
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipe_id');
    }
    
}
