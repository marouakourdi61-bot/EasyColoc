<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $colocation->name }} | Dashboard</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#0f0029",
                        backgroundLight: "#f6f5f8",
                        backgroundDark: "#160f23",
                        accent: "#7c3aed",
                    },
                    fontFamily: {
                        display: ["Manrope", "sans-serif"]
                    },
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Manrope', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #7c3aed;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-backgroundLight dark:bg-backgroundDark text-slate-900 dark:text-slate-100 min-h-screen flex flex-col"
    x-data="{ openExpenseModal: false, openInviteModal: false, openLeaveModal: false }">

    @include('layouts.navigation')

    <div class="flex flex-1 overflow-hidden">
        @include('layouts.sidebar')

        <main class="flex-1 overflow-y-auto custom-scrollbar">
            <div class="p-8 max-w-7xl mx-auto space-y-8">

                {{-- Header --}}
                <header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div class="space-y-1">
                        <a href="{{ route('dashboard') }}"
                            class="text-accent hover:underline text-xs font-black flex items-center gap-1 uppercase tracking-widest mb-2">
                            <span class="material-symbols-outlined text-sm">arrow_back</span> Mes Colocs
                        </a>
                        <h2 class="text-5xl font-black tracking-tighter">{{ $colocation->name }}</h2>
                        <p class="opacity-50 flex items-center gap-2 font-bold italic">
                            
                            {{ $colocation->description ?? 'Espace partagé' }}
                        </p>
                    </div>

                    {{-- Flash Messages --}}
                    <div class="space-y-4">
                        @if (session('success'))
                            <div
                                class="flex items-center gap-3 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-2xl shadow-sm">
                                <span class="material-symbols-outlined">check_circle</span>
                                <p class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</p>
                            </div>
                        @endif

                        @if (session('error'))
                            <div
                                class="flex items-center gap-3 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 rounded-2xl shadow-sm">
                                <span class="material-symbols-outlined">error</span>
                                <p class="text-xs font-black uppercase tracking-widest">{{ session('error') }}</p>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div
                                class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 rounded-2xl shadow-sm">
                                <ul class="list-disc list-inside text-xs font-black uppercase tracking-tight">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="flex gap-3 items-center">
                        @if ($hasLeft)
                            <span
                                class="flex items-center gap-2 text-[10px] font-black uppercase px-4 py-2 rounded-xl bg-slate-200 dark:bg-white/5 text-slate-500 border border-slate-300 dark:border-white/10">
                                <span class="material-symbols-outlined text-sm">visibility</span> Lecture seule
                            </span>
                        @else
                            @if (in_array($role, ['membre', 'member']))
                                <button @click="openLeaveModal = true"
                                    class="bg-white dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-red-500 px-5 py-3 rounded-2xl font-black hover:bg-red-50 transition flex items-center gap-2 shadow-sm text-sm active:scale-95">
                                    <span class="material-symbols-outlined text-sm">logout</span> Quitter
                                </button>
                            @endif

                            @if (in_array($role, ['admin', 'owner']))
                                <button @click="openInviteModal = true"
                                    class="bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 p-3 rounded-2xl font-bold hover:bg-slate-50 dark:hover:bg-white/10 transition shadow-sm active:scale-95">
                                    <span class="material-symbols-outlined text-accent">person_add</span>
                                </button>
                            @endif

                            <button @click="openExpenseModal = true"
                                class="bg-accent text-white px-6 py-4 rounded-2xl font-black hover:scale-105 transition-all flex items-center gap-2 shadow-xl shadow-accent/30 active:scale-95">
                                <span class="material-symbols-outlined">add_card</span> Ajouter une dépense
                            </button>
                        @endif
                    </div>
                </header>

                {{-- Status Banner --}}
                @if ($hasLeft)
                    <div
                        class="flex items-center gap-3 p-4 bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 rounded-3xl animate-pulse">
                        <span class="material-symbols-outlined">info</span>
                        <p class="text-sm font-black uppercase tracking-tight">Vous avez quitté cette colocation (Mode
                            Lecture).</p>
                    </div>
                @endif

                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        class="bg-white dark:bg-primary/20 border border-slate-200 dark:border-white/5 p-6 rounded-[2rem] shadow-sm">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40 mb-3 text-accent">Total
                            Dépensé</p>
                        <h4 class="text-3xl font-black tracking-tighter">{{ number_format($total, 2) }} dh

                        </h4>
                    </div>
                    <div
                        class="bg-white dark:bg-primary/20 border border-slate-200 dark:border-white/5 p-6 rounded-[2rem] shadow-sm">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40 mb-3">Part / Membre</p>
                        <h4 class="text-3xl font-black tracking-tighter">{{ number_format($share, 2) }} dh

                        </h4>
                    </div>
                    <div
                        class="bg-white dark:bg-primary/20 border border-slate-200 dark:border-white/5 p-6 rounded-[2rem] shadow-sm">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40 mb-3">Membres</p>
                        <h4 class="text-3xl font-black tracking-tighter">{{ $activeMembers->count() }}</h4>
                    </div>
                    <div class="bg-accent text-white p-6 rounded-[2rem] shadow-xl shadow-accent/20">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-70 mb-3">Mon Rôle</p>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined">verified_user</span>
                            <h4 class="text-2xl font-black tracking-tight capitalize">
                                {{ $hasLeft ? 'Ex-membre' : $role }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- GAUCHE : Gestion & Membres --}}
                    @if (!$hasLeft)
                        <div class="lg:col-span-1 space-y-8">
                            {{-- Réputation --}}
                            <section
                                class="bg-white dark:bg-primary/10 border border-slate-200 dark:border-primary/20 rounded-[2.5rem] overflow-hidden shadow-sm">
                                <div
                                    class="p-6 border-b border-slate-100 dark:border-white/5 flex items-center gap-2 bg-slate-50/50 dark:bg-white/5">
                                    
                                    <h3 class="font-black uppercase tracking-widest text-xs">Membres & Réputation</h3>
                                </div>
                                <div class="p-6 space-y-6">
                                    @forelse($balances as $row)
                                        <div class="flex items-center justify-between group">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-2xl bg-accent text-white flex items-center justify-center font-black shadow-lg shadow-accent/20">
                                                    {{ strtoupper(substr($row['user']->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-black text-sm">{{ $row['user']->name }}</p>
                                                    <div class="flex items-center gap-0.5">
                                                        @php $stars = $row['balance'] >= 0 ? 5 : ($row['balance'] >= -20 ? 4 : 3); @endphp
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p
                                                    class="font-black text-sm {{ $row['balance'] < 0 ? 'text-rose-500' : 'text-emerald-500' }} tracking-tighter">
                                                    {{ $row['balance'] >= 0 ? '+' : '' }}{{ number_format($row['balance'], 2) }}
                                                    dh

                                                </p>
                                                <p class="text-[9px] font-bold opacity-40 uppercase">Solde act.</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center opacity-40 italic text-sm">Seul au monde ?</p>
                                    @endforelse
                                </div>
                            </section>

                            {{-- Règlements en attente --}}
                            <section
                                class="bg-white dark:bg-primary/10 border border-slate-200 dark:border-primary/20 rounded-[2.5rem] overflow-hidden shadow-sm">
                                <div
                                    class="p-6 border-b border-slate-100 dark:border-white/5 flex items-center gap-2 bg-slate-50/50 dark:bg-white/5">
                                    <span class="material-symbols-outlined text-accent">payments</span>
                                    <h3 class="font-black uppercase tracking-widest text-xs text-slate-900 dark:text-white">
                                        Règlements en attente</h3>
                                </div>
                                <div class="p-6 space-y-4">
                                    {{-- Exemple : On vous doit --}}
                                    @foreach($toReceive as $settlement)
                                        <div id="settlement-row-{{ $settlement->id }}"
                                            class="p-4 bg-emerald-500/5 dark:bg-emerald-500/10 rounded-3xl border border-emerald-500/20 group mb-4">

                                            <div class="flex justify-between items-start mb-4">
                                                <div class="text-[10px] font-black uppercase tracking-tight">
                                                    <span class="text-emerald-600">{{ $settlement->sender->name }}</span>
                                                    <span class="text-slate-400 mx-1">➜</span>
                                                    <span class="text-slate-900 dark:text-white">Moi</span>
                                                </div>
                                                <div class="text-xl font-black text-emerald-600 tracking-tighter">
                                                    {{ number_format($settlement->amount, 2) }} dh

                                                </div>
                                            </div>

                                            <button type="button"
                                                onclick="confirmPayment(this, {{ $settlement->id }})"
                                                class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-black rounded-2xl transition-all shadow-lg shadow-emerald-500/20 uppercase tracking-widest flex items-center justify-center gap-2 active:scale-95">
                                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                                Confirmer Réception
                                            </button>
                                        </div>
                                    @endforeach

                                    {{-- Exemple : Vous devez --}}
                                    @foreach($toPay as $settlement)
                                        <div
                                            class="p-4 bg-slate-500/5 dark:bg-slate-500/10 rounded-3xl border border-slate-500/20 group opacity-70 mb-4">
                                            <div class="flex justify-between items-start mb-4">
                                                <div class="text-[10px] font-black uppercase tracking-tight">
                                                    <span class="text-slate-900 dark:text-white">Moi</span>
                                                    <span class="text-slate-400 mx-1">➜</span>
                                                    <span class="text-indigo-400">{{ $settlement->receiver->name }}</span>
                                                </div>
                                                <div class="text-xl font-black text-slate-400 tracking-tighter">
                                                    {{ number_format($settlement->amount, 2) }} dh

                                                </div>
                                            </div>
                                            <button disabled
                                                class="w-full py-3 bg-slate-700/50 text-slate-400 text-[10px] font-black rounded-2xl cursor-not-allowed uppercase tracking-widest flex items-center justify-center gap-2">
                                                En attente de {{ $settlement->receiver->name }}
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        </div>
                    @endif

                    {{-- DROITE : Historique --}}
                    <div class="{{ $hasLeft ? 'lg:col-span-3' : 'lg:col-span-2' }}">
                        <section
                            class="bg-white dark:bg-primary/10 border border-slate-200 dark:border-primary/20 rounded-[2.5rem] overflow-hidden shadow-sm">
                            <div
                                class="p-6 border-b border-slate-100 dark:border-white/5 flex justify-between items-center bg-slate-50/50 dark:bg-white/5">
                                <h3 class="font-black flex items-center gap-2 text-xs uppercase tracking-widest">
                                    Historique
                                </h3>
                                <span
                                    class="text-[10px] font-black px-3 py-1 bg-accent/10 text-accent rounded-full uppercase tracking-widest">
                                    {{ $colocation->expenses->count() }} transactions
                                </span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead
                                        class="bg-slate-50 dark:bg-white/5 text-[9px] uppercase font-black tracking-[0.2em] opacity-40">
                                        <tr>
                                            <th class="px-8 py-5">Label</th>
                                            <th class="px-6 py-5">Payeur</th>
                                            <th class="px-6 py-5 text-right">Montant</th>
                                            <th class="px-6 py-5">Catégorie</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                        @forelse($colocation->expenses->sortByDesc('expense_date') as $expense)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group">
                                                <td class="px-8 py-5">
                                                    <p class="font-black text-sm tracking-tight">{{ $expense->label }}
                                                    </p>
                                                    <p class="text-[10px] font-bold opacity-40 italic">
                                                        {{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}
                                                    </p>
                                                </td>
                                                <td class="px-6 py-5">
                                                    <div class="flex items-center gap-2">
                                                        <div
                                                            class="w-6 h-6 rounded-lg bg-accent/10 text-accent flex items-center justify-center text-[10px] font-black border border-accent/20">
                                                            {{ substr($expense->user->name ?? '?', 0, 1) }}
                                                        </div>
                                                        <span
                                                            class="text-xs font-bold {{ $expense->user_id === auth()->id() ? 'text-accent' : 'opacity-70' }}">
                                                            {{ $expense->user_id === auth()->id() ? 'Moi' : $expense->user->name }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-5 text-right">
                                                    <span
                                                        class="text-sm font-black tracking-tighter text-slate-900 dark:text-white">
                                                        {{ number_format($expense->amount, 2) }} dh

                                                    </span>
                                                </td>
                                                <td class="px-6 py-5">
                                                    @php
                                                        $colors = [
                                                            'food' =>
                                                                'bg-amber-100 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                                                            'rent' =>
                                                                'bg-blue-100 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                                                            'utility' =>
                                                                'bg-indigo-100 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
                                                        ];
                                                        $cat = $expense->category ?? 'other';
                                                    @endphp
                                                    <span
                                                        class="px-3 py-1 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $colors[$cat] ?? 'bg-slate-100 text-slate-500 dark:bg-white/10 dark:text-slate-400' }}">
                                                        {{ $cat }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="p-20 text-center">
                                                    <span
                                                        class="material-symbols-outlined text-5xl opacity-10 mb-4">folder_off</span>
                                                    <p class="opacity-30 italic text-sm font-bold">Aucun mouvement pour
                                                        le moment.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Modals --}}
    @if (!$hasLeft && ($role === 'membre' || $role === 'member'))
        <div x-show="openLeaveModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div @click.away="openLeaveModal = false"
                class="bg-white dark:bg-backgroundDark w-full max-w-sm rounded-3xl shadow-2xl border border-white/10 p-6">
                <div class="w-12 h-12 rounded-2xl bg-red-500/10 flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-red-500">logout</span>
                </div>
                <h3 class="text-xl font-black mb-1">Quitter la colocation ?</h3>
                <p class="text-sm opacity-60 mb-6">
                    Vous allez quitter <strong>{{ $colocation->name }}</strong>. Vous pourrez toujours consulter
                    l'historique des dépenses en lecture seule.
                </p>
                <form action="{{ route('colocations.leave', $colocation) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="flex gap-3">
                        <button type="button" @click="openLeaveModal = false"
                            class="flex-1 px-4 py-3 rounded-2xl font-black bg-slate-100 dark:bg-white/5 transition">
                            Annuler
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 rounded-2xl font-black bg-red-500 text-white hover:bg-red-600 transition">
                            Quitter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif


    @if (!$hasLeft)
        <div x-show="openExpenseModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div @click.away="openExpenseModal = false"
                class="bg-white dark:bg-backgroundDark w-full max-w-md rounded-3xl shadow-2xl border border-white/10 overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-white/5">
                    <h3 class="text-xl font-black">Nouvelle Dépense</h3>
                </div>
                <form action="{{ route('expenses.store', $colocation) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-[10px] font-black uppercase opacity-50 mb-2">Description</label>
                            <input type="text" name="title" required
                                class="w-full bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 rounded-2xl p-3 focus:ring-accent"
                                placeholder="Ex: Courses Carrefour">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase opacity-50 mb-2">Montant (dh
                                )</label>
                            <input type="number" step="0.01" name="amount" required
                                class="w-full bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 rounded-2xl p-3 focus:ring-accent">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase opacity-50 mb-2">Catégorie</label>
                            <select name="category"
                                class="w-full bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 rounded-2xl p-3 focus:ring-accent">
                                <option value="food">Nourriture</option>
                                <option value="rent">Loyer</option>
                                <option value="utility">Charges</option>
                                <option value="other">Autre</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-black uppercase opacity-50 mb-2">Date</label>
                            <input type="date" name="expense_date" value="{{ date('Y-m-d') }}"
                                class="w-full bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 rounded-2xl p-3 focus:ring-accent">
                        </div>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="openExpenseModal = false"
                            class="flex-1 px-4 py-3 rounded-2xl font-black bg-slate-100 dark:bg-white/5 transition">Annuler</button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 rounded-2xl font-black bg-accent text-white shadow-lg shadow-accent/20 transition">Confirmer</button>
                    </div>
                </form>
            </div>
        </div>

        @if ($role === 'owner')
            <div x-show="openInviteModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
                <div @click.away="openInviteModal = false"
                    class="bg-white dark:bg-backgroundDark w-full max-w-sm rounded-3xl shadow-2xl border border-white/10 p-6">
                    <h3 class="text-xl font-black mb-1">Inviter un membre</h3>
                    <p class="text-sm opacity-50 mb-4">Un email d'invitation sera envoyé.</p>
                    <form action="{{ route('colocation.invite', $colocation) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="email" name="email" required placeholder="email@exemple.com"
                            class="w-full bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 rounded-2xl p-3 focus:ring-accent">
                        <div class="flex gap-3">
                            <button type="button" @click="openInviteModal = false"
                                class="flex-1 px-4 py-3 rounded-2xl font-black bg-slate-100 dark:bg-white/5 transition">Annuler</button>
                            <button type="submit"
                                class="flex-1 bg-accent text-white px-6 py-3 rounded-2xl font-black shadow-lg shadow-accent/20">Inviter</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    @endif
</body>

</html>

<script>
    function confirmPayment(button, settlementId) {

    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = ' Traitement...';

    fetch(`/settlements/${settlementId}/confirm`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {

            const row = document.getElementById(`settlement-row-${settlementId}`);
            row.style.transition = "all 0.5s ease";
            row.style.opacity = "0";
            row.style.transform = "scale(0.95)";

            setTimeout(() => {
                row.remove();
            }, 500);
        }
    })
    .catch(error => {
        button.disabled = false;
        button.innerHTML = originalText;
        alert('Erreur.');
    });
}
</script>