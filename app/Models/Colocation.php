<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Colocation extends Model
{
    protected $fillable = ['name', 'description', 'user_id'];

    public function members()
    {
        return $this->belongsToMany(User::class, 'colocation_user', 'colocation_id', 'user_id')
                    ->withPivot('role') 
                    ->withTimestamps();
    }
}
