<?php

namespace App\Http\Controllers;

use App\Mail\Invit;
use App\Notifications\InvitationNotif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use App\Notifications\InvitationNotification;
use App\Models\Invitation;
use App\Models\Colocation;
use App\Models\ColocationInvitation;

class InvitationController extends Controller
{
    public function send(Request $request, Colocation $colocation)
    {
        if (!$colocation) {
            return back()->with('error', 'Vous n\'avez pas de colocation.');
        }

       
        $alreadyMember = $colocation->users()
            ->wherePivotNull('left_at')
            ->where('email', $request->email)
            ->exists();

        if ($alreadyMember) {
            return back()->with('error', 'Cet utilisateur est déjà membre actif de cette colocation.');
        }

        
        $alreadyInvited = Invitation::where('colocation_id', $colocation->id)
            ->where('email', $request->email)
            ->where('used', 0)
            ->exists();

        if ($alreadyInvited) {
            return back()->with('error', 'Une invitation a déjà été envoyée à cette adresse email.');
        }
        // Create invitation
        $token = Str::random(32);
        $invitation = Invitation::create([
            'colocation_id' => $colocation->id,
            'email' => $request->email,
            'token' => $token,
        ]);

        $link = route('colocation.join', $invitation->token);



        $invitation->load('colocation');


        Mail::to($invitation->email)->send(new Invit($invitation));

        $url = url('/invitations/join/' . $invitation->token);

        return back()->with('success', $url);
    }

    public function join($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();




        return view('invitations.accept', compact('invitation'));
    }


    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrfail();

        if (auth()->user()->email != $invitation->email)
            abort(403);
        $invitation->update([
            "used" => 1,

        ]);

        $colocation = Colocation::findOrfail($invitation->colocation_id);
        $colocation->users()->attach(auth()->id(), ['role' => 'member', 'left_at' => null]);



        return redirect()->route('colocation.show', $colocation)->with('success', 'Bienvenue dans la colocation !');
    }

    public function refuse($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        $invitation->delete();

        return redirect()->route('dashboard')
            ->with('info', 'Invitation refusée.');
    }
}
