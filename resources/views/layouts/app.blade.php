<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!--  Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')



        <div class="flex h-screen">
            <!--  サイドバー -->
            <aside class="bg-red-500 w-56 p-6 h-screen flex flex-col">
                <nav class="flex-1">
                    <ul class="space-y-4 text-white">
                        <li><a href="{{ route('players.index') }}" class="block px-4 py-2 hover:bg-blue-700">Player検索</a></li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-blue-700">0円システム利用者表示</a></li>
                        <li><a href="{{ route('tournaments.index') }}" class="block px-4 py-2 hover:bg-blue-700">トーナメント</a></li>
                        <li><a href="{{ route('players.subscribed') }}" class="block px-4 py-2 hover:bg-blue-700">サブスク利用者表示</a></li>
                    </ul>
                </nav>
            </aside>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
</body>

</html>