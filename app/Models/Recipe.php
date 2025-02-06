<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    // Fillable fields for mass assignment
    protected $fillable = [
        'account_id',
        'recipeName',
        'cuisineType',
        'description',
        'ingredients',
        'procedure',
        'status',
        'freePrem',
        'videoLink',
        'imagePath',
        'price',
        'gcashNumber',
    ];

    /**
     * Dynamic accessor for freePrem
     * Determines whether the recipe is Free or Premium based on the price.
     */
    public function getFreePremAttribute()
    {
        return ($this->price < 1 || is_null($this->price)) ? 'Free' : 'Premium';
    }

    /**
     * Boot method to handle automatic setting of freePrem on save.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($recipe) {
            if (is_null($recipe->status)) {
                $recipe->status = 'active';
            }
            $recipe->freePrem = ($recipe->price < 1 || is_null($recipe->price)) ? 'Free' : 'Premium';
        });
    }

    /**
     * Relationship: A recipe belongs to an account.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function getIngredientsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getProcedureAttribute($value)
    {
        return json_decode($value, true);
    }
    public function reacts()
    {
        return $this->hasMany(React::class, 'recipe_id');
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'user_id');
    }
}
