<x-app-layout>
    <section class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">Certificate Of Registration</h1>
        <h3>Upload player's COR to complete your requirements.</h3>
        <div class="grid grid-cols-1 mt-5">
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="col-span-4">Player Name</div>
                <div class="col-span-4">Last Updated</div>
                <div class="col-span-3">Status</div>
                <div class="col-span-1">Action</div>
            </div>
            @foreach ($players as $player)
                <div class="grid grid-cols-12 px-4 py-3 bg-white rounded-lg border mt-3">
                    <div class="col-span-4">{{ $player->first_name }} {{ $player->last_name }}</div>
                    <div class="col-span-4">{{ $player->created_at ? $player->created_at->format('Y-m-d H:i:s') : 'N/A' }}</div>
                    <div class="col-span-3">
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
                    <div class="col-span-1">
                        @if ($player->status == 'For Review')
                            <a href="" class="btn btn-success btn-sm">Approve</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-app-layout>