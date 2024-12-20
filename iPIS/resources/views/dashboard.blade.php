<x-app-layout>
    <div class="flex-grow px-4 py-6">
        <!-- Check if the user is active or not -->
        @if (Auth::user()->is_active)
            <!-- Main Dashboard for active users -->
            <h1 class="text-2xl font-bold">Dashboard</h1>
            @if (Auth::check())
                <h3 class="text-lg mb-8">Welcome, <span class="underline">{{ Auth::user()->role }} {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span></h3>
            @endif

            <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-lg shadow-md">
                    <div class="bg-green-800 text-white px-4 py-2 rounded-t-lg">
                        <h3 class="text-xl font-bold mb-2">Recent Activities</h3>
                    </div>
                    <div class="rounded-b-lg p-4 py-2">
                        <ul>
                            @foreach($activities as $activity)
                                <li class="border text-xs p-2 flex">
                                    <div class="text-green-700 text-xl">
                                        <ion-icon name="document"></ion-icon>
                                    </div>
                                    <div>
                                        <span class="font-bold">{{ $activity->activity_type }}:</span> 
                                        @if($activity->first_name && $activity->last_name)
                                            {{ $activity->first_name }} {{ $activity->last_name }} - {{ $activity->description }}
                                        @else
                                            You - {{ $activity->description }}
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                
                        <!-- Pagination links -->
                        <div class="mt-4">
                            {{ $activities->links() }}
                        </div>
                    </div>
                </div>
                
                <div class="rounded-lg shadow-md mb-4">
                    <div class="bg-green-800 text-white px-4 py-2 rounded-t-lg">
                        <h3 class="text-xl font-bold">Upcoming Games</h3>
                    </div>
                    <div class="rounded-b-lg p-4">
                        @if($upcomingGames->isEmpty())
                            <p class="text-center text-gray-500">No upcoming games scheduled.</p>
                        @else
                            <ul>
                            @foreach($upcomingGames as $game)
                                <li class="border-b py-2 flex justify-between items-center">
                                    <div>
                                        <strong class="text-sm">{{ $game->team1_school_name }}</strong>
                                        vs
                                        <strong class="text-sm">{{ $game->team2_school_name }}</strong>
                                        <div class="text-gray-600">{{ $game->sport_category }}</div>
                                    </div>
                                    <div>
                                        <span class="text-gray-700">{{ \Carbon\Carbon::parse($game->game_date)->format('M d, Y') }}</span>
                                    </div>
                                </li>
                            @endforeach
                            </ul>
                            <!-- Pagination links -->
                            <div class="mt-4">
                                {{ $upcomingGames->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-lg shadow-md">
                    <div class="bg-green-800 text-white px-4 py-2 rounded-t-lg">
                        <h3 class="text-xl font-bold mb-2">Standing</h3>
                    </div>
                    <div class="rounded-b-lg p-4 py-2">
                        <ul type="1">
                            <!--<li type="1" class="border text-xs flex w-full">
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
                        </ul>
                    </div>
                </div>

            

        @else
            <!-- Hold message for inactive users -->
            <h1 class="text-3xl font-bold mb-4">Dashboard</h1>
            <h3 class="text-xl mb-8">Welcome, Coach <span class="underline">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span></h3>
            <div class="flex flex-col items-center justify-center min-h-screen">
                <div class="text-center">
                    <div class="mb-6">
                        <img src="{{ asset('/images/search-magnifying.png') }}" alt="Approval Icon" class="w-24 h-24 mx-auto">
                    </div>
                    <h1 class="text-xl font-semibold mb-4">Your account is temporarily on hold for approval by our admins.</h1>
                    <p class="text-gray-600 mb-8">Please allow 24-48 hours for the approval process. We appreciate your patience.</p>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-full hover:bg-green-800 focus:outline-none">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
    <!-- New Module: No Team Yet -->
    @if (Auth::user()->is_active)
    @if ($teams->isEmpty())
                <div class="mt-8 bg-white shadow-md rounded-lg p-6 text-center w-full">
                    <h2 class="text-2xl font-bold mb-4">No Team Yet</h2>
                    <p class="text-gray-600 mb-4">It seems like no team has been created yet. Add a team now to get started.</p>
                    <button onclick="window.location.href='{{ route('add-teams') }}'" class="px-4 py-2 bg-green-700 text-white rounded-full hover:bg-green-800 focus:outline-none">
                        Add Team
                    </button>
                </div>
            @endif
    @endif
</x-app-layout>
