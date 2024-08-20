
<x-app-layout>
    <section class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">Gallery Of Players</h1>
        <h3>Fill in player's summary to complete your requirements.</h3>
        <div class="grid grid-cols-1 mt-5">
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="font-bold col-span-1">Jersey No.</div>
                <div class="font-bold col-span-2">Given Name</div>
                <div class="font-bold col-span-2">Sport Category</div>
                <div class="font-bold col-span-2">Team Name</div>
                <div class="font-bold col-span-2">Gender</div>
                <div class="font-bold col-span-1">Status</div>
                <div class="font-bold col-span-2">Action</div>
            </div>
        @foreach ($players as $player)
            <div class="grid grid-cols-12 px-4 py-3 bg-white rounded-lg border mt-3">
            <div class="col-span-1">{{ $player->jersey_no }}</div>
            <div class="col-span-2">{{ $player->first_name }} {{ $player->last_name }}</div>
            <div class="col-span-2">{{ $player->team->sport_category }}</div> 
            <div class="col-span-2">{{ $player->team->name }}</div> 
            <div class="col-span-2">{{ $player->gender }}</div>
            <div class="col-span-1">
                @switch($player->status)
                    @case('Approved')
                        <span class="text-green-500">Approved</span>
                        @break
                    @case('For Review')
                        <span class="text-yellow-500">For Review</span>
                        @break
                    @case('Rejected')
                        <span class="text-red-500">Rejected</span>
                        @break
                    @case('No File Attached')
                        <span class="text-gray-500">No File Attached</span>
                        @break
                @endswitch
            </div>
            <div class="col-span-2">
                Edit View 
            </div>
        </div>
    @endforeach

            </div>
        </section>
    </x-app-layout>
    