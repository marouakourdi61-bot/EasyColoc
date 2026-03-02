<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use App\Models\Expense;
use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class ColocationController extends Controller
{
    public function index($id = null)
{
    $user = auth()->user();
    $userColocation = null;

    if ($id) {
        $userColocation = Colocation::with(['members', 'owner', 'expenses'])->find($id);
        
        if (!$userColocation || !$userColocation->members->contains($user->id)) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }
    } 
    else {
        if ($user->active_colocation_id) {
            $userColocation = Colocation::with(['members', 'owner', 'expenses'])->find($user->active_colocation_id);
        }
        
        if (!$userColocation) {
            $userColocation = $user->colocations()->with(['members', 'owner', 'expenses'])->first();
        }
    }

    if (!$userColocation) {
        return view('colocation.index', ['userColocation' => null, 'role' => null]);
    }

    $memberInfo = $userColocation->members->find($user->id);
    $role = $memberInfo ? $memberInfo->pivot->role : 'membre';

    return view('colocation.index', compact('userColocation', 'role'));
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
    
    // Définir cette colocation comme active
    Auth::user()->update(['active_colocation_id' => $colocation->id]);

    return redirect()->route('colocation.index')->with('success', 'Colocation créée avec succès!');
}


    //show
    public function show($id)
{

        $userColocation = Colocation::with(['members', 'expenses', 'owner'])->findOrFail($id);
        $user = auth()->user();
        $userInColoc = $userColocation->members->find($user->id);

        if (!$userInColoc) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        $role = $userInColoc->pivot->role;
        $total = $userColocation->expenses->sum('amount'); 
        $memberCount = $userColocation->members->count();
        $share = $memberCount > 0 ? ($total / $memberCount) : 0; 
        $balances = $userColocation->members->map(function($member) use ($userColocation, $share) {
            $paid = $userColocation->expenses->where('user_id', $member->id)->sum('amount');
            return [
                'id'      => $member->id,
                'name'    => $member->name,
                'paid'    => $paid,
                'balance' => $paid - $share, 
                'role'    => $member->pivot->role ?? 'Membre'
            ];
        });

        return view('colocation.index', compact('userColocation', 'role', 'total', 'share', 'balances'))->with('readonly', true);
}
    

   public function invite(Request $request, Colocation $colocation)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    $token = Str::random(32);

    $invitation = Invitation::create([
        'colocation_id' => $colocation->id,
        'email' => $request->email,
        'token' => $token,
    ]);

    Mail::to($request->email)
        ->send(new \App\Mail\InvitationMail($invitation));

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