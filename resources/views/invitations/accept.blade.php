@extends('layouts.app')

@section('content')

<div style="position: fixed; top:0; left:0; width:100%; height:100%; 
background: rgba(0,0,0,0.5); display:flex; justify-content:center; align-items:center;">

    <div style="background:white; padding:30px; border-radius:10px; text-align:center; width:400px;">
        
        <h3>Invitation à rejoindre</h3>

        <p>
            Voulez-vous rejoindre la colocation 
            <strong>{{ $invitation->colocation->name }}</strong> ?
        </p>

        <form method="POST" action="{{ route('invitation.accept', $invitation->token) }}">
            @csrf
            <button type="submit" style="background:green; color:white; padding:10px 20px; border:none;">
                Accepter
            </button>
        </form>

        <form method="POST" action="{{ route('invitation.refuse', $invitation->token) }}" style="margin-top:10px;">
            @csrf
            <button type="submit" style="background:red; color:white; padding:10px 20px; border:none;">
                Refuser
            </button>
        </form>

    </div>
</div>

@endsection