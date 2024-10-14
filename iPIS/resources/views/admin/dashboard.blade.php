<x-app-layout>

    <h1 class="text-2xl font-bold">Admin Dashboard</h1>

    <!-- Fetch Current User Login -->
    @if (Auth::guard('admin')->check())
        <h3 class="text-lg mb-8">
            Welcome, <span class="underline">({{ Auth::guard('admin')->user()->role }}) {{ Auth::guard('admin')->user()->name }}</span>
        </h3>
    @endif

    <div class="container mx-auto p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <!-- Total Registrations -->
        <div class="bg-gray-50 hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300 col-span-2">
            <h2 class="text-lg">Total Registrations</h2>
            <p class="text-4xl">{{ $totalRegistrations }}</p>
        </div>

        <!-- Deisplay dynamic data for each category -->
        <div class="bg-gray-50 hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300">
            <h2 class="text-lg">Boys Basketball Developmental</h2>
            <p class="text-4xl">{{ $categories['Boys Basketball Developmental'] }}</p>
        </div>

        <div class="bg-gray-50 hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300">
            <h2 class="text-lg">Boys Basketball Competitive</h2>
            <p class="text-4xl">{{ $categories['Boys Basketball Competitive'] }}</p>
        </div>

        <div class="bg-gray-50 hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300">
            <h2 class="text-lg">Girls Basketball Developmental</h2>
            <p class="text-4xl">{{ $categories['Girls Basketball Developmental'] }}</p>
        </div>

        <div class="bg-gray-50 hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300">
            <h2 class="text-lg">Girls Basketball Competitive</h2>
            <p class="text-4xl">{{ $categories['Girls Basketball Competitive'] }}</p>
        </div>

        <div class="bg-gray-50 hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300">
            <h2 class="text-lg">Boys Volleyball Developmental</h2>
            <p class="text-4xl">{{ $categories['Boys Volleyball Developmental'] }}</p>
        </div>

        <div class="bg-gray-50 hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300">
            <h2 class="text-lg">Boys Volleyball Competitive</h2>
            <p class="text-4xl">{{ $categories['Boys Volleyball Competitive'] }}</p>
        </div>

        <div class="bg-gray-50 hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300">
            <h2 class="text-lg">Girls Volleyball Developmental</h2>
            <p class="text-4xl">{{ $categories['Girls Volleyball Developmental'] }}</p>
        </div>

        <div class="bg-gray-50 hover:bg-green-700 hover:text-white p-4 rounded-lg transition duration-300">
            <h2 class="text-lg">Girls Volleyball Competitive</h2>
            <p class="text-4xl">{{ $categories['Girls Volleyball Competitive'] }}</p>
        </div>
    </div>
    
    <div class="container mx-auto p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <!-- Additional dynamic sections (remaining slots, incomplete documents, etc.) -->
        <div class="col-span-1 lg:col-span-2 bg-gray-50 p-4 rounded-lg shadow hover:bg-green-700 hover:text-white">
            <h2 class="text-lg">Remaining Slots</h2>
            <p class="text-4xl">0</p>
        </div>

        <div class="col-span-1 lg:col-span-2 bg-gray-50 p-4 rounded-lg shadow hover:bg-green-700 hover:text-white">
            <h2 class="text-lg">Incomplete Documents</h2>
            <p class="text-4xl">{{$incompleteDocuments }}</p>
        </div>
    </div>
    
    
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-lg shadow-md">
            <div class="bg-green-800 text-white px-4 py-2 rounded-t-lg">
                <h3 class="text-xl font-bold mb-2">Recent Documents Activities</h3>
            </div>
            <div class="rounded-b-lg p-4 py-2">
                <ul>
                 
                </ul>
            </div>
        </div>
        <div class="rounded-lg shadow-md">
            <div class="bg-green-800 text-white px-4 py-2 rounded-t-lg">
                <h3 class="text-xl font-bold mb-2">Recent Activities</h3>
            </div>
            <div class="rounded-b-lg p-4 py-2">
                <ul>
                @forelse ($activities as $activity)
                    <li>
                        <strong>{{ $activity->first_name }} {{ $activity->last_name }} ({{ $activity->role ?? 'No role' }} - {{ $activity->school_name ?? 'No school' }}):</strong>
                        {{ $activity->description }}
                        <small>({{ $activity->created_at->diffForHumans() }})</small>
                    </li>
                @empty
                    <li>No recent activities available</li>
                @endforelse
                </ul>
            </div>
        </div>
        <div class="rounded-lg shadow-md">
            <div class="bg-green-800 text-white px-4 py-2 rounded-t-lg">
                <h3 class="text-xl font-bold mb-2">Standing</h3>
            </div>
            <div class="rounded-b-lg p-4 py-2">
                <ol type="1">
                 <!--    <li type="1" class="border text-xs flex w-full">
                        <div class="bg-yellow-100 p-2 w-1/12">1</div>
                        <div class="my-2 ps-2 w-6/12 border-e-2">FTICGC</div>
                        <div class="p-2 w-3/12">Wins</div>
                        <div class="p-2 w-2/12 font-bold">06</div>
                    </li>
                    <li type="1" class="border text-xs flex w-full">
                        <div class="bg-slate-300 p-2 w-1/12">1</div>
                        <div class="my-2 ps-2 w-6/12 border-e-2">FTICGC</div>
                        <div class="p-2 w-3/12">Wins</div>
                        <div class="p-2 w-2/12 font-bold">06</div>
                    </li>
                    <li type="1" class="border text-xs flex w-full">
                        <div class="bg-red-100 p-2 w-1/12">1</div>
                        <div class="my-2 ps-2 w-6/12 border-e-2">FTICGC</div>
                        <div class="p-2 w-3/12">Wins</div>
                        <div class="p-2 w-2/12 font-bold">06</div>
                    </li> -->
                </ol>
            </div>
        </div>
    </section>
</x-app-layout>
