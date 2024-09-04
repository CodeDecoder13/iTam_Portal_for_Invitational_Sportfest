<x-app-layout>
    <section class="grid grid-cols-1 w-full px-4">
        <h1 class="font-bold mb-2 text-3xl">Document Requirements</h1>
        <h3>Manage and Organize Team Documents</h3>
        <div class="grid grid-cols-1 mt-5">
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="font-bold col-span-3">Document</div>
                <div class="font-bold col-span-3">Sport Category</div>
                <div class="font-bold col-span-3">Team Name</div>
                <div class="font-bold col-span-2">Status</div>
                <div class="font-bold col-span-1">Action</div>
            </div>

            @php
                $sortedGroups = $groupedPlayers->sortByDesc(function($players) {
                    foreach ($players as $player) {
                        if ($player->birth_certificate_status != 0 || $player->parental_consent_status != 0) {
                            return 1;
                        }
                    }
                    return 0;
                });
            @endphp

            @foreach($sortedGroups as $groupKey => $players)
                @php
                    list($sportCategory, $teamName) = explode('|', $groupKey);
                    $status = 'No File Attached';

                    foreach ($players as $player) {
                        if ($player->birth_certificate_status == 3 || $player->parental_consent_status == 3) {
                            $status = 'Rejected';
                            break;
                        } elseif ($player->birth_certificate_status == 2 && $player->parental_consent_status == 2) {
                            $status = 'Approved';
                        } elseif ($player->birth_certificate_status == 1 || $player->parental_consent_status == 1) {
                            $status = 'For Review';
                        }
                    }
                @endphp
                <div class="grid grid-cols-12 px-4 py-3 rounded-lg border mt-2">
                    <div class="col-span-3">Summary of Players</div>
                    <div class="col-span-3">{{ $sportCategory }}</div>
                    <div class="col-span-3">{{ $teamName }}</div>
                    <div class="col-span-2">
                        @switch($status)
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
                    <div class="col-span-1 text-green-700">
                        <a href="{{ route('my-documents.sub', ['type' => 'SummaryOfPlayers', 'sport_category' => $sportCategory, 'name' => $teamName]) }}">
                            <ion-icon name="document"></ion-icon>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-app-layout>