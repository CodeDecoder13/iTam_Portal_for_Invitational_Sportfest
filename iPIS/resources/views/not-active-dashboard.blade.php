<x-app-layout>
    <div class="flex-grow px-4 py-6">
        <h1 class="text-2xl">Dashboard</h1>
    <!-- Fetch Current User Login -->
    @if (Auth::check())
        <h3 class="text-lg mb-8">Welcome, Coach <span class="underline">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span></h3>
    @endif
</x-app-layout>