@foreach($groupedPlayers as $groupKey => $players)
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

    <div class="grid grid-cols-12 px-6 py-4 border-b hover:bg-gray-50">
        <div class="col-span-3">Summary of Players</div>
        <div class="col-span-3">{{ $sportCategory }}</div>
        <div class="col-span-3">{{ $teamName }}</div>
        <div class="col-span-2">
            @switch($status)
                @case('Approved')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Approved
                    </span>
                    @break
                @case('For Review')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        For Review
                    </span>
                    @break
                @case('Rejected')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Rejected
                    </span>
                    @break
                @default
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        No File Attached
                    </span>
            @endswitch
        </div>
        <div class="col-span-1 flex justify-center">
            <a href="{{ route('admin.SummaryOfPlayers', ['type' => 'SummaryOfPlayers', 'sport_category' => $sportCategory, 'name' => $teamName]) }}"
               class="text-green-600 hover:text-green-800 transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </a>
        </div>
    </div>
@endforeach 