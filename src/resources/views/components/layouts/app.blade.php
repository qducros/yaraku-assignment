<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css'])

        <title>{{ $title ?? 'Yaraku assignment' }}</title>
    </head>
    <body x-data="{ scrollTopShow: false }" x-on:scroll.window = "scrollTopShow = (window.pageYOffset > 150) ? true : false">
        <x-header />

        <main class="container px-3">
            {{ $slot }}
        </main>

        <div x-show="scrollTopShow">
            <button class="scroll-top" x-on:click="window.scrollTo({top: 0, behavior: 'smooth'})">
                <svg height="48" viewBox="0 0 48 48" width="48" height="48px" xmlns="http://www.w3.org/2000/svg">
                    <path id="scrolltop-arrow" fill="grey" d="M14.83 30.83l9.17-9.17 9.17 9.17 2.83-2.83-12-12-12 12z"></path>
                </svg>
            </button>
        </div>

        <x-footer />
    </body>
</html>
