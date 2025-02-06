<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'fullname',
        'username',
    ];
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
