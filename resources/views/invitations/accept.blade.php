@extends('layouts.app')

@section('content')
Since we are using the Manrope font and the Primary/Accent color palette from your previous dashboard, we should avoid the "classic" red/green buttons and go for something more sophisticated.

Here is the refactored invitation UI using the Dark/Modern theme. It uses a glassmorphism backdrop and clean, high-contrast cards.

HTML
@extends('layouts.app')

@section('content')
<div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-backgroundDark/80 backdrop-blur-md">
    
    <div class="bg-white dark:bg-primary/20 w-full max-w-md rounded-[2.5rem] shadow-2xl border border-slate-200 dark:border-white/10 overflow-hidden transition-all animate-in fade-in zoom-in duration-300">
        
        <div class="relative h-32 bg-accent flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 opacity-20">
                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 20 0 50 0 100 100 Z"></path>
                </svg>
            </div>
            <div class="bg-white/20 p-4 rounded-2xl backdrop-blur-sm border border-white/30 shadow-xl">
                <span class="material-symbols-outlined text-4xl text-white">home_work</span>
            </div>
        </div>

        <div class="p-8 text-center">
            <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">
                Nouvelle Invitation
            </h3>
            
            <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-8">
                Vous avez été invité à rejoindre l'espace partagé :<br>
                <span class="inline-block mt-2 px-4 py-1.5 bg-accent/10 text-accent rounded-full font-bold text-lg">
                    {{ $invitation->colocation->name }}
                </span>
            </p>

            <div class="space-y-3">
                <form method="POST" action="{{ route('invitation.accept', $invitation->token) }}">
                    @csrf
                    <button type="submit" class="w-full py-4 bg-accent hover:bg-accent/90 text-white font-black rounded-2xl shadow-lg shadow-accent/30 transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">check_circle</span>
                        Accepter l'invitation
                    </button>
                </form>

                <form method="POST" action="{{ route('invitation.refuse', $invitation->token) }}">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-slate-100 dark:bg-white/5 hover:bg-rose-500/10 text-slate-400 hover:text-rose-500 font-bold rounded-2xl transition-all flex items-center justify-center gap-2 border border-transparent hover:border-rose-500/20">
                        <span class="material-symbols-outlined text-sm">block</span>
                        Décliner l'invitation
                    </button>
                </form>
            </div>

            <p class="mt-8 text-[10px] uppercase font-black tracking-widest opacity-30 text-slate-500 dark:text-white">
                Coloc' Reputation System &copy; 2026
            </p>
        </div>
    </div>
</div>

@endsection