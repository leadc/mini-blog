@php
use App\Models\LandingText;
if (!isset($landing)) {
    $landing = array_merge(config('landing'), LandingText::all()->pluck('value', 'key')->toArray());
}
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased min-h-screen flex flex-col">
        <div class="flex-1 min-h-0 flex flex-col bg-gray-100 dark:bg-gray-900">
            <div class="bg-white">
                <div class="w-full max-w-5xl mx-auto px-4">
                    @include('layouts.navigation')
                </div>
            </div>
            {{-- @include('layouts.navigation') --}}

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1">
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot ?? '' }}
                @endif
            </main>
        </div>
        <div class="bg-white w-full">
            <footer class="w-full max-w-5xl mx-auto py-8 text-center text-gray-400 text-xs">
                <hr>
                <div class="mt-8">
                    &copy; {{ date('Y') }} {{ $landing['footer'] }}
                </div>
            </footer>
        </div>
    </body>
</html>
