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
    public function send(Request $request , Colocation $colocation)
    {
//   dd($colocation);
        $request->validate([
            'email' => 'required|email'
        ]);
        // $colocation = auth()->user()->colocation;
        
      
        if (!$colocation) {
            return back()->with('error', 'Vous n’avez pas de colocation.');
        }

        // Create invitation
        $token = Str::random(32);
        $invitation = Invitation::create([
            'colocation_id' => $colocation->id,
            'email' => $request->email,
            'token' => $token,
        ]);

        $link = route('colocation.join', $invitation->token);


        // dd($invitation);
       

        $invitation->load('colocation');

        // dd($invitation->email);


         Mail::to($invitation->email)->send(new Invit($invitation));
    // return 'Email sent!';

        // try {
        //     // Send email using Notification
        //     Notification::route('mail', $request->email)
        //                 ->notify(new InvitationNotif($invitation));
        // } catch (\Exception $e) {
        //     return back()->with('error', 'Erreur lors de l’envoi du mail: ' . $e->getMessage());
        // }

        return back()->with('success', 'Invitation envoyée avec succès!');
    }

    public function join($token)
{
    $invitation = Invitation::where('token', $token)->firstOrFail();


    if (auth()->user()->email !== $invitation->email) {
        abort(403, 'Cette invitation ne vous appartient pas.');
    }

    return view('invitations.accept', compact('invitation'));
}


public function accept($token)
{
    $invitation = Invitation::where('token', $token)->firstOrFail();
    $user = auth()->user();

    
    $invitation->colocation->members()->syncWithoutDetaching([$user->id => ['role' => 'membre']]);

    $invitation->delete();


    return redirect()->route('colocation.index')->with('success', 'Bienvenue dans la colocation !');
}

public function refuse($token)
{
    $invitation = Invitation::where('token', $token)->firstOrFail();

    $invitation->delete();

    return redirect()->route('dashboard')
        ->with('info', 'Invitation refusée.');
}

}