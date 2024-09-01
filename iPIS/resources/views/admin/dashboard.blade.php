<x-app-layout>

    <h1 class="text-2xl font-bold">Admin Dashboard</h1>
             <!-- Fetch Current User Login -->
             @if (Auth::guard('admin')->check())
    <h3 class="text-lg mb-8"> Welcome, <span class="underline">({{ Auth::guard('admin')->user()->role }}) {{ Auth::guard('admin')->user()->name }}</span></h3>
@endif

<div class="container mx-auto p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <div class="bg-white hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300">
        <h2 class="text-lg">Total Registrations</h2>
    
        <p class="text-4xl">{{ $totalTeams }}</p>
    </div>
   

        
        <div class=" bg-white hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300">
            <h2 class="text-lg ">Women's Volleyball (Competitive)</h2>
            <p class="text-4xl ">5</p>
        </div>
    
        <div class=" bg-white hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300">
            <h2 class="text-lg ">Women's Volleyball (Development)</h2>
            <p class="text-4xl">5</p>
        </div>
        
        <div class=" bg-white hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300">
            <h2 class="text-lg ">Women's Basketball (Sport)</h2>
            <p class="text-4xl ">10</p>
        </div>
    
        <div class=" bg-white hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300">
            <h2 class="text-lg ">Men's Basketball (Competitive)</h2>
            <p class="text-4xl ">10</p>
        </div>
    
        <div class=" bg-white hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300">
            <h2 class="text-lg ">Men's Basketball (Development)</h2>
            <p class="text-4xl ">10</p>
        </div>
    
        <div class=" bg-white hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300">
            <h2 class="text-lg ">Men's Volleyball (Competitive)</h2>
            <p class="text-4xl ">10</p>
        </div>
    
        <div class=" bg-white hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300">
            <h2 class="text-lg ">Men's Volleyball (Development)</h2>
            <p class="text-4xl ">10</p>
        </div>
    
        <div class="col-span-1 lg:col-span-2 bg-white p-4 rounded-lg shadow hover:bg-green-700 hover:text-white">
            <h2 class="text-lg ">Remaining Slots</h2>
            <p class="text-4xl">0</p>
        </div>
    
        <div class="col-span-1 lg:col-span-2 bg-white p-4 rounded-lg shadow hover:bg-green-700 hover:text-white">
            <h2 class="text-lg ">Incomplete Documents</h2>
            <p class="text-4xl">86</p>
        </div>
    </div>
    
    
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</x-app-layout>
