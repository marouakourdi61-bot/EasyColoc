<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

      
        $colocations = Colocation::where('user_id', $userId)
            ->orWhereHas('members', function($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->withCount('members') 
            ->get();

        return view('dashboard', compact('colocations'));
    }

   }