<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use App\Models\Expense;
use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Settlement;


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
        } else {
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

        // Check active usr
        $hasActiveColocation = Auth::user()
            ->colocations()
            ->wherePivotNull('left_at')
            ->exists();

        if ($hasActiveColocation) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous êtes déjà membre d\'une colocation active.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:30',
            'description' => 'nullable|string|max:100',
        ]);

        $colocation = Colocation::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'user_id' => auth()->id(),
        ]);

        $colocation->users()->attach(auth()->id(), ['role' => 'owner', 'left_at' => null]);

        

        return redirect()->route('colocations.show', $colocation)
            ->with('success', 'Colocation créée avec succès !');
    }


    public function show(Colocation $colocation)
    {
        $colocation->load(['expenses.user']);

        $user = auth()->user();


        $currentUser = $colocation->users->firstWhere('id', $user->id);

        if (!$currentUser) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        $role = $currentUser->pivot->role;
        // read-only 
        $hasLeft = !is_null($currentUser->pivot->left_at);

        // active members 
        $activeMembers = $colocation->users->filter(fn($m) => is_null($m->pivot->left_at));
        $memberCount = $activeMembers->count();
        $total = $colocation->expenses->sum('amount');
        $share = $memberCount > 0 ? ($total / $memberCount) : 0;



        $balances = $colocation->users->map(fn($user) => [
            'user' => $user,
            'paid' => $paid = $colocation->expenses->where('user_id', $user->id)->sum('amount'),
            'share' => $share,
            'balance' => $paid - $share,
        ]);



        
        

        //settlement

        $toReceive = Settlement::whereHas('expense', function($q) use ($colocation) {
            $q->where('colocation_id', $colocation->id);
        })
        ->where('receiver_id', $user->id)
        ->where('status', 'pending')
        ->with('sender')
        ->get();

    
    $toPay = Settlement::whereHas('expense', function($q) use ($colocation) {
            $q->where('colocation_id', $colocation->id);
        })
        ->where('sender_id', $user->id)
        ->where('status', 'pending')
        ->with('receiver')
        ->get();


        return view('colocation.show', compact('colocation', 'role', 'hasLeft', 'total', 'share', 'balances', 'activeMembers','toReceive','toPay'));
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

    public function leave(Colocation $colocation)
    {
        $user = auth()->user();

        $currentUser = $colocation->users()
            ->withPivot('role', 'left_at')
            ->where('user_id', $user->id)
            ->first();

        // must be a member
        if (!$currentUser) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'êtes pas membre de cette colocation.');
        }

        // admins cannot leave
        if ($currentUser->pivot->role === 'admin' || $currentUser->pivot->role === 'owner') {
            return redirect()->route('colocations.show', $colocation)
                ->with('error', 'En tant qu\'admin, vous ne pouvez pas quitter la colocation.');
        }


        if (!is_null($currentUser->pivot->left_at)) {
            return redirect()->route('colocations.show', $colocation)
                ->with('error', 'Vous avez déjà quitté cette colocation.');
        }


        $colocation->users()->updateExistingPivot($user->id, [
            'left_at' => now(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Vous avez quitté la colocation "' . $colocation->name . '".');
    }





    public function payAllBetween(Colocation $colocation, $receiverId)
    {

        $updated = Settlement::whereHas('expense', function ($query) use ($colocation) {
            $query->where('colocation_id', $colocation->id);
        })
            ->where('sender_id', auth()->id())
            ->where('receiver_id', $receiverId)
            ->where('status', 'pending')
            ->update([
                'status' => 'paid',
                'paid_at' => now()
            ]);


        return response()->json([
            'success' => true,
            'message' => 'Règlement effectué',
            'receiver_id' => $receiverId
        ]);
    }


    public function confirmSettlement($settlementId)
{
    $settlement = \App\Models\Settlement::findOrFail($settlementId);

    
    
    if ($settlement->receiver_id !== auth()->id()) {
        return response()->json(['success' => false], 403);
    }

    $settlement->update([
        'status' => 'paid',
        'paid_at' => now(),
    ]);

    return response()->json([
        'success' => true
    ]);
}

}