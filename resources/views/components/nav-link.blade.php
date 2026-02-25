@props(['href', 'active'])

<li>
    <a href="{{ $href }}" {{ $active ? 'class="text-primary font-medium"' : 'class="text-slate-600 dark:text-slate-400 hover:text-primary"' }}>
        {{ $slot }}
    </a>
</li>