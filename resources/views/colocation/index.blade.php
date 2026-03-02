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

                    @if(empty($readonly))
                    <button id="btn-show-form" onclick="toggleColocForm(true)"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition-all transform hover:scale-105">
                        + Ajouter une colocation
                    </button>
                    @endif

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
                                <button type="button" onclick="toggleColocForm(false)" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800">Annuler</button>
                                <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold shadow-md transition-colors">Créer la colocation</button>
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

                        <div class="flex gap-2">
                            @php $isActive = auth()->user()->active_colocation_id == $userColocation->id; @endphp
                            @if($isActive && empty($readonly))
                                <button onclick="openModal('expense-modal')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Ajouter une dépense
                                </button>

                                @can('invite', $userColocation)
                                    <button onclick="openModal('invite-modal')" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition shadow-sm">
                                        Inviter un membre
                                    </button>
                                @endcan
                            @endif
                        </div>
                    </div>

                    {{-- Statistiques --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col items-center justify-center hover:shadow-md transition">
                            <p class="text-sm font-semibold text-slate-400 uppercase tracking-wide">Membres</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $userColocation->members->count() }}</p>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col items-center justify-center hover:shadow-md transition">
                            <p class="text-sm font-semibold text-slate-400 uppercase tracking-wide">Total Dépenses</p>
                            <p class="text-2xl font-bold text-indigo-600">
                                {{ number_format($userColocation->expenses->sum('amount'), 2) }}€
                            </p>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col items-center justify-center hover:shadow-md transition">
                            <p class="text-sm font-semibold text-slate-400 uppercase tracking-wide">Votre Solde</p>
                            @php $myPivot = $userColocation->members->where('id', auth()->id())->first()->pivot; @endphp
                            <p class="text-2xl font-bold {{ $myPivot->balance >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                {{ $myPivot->balance >= 0 ? '+' : '' }}{{ number_format($myPivot->balance, 2) }}€
                            </p>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col items-center justify-center hover:shadow-md transition">
                            <p class="text-sm font-semibold text-slate-400 uppercase tracking-wide">Réputation Moy.</p>
                            <p class="text-2xl font-bold text-amber-500">
                                {{ number_format($userColocation->members->avg('pivot.reputation') ?? 0, 1) }}
                            </p>
                        </div>
                    </div>

                    {{-- Liste des membres --}}
                    <div class="mt-8">
                        <h2 class="text-xl font-bold text-slate-900 mb-4">Membres de la colocation</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($userColocation->members as $member)
                                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center space-x-4 hover:shadow-md transition">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600 uppercase">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-bold text-slate-800">{{ $member->name }}</h4>
                                        <p class="text-xs font-semibold text-indigo-500 uppercase tracking-wider">{{ $member->pivot->role ?? 'Membre' }}</p>
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

                    {{-- Liste dépenses --}}
                    <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-bold text-slate-900 mb-4">Dernières Dépenses</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b border-slate-100">
                                        <th class="py-3 px-2 text-slate-500 font-semibold text-sm">Titre</th>
                                        <th class="py-3 px-2 text-slate-500 font-semibold text-sm">Catégorie</th>
                                        <th class="py-3 px-2 text-slate-500 font-semibold text-sm text-right">Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($userColocation->expenses as $expense)
                                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                                            <td class="py-3 px-2 font-medium text-slate-800">{{ $expense->title }}</td>
                                            <td class="py-3 px-2">
                                                <span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-md text-xs font-bold uppercase">{{ $expense->category }}</span>
                                            </td>
                                            <td class="py-3 px-2 font-bold text-slate-900 text-right">{{ number_format($expense->amount, 2) }}€</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="py-6 text-center text-slate-400 text-sm">Aucune dépense enregistrée.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- MODAL INVITATION --}}
                    @can('invite', $userColocation)
                        <div id="invite-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
                            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
                                <button onclick="closeModal('invite-modal')" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                                <h3 class="text-lg font-semibold mb-4 text-slate-800">Inviter un membre</h3>
                                <form action="{{ route('colocation.invite', $userColocation) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="colocation_id" value="{{ $userColocation->id }}">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email du membre</label>
                                        <input type="email" name="email" required class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm p-3 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500" placeholder="ami@exemple.com">
                                    </div>
                                    <div class="flex justify-end space-x-3 pt-2">
                                        <button type="button" onclick="closeModal('invite-modal')" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Annuler</button>
                                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Envoyer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endcan

                    {{-- MODAL DEPENSE --}}
                    <div id="expense-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative animate-fade-in">
                            <button onclick="closeModal('expense-modal')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <div class="mb-6">
                                <h3 class="text-xl font-bold text-slate-800">Nouvelle Dépense</h3>
                                <p class="text-sm text-slate-500">Ajoutez les détails de la dépense commune.</p>
                            </div>
                            <form action="{{ route('expenses.store') }}" method="POST" class="space-y-5">
                                @csrf
                                <input type="hidden" name="colocation_id" value="{{ $userColocation->id }}">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900 mb-1">Titre de la dépense</label>
                                    <input type="text" name="title" required class="block w-full rounded-xl border-slate-200 shadow-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 p-3 border" placeholder="Ex: Courses">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-900 mb-1">Montant (€)</label>
                                        <input type="number" step="0.01" name="amount" required class="block w-full rounded-xl text-gray-900 border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3 border" placeholder="0.00">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-900 mb-1">Catégorie</label>
                                        <select name="category" required class="block w-full rounded-xl text-gray-900 border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3 border bg-white">
                                            <option value="Alimentation">Alimentation</option>
                                            <option value="Loyer">Loyer</option>
                                            <option value="Énergie">Énergie</option>
                                            <option value="Transport">Transport</option>
                                            <option value="Divers">Divers</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="flex items-center justify-end space-x-3 pt-4">
                                    <button type="button" onclick="closeModal('expense-modal')" class="px-5 py-2.5 text-sm font-medium text-slate-900 hover:bg-slate-100 rounded-xl">Annuler</button>
                                    <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold shadow-lg transition-all transform hover:scale-105">Enregistrer</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </div>

    <script>
        // Formulaire Coloc
        function toggleColocForm(show) {
            const formContainer = document.getElementById('coloc-form-container');
            const btnShow = document.getElementById('btn-show-form');
            if (show) {
                formContainer.classList.remove('hidden');
                btnShow.classList.add('hidden');
            } else {
                formContainer.classList.add('hidden');
                btnShow.classList.remove('hidden');
            }
        }

        // Gestion Modales (Générique)
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection