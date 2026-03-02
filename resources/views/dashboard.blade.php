<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Colocations | Dashboard</title>

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

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Manrope', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-backgroundLight dark:bg-backgroundDark text-slate-900 dark:text-slate-100 min-h-screen flex flex-col"
    x-data="{ openCreateModal: false }">

    @include('layouts.navigation')

    <div class="flex flex-1">
        @include('layouts.sidebar')

        <main class="flex-1 overflow-y-auto">
            <div class="p-8 max-w-7xl mx-auto space-y-8">

                <div class="flex justify-between items-end">
                    <div>
                        <h2 class="text-3xl font-black">Mes Colocations</h2>
                        <p class="opacity-60">Gérez vos espaces de vie partagés</p>
                    </div>
                    <button @click="openCreateModal = true"
                        class="bg-accent text-white px-6 py-3 rounded-xl font-bold hover:scale-105 transition flex items-center gap-2 shadow-lg shadow-accent/20">
                        <span class="material-symbols-outlined">add_home</span>
                        Nouvelle Colocation
                    </button>
                </div>

                @if(session('success'))
                    <div class="p-4 bg-green-500/10 border border-green-500/50 text-green-500 rounded-xl">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-4 bg-red-500/10 border border-red-500/50 text-red-500 rounded-xl">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($colocations as $colocation)
                        @php
                            $isActive = auth()->user()->active_colocation_id == $colocation->id;
                        @endphp

                        <div
                            class="bg-white dark:bg-primary/10 border {{ $isActive ? 'border-accent ring-2 ring-accent/20 shadow-xl shadow-accent/5' : 'border-slate-200 dark:border-primary/20 shadow-sm opacity-80 hover:opacity-100' }} rounded-2xl p-6 transition-all duration-300 group relative overflow-hidden">

                            @if($isActive)
                                <div class="absolute -right-4 -top-4 w-24 h-24 bg-accent/10 blur-3xl rounded-full"></div>
                            @endif

                            <div class="flex justify-between items-start mb-6">
                                <div
                                    class="w-12 h-12 {{ $isActive ? 'bg-accent text-white' : 'bg-slate-100 dark:bg-white/5 text-slate-400' }} rounded-xl flex items-center justify-center transition-colors">
                                    <span
                                        class="material-symbols-outlined">{{ $isActive ? 'home_work' : 'door_front' }}</span>
                                </div>

                                <div class="flex flex-col items-end gap-2">
                                    @if($isActive)
                                        <span
                                            class="flex items-center gap-1 text-[10px] uppercase font-black px-2 py-1 rounded-md bg-green-500 text-white animate-pulse">
                                            <span class="w-1 h-1 bg-white rounded-full"></span> Active
                                        </span>
                                    @else
                                        <span
                                            class="text-[10px] uppercase font-black px-2 py-1 rounded-md bg-slate-200 dark:bg-white/10 text-slate-500">
                                            Inactive
                                        </span>
                                    @endif

                                    <span
                                        class="text-[9px] uppercase font-black px-2 py-0.5 rounded-md border {{ $colocation->user_id == auth()->id() ? 'border-accent text-accent' : 'border-slate-300 text-slate-400' }}">
                                        {{ $colocation->user_id == auth()->id() ? 'Admin' : 'Membre' }}
                                    </span>
                                </div>
                            </div>

                            <h3 class="text-xl font-bold mb-2 {{ $isActive ? 'text-accent' : '' }} transition-colors">
                                {{ $colocation->name }}
                            </h3>
                            <p class="text-sm opacity-60 mb-6 line-clamp-2 h-10">{{ $colocation->description }}</p>

                            <div
                                class="flex items-center justify-between border-t border-slate-100 dark:border-white/5 pt-4">
                                <div class="flex items-center gap-1 opacity-70">
                                    <span class="material-symbols-outlined text-sm">group</span>
                                    <span
                                        class="text-sm font-bold">{{ $colocation->members_count ?? $colocation->members->count() }}
                                        membres</span>
                                </div>

                                <a href="{{ route('colocation.show', ['id' => $colocation->id]) }}"
                                    class="group/btn {{ $isActive ? 'text-accent' : 'text-slate-500' }} font-black text-sm flex items-center hover:text-accent transition-all">
                                    {{ $isActive ? 'Gérer' : 'Consulter' }}
                                    <span
                                        class="material-symbols-outlined text-sm ml-1 group-hover/btn:translate-x-1 transition-transform">
                                        {{ $isActive ? 'arrow_forward' : 'visibility' }}
                                    </span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div
                            class="lg:col-span-3 py-20 text-center bg-slate-100/50 dark:bg-white/5 rounded-3xl border-2 border-dashed border-slate-300 dark:border-primary/20">
                            <span class="material-symbols-outlined text-6xl opacity-20">sensor_door</span>
                            <p class="mt-4 text-xl opacity-60 font-medium">Vous n'avez pas encore de colocation.</p>
                            <button @click="openCreateModal = true"
                                class="mt-4 text-accent font-black hover:underline underline-offset-4">
                                Commencez par en créer une !
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <div x-show="openCreateModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>

        <div @click.away="openCreateModal = false"
            class="bg-white dark:bg-backgroundDark w-full max-w-md rounded-2xl shadow-2xl border border-white/10 overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-white/5">
                <h3 class="text-xl font-bold">Nouvelle Colocation</h3>
            </div>

            
            <form action="{{ route('colocation.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold mb-2 opacity-70">Nom de l'espace</label>
                    <input type="text" name="name" required
                        class="w-full bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 rounded-xl focus:ring-accent focus:border-accent"
                        placeholder="Ex: Appart Casa, Villa Sunshine...">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2 opacity-70">Description (Optionnel)</label>
                    <textarea name="description" rows="3"
                        class="w-full bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 rounded-xl focus:ring-accent focus:border-accent"
                        placeholder="Partage des frais de loyer et courses..."></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" @click="openCreateModal = false"
                        class="flex-1 px-4 py-3 rounded-xl font-bold bg-slate-100 dark:bg-white/5 hover:bg-slate-200 transition">
                        Annuler
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 rounded-xl font-bold bg-accent text-white shadow-lg shadow-accent/20 hover:opacity-90 transition">
                        Créer maintenant
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>