<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Invitation extends Model
{
    use HasFactory;

    protected $table = 'colocation_invitations'; 
    protected $fillable = [
        'colocation_id',
        'email',
        'token',
        'used', 
    ];


    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }
}
