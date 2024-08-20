<x-app-layout>
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
    </section>
    <section class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">Document Requirements</h1>
        <h3>Manage and Organize Team Documents</h3>
        <div class="grid grid-cols-1 mt-5">
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="col-span-4">Document</div>
                <div class="col-span-4">Last Updated</div>
                <div class="col-span-3">Status</div>
                <div class="col-span-1">Action</div>
            </div>

            <!-- Check if a team is selected -->
            @if (session('selected_team'))
                @php
                    // Fetch team-related data based on selected team ID
                    $team = App\Models\Team::where('id', session('selected_team'))
                        ->where('coach_id', Auth::user()->id)
                        ->first();
                    
                    // Get the last updated date and status from the related players
                    $lastUpdated = $team ? $team->players()->latest()->first()->updated_at ?? 'No Data' : 'No Data';
                    $status = $team ? $team->players()->latest()->first()->status ?? 'No File Attached' : 'No File Attached';
                @endphp
            @else
                @php
                    // Default or fallback data when no team is selected
                    $lastUpdated = 'No Data';
                    $status = 'No Team Selected';
                @endphp
            @endif

            <div class="grid grid-cols-12 px-4 py-3 rounded-lg border mt-2">
                <div class="col-span-4">Summary of Players</div>
                <div class="col-span-4">{{ $lastUpdated }}</div>
                <div class="col-span-3 @switch($status)
                            @case('Approved')
                                text-green-700
                            @break

                            @case('For Review')
                                text-yellow-700
                            @break

                            @case('Rejected')
                                text-red-700
                            @break

                            @case('No File Attached')
                                text-gray-700
                            @break
                        @endswitch font-semibold">
                    {{ $status }}
                </div>
                <div class="col-span-1 text-green-700">
                    <a href="{{ route('my-documents_sub', 'SummaryOfPlayers') }}">
                        <ion-icon name="document"></ion-icon>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
