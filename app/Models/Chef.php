<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chef extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'fullname',
        'username',
        'experience',
        'credentials_path',
    ];
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
