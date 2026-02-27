@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">{{ $colocation->name }}</h1>

<p><strong>Créée par :</strong> {{ $colocation->owner?->name ?? 'Propriétaire inconnu' }}</p>    <p><strong>Membres :</strong></p>
    <ul>
        @foreach($colocation->users as $user)
            <li>{{ $user->name }} ({{ $user->email }})</li>
        @endforeach
    </ul>

    <hr class="my-4">

    <h2 class="text-xl font-semibold mb-2">Invitations en attente</h2>
    <ul>
        @foreach($colocation->invitations as $invitation)
            <li>{{ $invitation->email }} - Token: {{ $invitation->token }}</li>
        @endforeach
    </ul>
</div>
@endsection
