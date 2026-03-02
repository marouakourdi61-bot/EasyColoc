<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Ravi de vous revoir</h2>
        <p class="text-slate-500 mt-2 font-medium">Connectez-vous à votre espace colocation</p>
    </div>

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-3">
            <span class="material-symbols-outlined text-rose-500">error</span>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
    @endif

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-sm font-black text-slate-700 mb-1 uppercase tracking-wider">Email</label>
            <x-text-input id="email" 
                class="block w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition" 
                type="email" name="email" :value="old('email')" 
                required autofocus placeholder="votre@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold" />
        </div>

        <div>
            <div class="flex justify-between items-center mb-1">
                <label for="password" class="block text-sm font-black text-slate-700 uppercase tracking-wider">Mot de passe</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition" href="{{ route('password.request') }}">
                        Oublié ?
                    </a>
                @endif
            </div>
            <x-text-input id="password" 
                class="block w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition" 
                type="password" name="password" 
                required placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold" />
        </div>

        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded-md border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm font-medium text-slate-600">Rester connecté</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-xl shadow-lg shadow-indigo-100 transition-all active:scale-[0.98]">
                {{ __('Log in') }}
            </button>
        </div>

        @if (Route::has('register'))
            <p class="text-center text-sm text-slate-500 font-medium">
                Pas encore de compte ? 
                <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:underline">Inscrivez votre coloc</a>
            </p>
        @endif
    </form>
</x-guest-layout>