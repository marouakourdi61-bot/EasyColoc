<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Créer un compte</h2>
        <p class="text-slate-500 mt-2 font-medium">Rejoignez l'aventure EasyColoc dès aujourd'hui</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-black text-slate-700 mb-1 uppercase tracking-wider">Nom complet</label>
            <x-text-input id="name" 
                class="block w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition" 
                type="text" name="name" :value="old('name')" 
                required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs font-bold" />
        </div>

        <div>
            <label for="email" class="block text-sm font-black text-slate-700 mb-1 uppercase tracking-wider">Adresse Email</label>
            <x-text-input id="email" 
                class="block w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition" 
                type="email" name="email" :value="old('email')" 
                required autocomplete="username" placeholder="votre@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold" />
        </div>

        <div>
            <label for="password" class="block text-sm font-black text-slate-700 mb-1 uppercase tracking-wider">Mot de passe</label>
            <x-text-input id="password" 
                class="block w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition" 
                type="password" name="password" 
                required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-black text-slate-700 mb-1 uppercase tracking-wider">Confirmer le mot de passe</label>
            <x-text-input id="password_confirmation" 
                class="block w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition" 
                type="password" name="password_confirmation" 
                required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs font-bold" />
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-xl shadow-lg shadow-indigo-100 transition-all active:scale-[0.98]">
                S'inscrire gratuitement
            </button>
        </div>

        <div class="text-center text-sm text-slate-500 font-medium">
            Déjà inscrit ? 
            <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">
                Se connecter
            </a>
        </div>
    </form>
</x-guest-layout>