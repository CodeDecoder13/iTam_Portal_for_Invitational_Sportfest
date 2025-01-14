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
                    <div class="rounded-b-lg p-4 py-2 h-72 overflow-y-auto bg-white border border-gray-300">
                        <ul>
                            @forelse($activities as $activity)
                                <li class="mb-4 border-b border-gray-200 pb-2">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            @switch($activity->activity_type)
                                                @case('Added Player')
                                                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                                    </svg>
                                                    @break
                                                @case('Added Team')
                                                    <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    @break
                                                @case('Uploaded a document')
                                                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    @break
                                                @default
                                                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                            @endswitch
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $activity->first_name }} {{ $activity->last_name }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{ $activity->school_name }} - {{ $activity->role }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ $activity->description }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ $activity->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-gray-500 text-center py-4">
                                    No recent activities
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                
                <div class="rounded-lg shadow-md">
                    <div class="bg-green-800 text-white px-4 py-2 rounded-t-lg">
                        <h3 class="text-xl font-bold mb-2">Upcoming Games</h3>
                    </div>
                    <div class="rounded-b-lg p-4 py-2 h-72 overflow-y-auto bg-white border border-gray-300">
                        <ul>
                            @forelse($upcomingGames as $game)
                                <li class="mb-4 border-b border-gray-200 pb-2">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $game['team1_school_name'] }} vs {{ $game['team2_school_name'] }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{ $game['sport_category'] }}
                                            </p>
                                            <div class="flex items-center text-xs text-gray-500 mt-1">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $game['game_date'] }} at {{ $game['start_time'] }}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-gray-500 text-center py-4">
                                    No upcoming games scheduled
                                </li>
                            @endforelse
                        </ul>
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
