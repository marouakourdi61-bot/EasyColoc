<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'sender_id',
        'receiver_id',
        'amount',
        'status',
        'paid_at'
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }


    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

   
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}