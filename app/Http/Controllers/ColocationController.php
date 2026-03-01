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
    public function index($id = null)
    
    {
        $user = auth()->user();
        $userColocation = null;

       
        if ($id) {
            $userColocation = Colocation::with(['members', 'owner', 'expenses.user'])->find($id);
            if (!$userColocation || !$userColocation->members->contains($user->id)) {
                return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
            }
        } else {
            
            $colocId = $user->active_colocation_id;
            if ($colocId) {
                $userColocation = Colocation::with(['members', 'owner', 'expenses.user'])->find($colocId);
            }
            if (!$userColocation) {
                $userColocation = $user->colocations()->with(['members', 'owner', 'expenses.user'])->first();
            }
        }

        
        if (!$userColocation) {
            return view('colocation.index', [
                'userColocation' => null, 
                'role' => null,
                'total' => 0,
                'share' => 0,
                'balance' => collect([])
            ]);
        }

        //  CALCUL
        $total = $userColocation->expenses->sum('amount'); 
        $memberCount = max($userColocation->members->count(), 1); 
        $share = $total / $memberCount; 

        $balance = $userColocation->members->map(function($member) use ($userColocation, $share) {
            $paid = $userColocation->expenses->where('user_id', $member->id)->sum('amount');
            return [
                'user' => $member,
                'paid' => $paid,
                'balance' => $paid - $share, 
            ];
        });

        $memberInfo = $userColocation->members->find($user->id);
        $role = $memberInfo ? $memberInfo->pivot->role : 'membre';

        return view('colocation.index', compact('userColocation', 'role', 'total', 'share', 'balance'));
    }


    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    $colocation = Colocation::create([
        'name' => $request->name,
        'description' => $request->description,
        'user_id' => Auth::id(),
    ]);

    
    $colocation->members()->attach(Auth::id(), ['role' => 'admin']);

    return redirect()->route('colocation.index')->with('success', 'Colocation créée avec succès!');
}


    //show
    public function show($id)
{
    $colocation = Colocation::with(['members'])->findOrFail($id);
    
    $userInColoc = $colocation->members->find(auth()->id());
    
    if (!$userInColoc) {
        return redirect()->route('dashboard')->with('error', 'Vous n\'êtes pas membre.');
    }

    $role = $userInColoc->pivot->role; 

    $userColocation = $colocation;

    return view('colocation.index', compact('userColocation', 'role'));
}
    

   public function invite(Request $request, Colocation $colocation)
{
dd(1);
    $request->validate([
        'email' => 'required|email',
    ]);

    $token = Str::random(32);

    $invitation = ColocationInvitation::create([
        'colocation_id' => $colocation->id,
        'email' => $request->email,
        'token' => $token,
    ]);

    Mail::to($request->email)
        ->send(new \App\Mail\ColocationInvitationMail($invitation));

    return back()->with('success', 'Invitation envoyée avec succès !');
}



    public function select($id)
{
    $colocation = Colocation::findOrFail($id);
    $user = auth()->user();

    if (!$colocation->members->contains($user->id)) {
        return back()->with('error', 'n aller pas a cette colocation!');
    }

    $user->update([
        'active_colocation_id' => $colocation->id
    ]);

    return redirect()->route('colocation.index', ['id' => $colocation->id])
                     ->with('success', 'Bienvenue dans ' . $colocation->name);
}
}