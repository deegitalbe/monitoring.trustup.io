<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        @vite(['resources/css/app.css'])
    </head>
    <body class="bg-slate-100 relative">

        <div class="bg-gray-900 w-full h-16">
            <div class="h-full container mx-auto text-white">
                <div class="h-full flex justify-between items-center">
                    <h1 class="text-xl font-bold">monitoring.trustup.io</h1>
                    <div class="flex space-x-8">
                        <a class="hover:underline" href="{{ route('end-points.index') }}">EndPoints</a>
                        <a class="hover:underline" href="{{ route('domain-pings-batches.index') }}">Domains</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto py-12">
            {{ $slot }}
        </div>


        @vite(['resources/js/app.js'])
    </body>
</html>
