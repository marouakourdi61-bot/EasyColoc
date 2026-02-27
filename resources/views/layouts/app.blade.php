<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>

    <!-- Tailwind CDN -->
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

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>

<body class="bg-backgroundLight dark:bg-backgroundDark text-slate-900 dark:text-slate-100 min-h-screen flex font-display">

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content -->
    <main class="flex-1 flex flex-col">

        <!-- Navigation -->
        <nav class="bg-white dark:bg-backgroundDark border-b border-slate-200 dark:border-primary/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">

                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                            <div class="size-8 bg-primary rounded-lg flex items-center justify-center text-white">
                                <span class="material-symbols-outlined text-sm">
                                    account_balance_wallet
                                </span>
                            </div>
                            <span class="font-bold text-lg">
                                EasyColoc
                            </span>
                        </a>
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center gap-6">

                        <!-- Profile -->
                        <a href="{{ route('profile.edit') }}"
                           class="text-slate-700 dark:text-slate-300 hover:text-primary transition">
                            Profile
                        </a>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                                Log Out
                            </button>
                        </form>

                    </div>

                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="flex-1 bg-white dark:bg-backgroundDark p-8">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </div>

    </main>

</body>
</html>