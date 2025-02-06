<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Account extends Authenticatable
{
    use Notifiable;

    protected $table = 'accounts';

    protected $fillable = ['email', 'password', 'role', 'status'];

    protected $hidden = ['password', 'remember_token'];

    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function chef()
    {
        return $this->hasOne(Chef::class, 'account_id');
    }
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
