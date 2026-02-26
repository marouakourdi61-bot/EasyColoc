@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        {{-- CAS 1 : L'UTILISATEUR N'A PAS DE COLOCATION --}}
        @if(!$userColocation) 
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center border border-slate-100">
                <div class="mb-6 inline-flex items-center justify-center w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 mb-2">Vous n'avez pas encore de colocation.</h2>
                <p class="text-slate-500 mb-8">Créez votre espace pour commencer à gérer vos dépenses communes en toute simplicité.</p>
                
                <button id="btn-show-form" onclick="toggleColocForm(true)" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition-all transform hover:scale-105">
                    + Ajouter une colocation
                </button>

                {{-- FORMULAIRE --}}
                <div id="coloc-form-container" class="hidden mt-10 p-6 bg-slate-50 rounded-xl border border-slate-200 text-left animate-fade-in">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Nouvelle Colocation</h3>
                    <form action="{{ route('colocation.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nom de la colocation</label>
                            <input type="text" name="name" required class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border" placeholder="Ex: Appartement des Lilas">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Description</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border" placeholder="Une brève description..."></textarea>
                        </div>
                        <div class="flex items-center justify-end space-x-4 pt-4">
                            <button type="button" onclick="toggleColocForm(false)" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800">
                                Annuler
                            </button>
                            <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold shadow-md transition-colors">
                                Créer la colocation
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        {{-- CAS 2 : Colocation existe --}}
        @else
            <div class="space-y-8">

                {{-- Header --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $userColocation->name }}</h1>
                        <p class="text-slate-500">{{ $userColocation->description }}</p>
                    </div>
                    <button class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition shadow-sm">
                        Inviter un membre
                    </button>
                </div>

                {{-- Statistiques dynamiques --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    {{-- Membres --}}
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col items-center justify-center hover:shadow-md transition">
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wide">Membres</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $userColocation->members->count() }}</p>
                    </div>

                    {{-- Total Dépenses --}}
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col items-center justify-center hover:shadow-md transition">
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wide">Total Dépenses</p>
                        <p class="text-2xl font-bold text-green-600">
                            {{ number_format($userColocation->members->sum('pivot.balance'), 2) }}€
                        </p>
                    </div>

                    {{-- Balance Totale --}}
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col items-center justify-center hover:shadow-md transition">
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wide">Balance Totale</p>
                        <p class="text-2xl font-bold {{ $userColocation->members->sum('pivot.balance') >= 0 ? 'text-green-600' : 'text-red-500' }}">
                            {{ $userColocation->members->sum('pivot.balance') >= 0 ? '+' : '' }}
                            {{ number_format($userColocation->members->sum('pivot.balance'), 2) }}€
                        </p>
                    </div>

                    {{-- Réputation Moyenne --}}
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col items-center justify-center hover:shadow-md transition">
                        <p class="text-sm font-semibold text-slate-400 uppercase tracking-wide">Réputation Moyenne</p>
                        <p class="text-2xl font-bold text-indigo-600">
                            {{ number_format($userColocation->members->avg('pivot.reputation') ?? 0, 1) }}
                        </p>
                    </div>
                </div>

                {{-- Liste des membres dynamique --}}
                <div class="mt-8">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Membres de la colocation</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($userColocation->members as $member)
                            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center space-x-4 hover:shadow-md transition">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-bold text-slate-800">{{ $member->name }}</h4>
                                    <p class="text-xs font-semibold text-indigo-500 uppercase tracking-wider">
                                        {{ $member->pivot->role ?? 'Membre' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-400 uppercase font-bold tracking-tighter">Solde</p>
                                    <p class="text-lg font-mono font-bold {{ $member->pivot->balance >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                        {{ $member->pivot->balance >= 0 ? '+' : '' }}{{ number_format($member->pivot->balance, 2) }}€
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        @endif

    </div>
    
</div>

<script>
function toggleColocForm(show) {
    const formContainer = document.getElementById('coloc-form-container');
    const btnShow = document.getElementById('btn-show-form');
    
    if (show) {
        formContainer.classList.remove('hidden');
        btnShow.classList.add('hidden');
        setTimeout(() => formContainer.scrollIntoView({ behavior: 'smooth', block: 'center' }), 100);
    } else {
        formContainer.classList.add('hidden');
        btnShow.classList.remove('hidden');
    }
}
</script>
@endsection