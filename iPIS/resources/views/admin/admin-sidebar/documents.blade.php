<x-app-layout>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"></section>

    <section class="grid grid-cols-1">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="font-bold text-3xl">Document Requirements</h1>
                <p class="text-gray-600">Manage and Organize Team Documents</p>
            </div>
            
            <!-- Search and Filter Container -->
            <div class="flex space-x-4">
                <!-- Search Input -->
                <div class="relative">
                    <input type="text" 
                           placeholder="Search documents..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filter Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.away="open = false"
                         class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg z-50">
                        <div class="p-4">
                            <!-- Sport Category Filter -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sport Category</label>
                                <select name="sport_category" id="sportCategory" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">All Categories</option>
                                    <option value="Boys Basketball Developmental">Boys Basketball Developmental</option>
                                    <option value="Boys Basketball Competitive">Boys Basketball Competitive</option>
                                    <option value="Girls Basketball Developmental">Girls Basketball Developmental</option>
                                    <option value="Girls Basketball Competitive">Girls Basketball Competitive</option>
                                    <option value="Boys Volleyball Developmental">Boys Volleyball Developmental</option>
                                    <option value="Boys Volleyball Competitive">Boys Volleyball Competitive</option>
                                    <option value="Girls Volleyball Developmental">Girls Volleyball Developmental</option>
                                    <option value="Girls Volleyball Competitive">Girls Volleyball Competitive</option>
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" id="statusFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">All Status</option>
                                    <option value="Approved">Approved</option>
                                    <option value="For Review">For Review</option>
                                    <option value="Rejected">Rejected</option>
                                    <option value="No File Attached">No File Attached</option>
                                </select>
                            </div>

                            <!-- Apply Button -->
                            <button id="applyFilter" class="w-full bg-green-600 text-white rounded-lg px-4 py-2 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Table Header -->
            <div class="grid grid-cols-12 px-6 py-4 bg-green-600 text-white font-semibold">
                <div class="col-span-3">Document</div>
                <div class="col-span-3">Sport Category</div>
                <div class="col-span-3">Team Name</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-1 text-center">Action</div>
            </div>

            @php
                // Sort the grouped players by status change priority
                $sortedGroups = $groupedPlayers->sortByDesc(function($players) {
                    foreach ($players as $player) {
                        if ($player->birth_certificate_status != 0 || $player->parental_consent_status != 0) {
                            // Prioritize groups where there's a status other than the default
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
        </div>
    </section>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[placeholder="Search documents..."]');
    const sportCategory = document.getElementById('sportCategory');
    const statusFilter = document.getElementById('statusFilter');
    const applyFilter = document.getElementById('applyFilter');
    const documentsContainer = document.querySelector('.bg-white.rounded-lg.shadow');

    // Function to perform the search and filter
    async function performFilter() {
        try {
            const searchTerm = searchInput.value;
            const sport = sportCategory.value;
            const status = statusFilter.value;

            const response = await fetch(`/admin/filter-documents?search=${searchTerm}&sport_category=${sport}&status=${status}`);
            const data = await response.json();

            if (data.html) {
                // Update only the table body, keeping the header
                const tableHeader = documentsContainer.querySelector('.grid.grid-cols-12.px-6.py-4.bg-green-600');
                documentsContainer.innerHTML = '';
                documentsContainer.appendChild(tableHeader);
                documentsContainer.insertAdjacentHTML('beforeend', data.html);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // Event listeners
    searchInput.addEventListener('input', debounce(performFilter, 300));
    applyFilter.addEventListener('click', performFilter);

    // Debounce function to limit API calls
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
});
</script>
