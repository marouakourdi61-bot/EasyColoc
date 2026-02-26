<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use Illuminate\Support\Facades\Auth;

class ColocationController extends Controller
{
    public function index()
    {
        $userColocation = Colocation::where('user_id', Auth::id())
                                 ->with('members') 
                                 ->first();

    return view('colocation.index', compact('userColocation'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Colocation::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('colocation.index')->with('success', 'Colocation créée avec succès!');
    }
}