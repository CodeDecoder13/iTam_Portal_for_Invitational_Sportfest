<x-app-layout>

   
        <h1 class="text-3xl font-bold">Admin Dashboard</h1>
    
        <!-- Fetch Current User Login -->
        @if (Auth::guard('admin')->check())
            <h3 class="text-lg mb-8">
                Welcome, <span class="underline">({{ Auth::guard('admin')->user()->role }}) {{ Auth::guard('admin')->user()->name }}</span>
            </h3>
        @endif
    
        <!-- Right-Aligned Update Patch Button 
        <div class="flex justify-end">
            <button
                class="bg-green-700 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300"
                onclick="toggleModal(true)"
            >
                Update Patch
            </button>
        </div>
   -->

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
    
    
    <section class="container mx-auto p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
       <!-- Recent Documents Activities -->
    <div class="rounded-lg shadow-md">
        <div class="bg-green-800 text-white px-4 py-2 rounded-t-lg">
            <h3 class="text-xl font-bold mb-2">Recent Documents Activities</h3>
        </div>
        <div class="rounded-b-lg p-4 py-2 h-72 overflow-y-auto bg-white border border-gray-300">
            <ul>
                @forelse($recentDocuments as $document)
            <li class="mb-4 border-b border-gray-200 pb-2">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">
                            {{ $document->first_name }} {{ $document->last_name }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ $document->school_name }} - {{ $document->role }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Team: {{ $document->team_name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $document->description }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $document->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </li>
        @empty
            <li class="text-gray-500 text-center py-4">
                No recent document uploads
            </li>
        @endforelse
            </ul>
        </div>
    </div>
        <div class="rounded-lg shadow-md">
            <div class="bg-green-800 text-white px-4 py-2 rounded-t-lg">
                <h3 class="text-xl font-bold mb-2">Recent Activities</h3>
            </div>
            <div class="rounded-b-lg p-4 py-2 h-72 overflow-y-auto bg-white border border-gray-300">
                <ul id="activity-list">
                    @forelse($activities as $activity)
                    <li class="mb-4 border-b border-gray-200 pb-2">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                @switch($activity->activity_type)
                                    @case('Login')
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                        </svg>
                                        @break
                                    @case('Logout')
                                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
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
                <h3 class="text-xl font-bold mb-2">Standing</h3>
            </div>
            <div class="rounded-b-lg p-4 py-2 h-72 overflow-y-auto bg-white border border-gray-300">
                <ol type="1">
                    <li type="1" class="border text-xs flex w-full">
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
                    </li> 
                </ol>
            </div>
        </div>
    </section>
    <!-- Modal -->
    <div id="updatePatchModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden" onclick="toggleModal(false)">
        <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative" onclick="event.stopPropagation()">
            <!-- Close Button -->
            <button
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
                style="font-size: 1.5rem; padding: 0.5rem;"
                onclick="toggleModal(false)"
            >
                &times;
            </button>

            <!-- Modal Content -->
            <h2 class="text-xl font-bold mb-4">System Update Information</h2>
            <p class="text-gray-600">
                Here you will find the latest updates made in the system. This may include changes to functionality, design adjustments, and other relevant information to keep you informed.
            </p>

            <ul class="mt-4 text-gray-600 list-disc pl-5">
                <li>Update 1: Improved user interface for better accessibility.</li>
                <li>Update 2: Added new reporting features to the admin dashboard.</li>
                <li>Update 3: Performance enhancements for faster loading times.</li>
            </ul>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js"></script>\
<script>
    // Function to toggle the modal visibility
    function toggleModal(show) {
        const modal = document.getElementById("updatePatchModal");
        if (show) {
            modal.classList.remove("hidden");
        } else {
            modal.classList.add("hidden");
        }
    }
</script>
<script>
    dayjs.extend(dayjs_plugin_relativeTime);
    
    let lastActivityId = null;

    function getActivityIcon(type) {
        switch(type) {
            case 'Login':
                return `<svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>`;
            case 'Logout':
                return `<svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>`;
            
            default:
                return `<svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>`;
        }
    }

    function fetchActivities() {
        $.ajax({
            url: '/admin/activities',
            method: 'GET',
            success: function(data) {
                if (data.length > 0) {
                    if (lastActivityId === null) {
                        lastActivityId = data[0].id;
                    }

                    $('#activity-list').empty();

            data.forEach(function(activity) {
                // Skip document upload activities
                if (activity.activity_type === 'Uploaded a document') {
                    return;
                }

                var timeAgo = dayjs(activity.created_at).fromNow();
                var icon = getActivityIcon(activity.activity_type);

                $('#activity-list').append(`
                    <li class="mb-4 border-b border-gray-200 pb-2">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                ${icon}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    ${activity.first_name} ${activity.last_name}
                                </p>
                                <p class="text-sm text-gray-600">
                                    ${activity.school_name || 'No school'} - ${activity.role || 'No role'}
                                </p>
                                <p class="text-sm text-gray-500">
                                    ${activity.description}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    ${timeAgo}
                                </p>
                            </div>
                        </div>
                    </li>
                `);

                if (activity.id > lastActivityId) {
                    lastActivityId = activity.id;
                }
            });
                }
            },
            error: function(xhr) {
                console.error('Error fetching activities:', xhr);
            }
        });
    }

    // Initial fetch and set interval
    fetchActivities();
    setInterval(fetchActivities, 5000);
</script>

</x-app-layout>
