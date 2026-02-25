<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Tailwind Custom Config -->
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

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Manrope', sans-serif; }
    </style>
</head>

<body class="bg-backgroundLight dark:bg-backgroundDark text-slate-900 dark:text-slate-100 min-h-screen flex flex-col">

    <!-- Navigation -->
    @include('layouts.navigation')

    <div class="flex flex-1">
        @include('layouts.sidebar')

    

    <!-- Main -->
    <main class="flex-1 overflow-y-auto">

        <div class="p-8 max-w-7xl mx-auto space-y-8">

            <!-- Hero Card -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 relative overflow-hidden rounded-xl bg-gradient-to-br from-primary to-accent p-8 text-white shadow-2xl shadow-primary/20">
                    <p class="text-sm uppercase tracking-wider opacity-80">Household Balance</p>
                    <h3 class="text-5xl font-black mt-2 mb-6">$1,240.50</h3>

                    <div class="flex gap-6 flex-wrap">
                        <div class="bg-white/10 p-4 rounded-lg">
                            <p class="text-xs opacity-70">You are owed</p>
                            <p class="text-xl font-bold">$420.00</p>
                        </div>

                        <div class="bg-white/10 p-4 rounded-lg">
                            <p class="text-xs opacity-70">Pending payments</p>
                            <p class="text-xl font-bold">3 items</p>
                        </div>

                        <button class="bg-white text-primary px-8 py-3 rounded-lg font-bold hover:bg-slate-100 transition">
                            Settle Up
                        </button>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-primary/10 rounded-xl p-6 border border-slate-200 dark:border-primary/20 flex flex-col gap-4">
                    <h4 class="font-bold text-lg">Quick Actions</h4>

                    <button class="bg-primary text-white p-4 rounded-xl font-bold hover:scale-105 transition">
                        Add Expense
                    </button>

                    <button class="border-2 border-slate-200 p-4 rounded-xl font-bold hover:bg-slate-50 transition">
                        Invite Member
                    </button>
                </div>

            </div>

        </div>

    </main>

    </div>

</body>
</html>