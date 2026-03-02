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

        $colocations = Colocation::whereHas('members', function($query) use ($userId) {
            $query->where('users.id', $userId);
        })
        ->distinct()
        ->select('colocations.*')
        ->withCount('members')
        ->orderByDesc('colocations.created_at')
        ->get();

        return view('dashboard', compact('colocations'));
    }

   }