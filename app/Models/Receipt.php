<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'recipeName',
        'cuisineType',
        'description',
        'ingredients',
        'procedure',
        'status',
        'freePrem',
        'video',
        'price'
    ];
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
