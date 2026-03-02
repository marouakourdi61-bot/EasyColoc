<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Colocation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'user_id'];

    

 public function users() :BelongsToMany{
       return $this->belongsToMany(User::class)
        ->withPivot('role','left_at')
        ->withTimestamps()
        ; 
   }

    public function expenses() {
    return $this->hasMany(Expense::class);
}

    public function invitations()
    {
        return $this->hasMany(ColocationInvitation::class);
    }
}