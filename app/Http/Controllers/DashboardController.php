<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
     $colocations = Auth::user()
        ->colocations()
        ->with(['users' => fn($q) => $q->withPivot('role', 'left_at')])
        ->get();
        //  dd($colocations);

        return view('dashboard', compact('colocations'));
    }

   }