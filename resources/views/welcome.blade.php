<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

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

    </head>
    <body class=" mx-10 font-sans antialiased dark:bg-black dark:text-white/50">
    <main class="mt-16 max-w-5xl mx-auto px-6">
    <div class="text-center py-20 border-b border-slate-100">
        <h1 class="text-6xl font-black text-slate-900 mb-6 tracking-tight">
            Easy<span class="text-indigo-600">Coloc</span>
        </h1>
        <p class="text-xl text-slate-500 mb-10 max-w-xl mx-auto font-medium">
            L'outil simple pour gérer vos dépenses, vos tâches et votre vie en communauté.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="px-10 py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                    Créer un compte
                </a>
            @endif
            
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="px-10 py-4 bg-white border-2 border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 hover:border-slate-300 transition">
                    Se connecter
                </a>
            @endif
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-12 py-16">
        <div class="text-center">
            <div class="text-indigo-600 mb-4 flex justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Dépenses partagées</h3>
            <p class="text-slate-500 text-sm leading-relaxed">Équilibrez vos comptes sans effort. Ajoutez une dépense, EasyColoc fait le calcul.</p>
        </div>

        <div class="text-center">
            <div class="text-indigo-600 mb-4 flex justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Tâches ménagères</h3>
            <p class="text-slate-500 text-sm leading-relaxed">Un planning clair pour que chacun sache quoi faire. Plus d'excuses pour la vaisselle.</p>
        </div>

        <div class="text-center">
            <div class="text-indigo-600 mb-4 flex justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Réputation</h3>
            <p class="text-slate-500 text-sm leading-relaxed">Gagnez des points en étant un bon colocataire. La confiance, ça se mérite.</p>
        </div>
    </div>
</main>
    </body>
</html>
