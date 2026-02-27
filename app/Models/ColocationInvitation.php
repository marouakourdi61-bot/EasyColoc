<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ColocationInvitation extends Model
{
    use HasFactory;

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
   