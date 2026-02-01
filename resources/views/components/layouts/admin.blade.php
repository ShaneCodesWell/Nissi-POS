<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Nissi POS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/products.js', 'resources/js/sales.js', 'resources/js/reports.js', 'resources/js/crm.js'])
</head>

<body class="bg-gray-900 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <x-sidebar />
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <x-navbar />
            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-900 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
    <script>
        const themeToggle = document.getElementById("themeToggle");
        const body = document.body;

        themeToggle.addEventListener("click", () => {
            if (body.classList.contains("bg-gray-900")) {
                body.classList.remove("bg-gray-900");
                body.classList.add("bg-white");
                themeToggle.innerHTML = '<i class="fas fa-sun text-xl"></i>';
            } else {
                body.classList.remove("bg-white");
                body.classList.add("bg-gray-900");
                themeToggle.innerHTML = '<i class="fas fa-moon text-xl"></i>';
            }
        });
    </script>
</body>

</html>
