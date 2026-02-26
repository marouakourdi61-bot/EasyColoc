
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body class="bg-backgroundLight dark:bg-backgroundDark text-slate-900 dark:text-slate-100 min-h-screen flex">

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main -->
    <main class="flex-1 flex flex-col">
        <!-- Header -->
          @include('layouts.navigation')
        <!-- Page Content -->
        <div class="flex-1 bg-white p-8">
    <div class="max-w-7xl mx-auto">
        @yield('content')
    </div>
</div>
    </main>

</body>
</html>