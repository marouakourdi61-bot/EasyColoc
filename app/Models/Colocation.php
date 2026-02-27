<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Colocation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'user_id'];

    
    public function owner()
    {
        
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function members()
{
    return $this->belongsToMany(User::class, 'colocation_user')
                ->withPivot('role') 
                ->withTimestamps();
}


    public function users()
    {
        return $this->members();
    }

    public function invitations()
    {
        return $this->hasMany(ColocationInvitation::class);
    }
}