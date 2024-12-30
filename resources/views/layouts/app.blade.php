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
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
         <!-- Make sure you put this AFTER Leaflet's CSS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
        <!-- Styles -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 relative">
            @include('components.navbar')
            <div class="pt-14 gap-4 lg:flex w-full">
                <div id="sidebar" class="p-4 hidden lg:flex flex-col gap-2 bg-white w-1/6 drop-shadow-md min-h-screen fixed left-0">
                    <p class="text-lg font-semibold text-center pb-10">Menu - menu</p>
                    <a href="{{ route('dashboard') }}" class="font-semibold text-lg {{ Route::is('dashboard') ? 'link' : '' }}">Dashboard</a>
                    <a href="{{ route('main_sppds.index') }}" class=" font-semibold text-lg {{ Route::is('main_sppds.index') ? 'link' : '' }}">SPPD</a>
                    <a href="{{ route('eslons.index') }}" class=" font-semibold text-lg {{ Route::is('eslons.index') ? 'link' : '' }}">Eslon</a>
                    <a href="{{ route('pocket_moneys.index') }}" class=" font-semibold text-lg {{ Route::is('pocket_moneys.index') ? 'link' : '' }}">Uang Saku</a>
                    <a href="{{ route('transportations.index') }}" class=" font-semibold text-lg {{ Route::is('transportations.index') ? 'link' : '' }}">Transportasi</a>
                    <form method="POST" action="{{ route('logout') }}" class="">
                        @csrf
                        <button type="submit" class=" font-semibold text-lg">Logout</button>
                    </form>
                </div>
                <div id="right-side" class="lg:w-5/6 w-full lg:ml-[16.666667svw]">
                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white shadow">
                            <div class="max-w-7xl text-center mx-auto py-4 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset
        
                    <!-- Page Content -->
                    <main>
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
