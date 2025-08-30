<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'userId';
    public $timestamps = false;
    protected $fillable = ['username', 'passwordHash'];
    protected $hidden = ['passwordHash'];
}
