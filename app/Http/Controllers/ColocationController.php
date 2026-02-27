<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\ColocationInvitation;


class ColocationController extends Controller
{
    public function index()
{
    $userId = auth()->id();

    
    $userColocation = Colocation::where('user_id', $userId) 
        ->orWhereHas('members', function($query) use ($userId) {
            $query->where('users.id', $userId);
        })
        ->with(['members', 'owner'])  
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


    //show
    public function show($id)
    {
    $colocation = Colocation::findOrFail($id);
    return view('colocations.show', compact('colocation'));
    }

   public function invite(Request $request, Colocation $colocation)
{
dd(1);
    $request->validate([
        'email' => 'required|email',
    ]);

    $token = Str::random(32);

    $invitation = ColocationInvitation::create([
        'colocation_id' => $colocationId,
        'email' => $request->email,
        'token' => $token,
    ]);

    Mail::to($request->email)
        ->send(new \App\Mail\ColocationInvitationMail($invitation));

    return back()->with('success', 'Invitation envoyée avec succès !');
}
}