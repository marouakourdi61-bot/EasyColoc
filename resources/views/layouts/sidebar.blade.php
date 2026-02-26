<!-- Sidebar -->
<aside class="w-64 border-r border-slate-200 dark:border-primary/30 flex flex-col bg-white dark:bg-backgroundDark">
    
    <nav class="flex-1 px-4 space-y-2">

        <!-- Dashboard Link -->
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 font-medium" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined">dashboard</span>
            Dashboard
        </a>

        <!-- Expenses -->
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 transition" href="#">
            <span class="material-symbols-outlined">receipt_long</span>
            Expenses
        </a>

        <!-- Groups -->
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 transition" href="#">
            <span class="material-symbols-outlined">group</span>
            Groups
        </a>

        <!-- Colocation -->
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 transition" href="{{ route('colocation.index') }}">
            <span class="material-symbols-outlined">home</span>
            Colocation
        </a>

    </nav>
</aside>