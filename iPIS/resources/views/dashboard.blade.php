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
                            <!-- <li class="border text-xs p-2 flex">
                                <div class="text-green-700 text-xl">
                                    <ion-icon name="document"></ion-icon>
                                </div>
                                <div>
                                    <span class="font-bold">Document Comment:</span> "Full name and address doesn’t match ID
                                    details” - RAC Representative"
                                </div>
                            </li> -->
                        </ul>
                    </div>
                </div>
                <div class="rounded-lg shadow-md">
                    <div class="bg-green-800 text-white px-4 py-2 rounded-t-lg">
                        <h3 class="text-xl font-bold mb-2">Upcoming Games</h3>
                    </div>
                    <div class="rounded-b-lg p-4 py-2">
                        <ul>
                            <li class="border text-xs p-2 flex">
                                <div class="w-8/12 border-e-2 flex-grow flex">
                                    <div><img width="15" class="img-fluid" src="/images/userlogo.png" /></div>
                                    <div class="text-center">
                                        <div><span>FITGC</span> VS <span>MPTGC</span></div>
                                        <div class="text-xs">Men’s Basketball (D)</div>
                                    </div>
                                    <div><img width="15" class="img-fluid" src="/images/userlogo.png" /></div>
                                </div>
                                <div class="w-4/12 text-end">
                                    <p class="font-bold">Sept. 11, 2024</p>
                                </div>
                            </li> 
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
