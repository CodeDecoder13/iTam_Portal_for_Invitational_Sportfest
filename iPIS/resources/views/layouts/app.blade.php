<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Your website description here">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


    <title>{{ config('app.name', 'ITam_Invitationals_Sportfest') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Stylesheets -->

    @vite('resources/css/app.css') <!-- Ensure you are using Vite correctly -->


    <!-- Scripts -->
    @vite('resources/js/app.js')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-100 min-h-screen flex flex-col lg:flex-row">
    <!-- Sidebar -->


    @if (Auth::guard('admin')->check())
        <div class="w-full lg:w-4/12 xl:w-2/12 min-h-screen">
            @include('layouts.admin.sidebar')
        </div>
    @elseif (Auth::guard('web')->check())
        <div class="w-full lg:w-4/12 xl:w-2/12 min-h-screen">
            @include('layouts.sidebar')
        </div>
    @endif
    <!-- Main Content -->
    <main class="flex flex-col flex-grow px-10 py-5">
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        @if (session()->has('fail'))
            <div class="alert alert-danger">
                {{ session()->get('fail') }}
            </div>
        @endif
        <div class="flex flex-col min-h-screen">
            <!-- Page Heading -->
            <header class="bg-white shadow">
                <!-- Include any header content here -->
            </header>

            <!-- Page Content -->
            <div class="flex-grow">

                {{ $slot }}
            </div>

            <!-- Footer -->
            @include('layouts.footer')
        </div>
    </main>
</body>

</html>
