<x-app-layout>
    <div class="min-h-screen bg-gray-100 p-8">
        <!-- Header section with Back button and School name -->
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center space-x-4">
                <div class="relative h-32 w-32"> <!-- Changed size to larger -->
                    @if($team && $team->team_logo)
                        <img 
                            src="{{ Storage::url($team->team_logo) }}"
                            alt="{{ $user->school_name ?? 'School Logo' }}"
                            class="w-full h-full object-contain"
                            onerror="this.src='{{ asset('images/placeholder.png') }}'"
                        />
                    @else
                        <img
                            src="{{ asset('images/placeholder.png') }}"
                            alt="{{ $user->school_name ?? 'School Logo' }}"
                            class="w-full h-full object-contain"
                        />
                    @endif
                </div>
                
                
                
                
                <h1 class="text-2xl font-bold text-gray-900">{{ $user->school_name ?? 'N/A' }}</h1>
            </div>
            <a href="{{ route('admin.school-management', ['id' => $user->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-800 font-semibold tracking-wide hover:bg-gray-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Go Back
            </a>
        </div>

        <!-- School information card -->
        <div class="bg-white shadow-md rounded-md p-8 mb-8">
            <h2 class="text-3xl font-semibold mb-4">School Management Dashboard</h2>
            <p class="text-gray-600 mb-4">Manage school information and access school-related operations.</p>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <p class="font-semibold">Name:</p>
                    <p>{{ $user->first_name . ' ' . $user->last_name }}</p>
                </div>
                <div>
                    <p class="font-semibold">Email:</p>
                    <p>{{ $user->email }}</p>
                </div>
                <div>
                    <p class="font-semibold">Role:</p>
                    <p>{{ $user->role ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold">Status:</p>
                    <p class="inline-flex items-center rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2.5 py-0.5 text-sm font-medium">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </p>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button class="bg-green-700 hover:bg-green-800 text-white px-3 py-1 rounded-lg mr-2" onclick="updateStatus({{ $user->id }}, 'activate')">Activate</button>
                <button class="bg-red-700 hover:bg-red-800 text-white px-3 py-1 rounded-lg" onclick="updateStatus({{ $user->id }}, 'deactivate')">Deactivate</button>
            </div>
        </div>

        <!-- Management cards -->
<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
    <!-- Player Management Card -->
    <div class="bg-orange-300 rounded-lg shadow-lg overflow-hidden">
        <div class="p-5">
            <div class="text-gray-800 text-4xl mb-2">Player Management</div>
            <p class="text-gray-700 text-sm mb-4">Manage player information with Create, Read, Update, and Delete operations</p>
            
                <a href="{{ route('admin.player-management', ['id' => $user->id]) }}"
                    class="bg-white text-orange-500 font-bold py-2 px-4 rounded w-full">
                    Player Management
                </a>
            
        </div>
    </div>

    <!-- Team Management Card -->
    <div class="bg-purple-300 rounded-lg shadow-lg overflow-hidden">
        <div class="p-5">
            <div class="text-gray-800 text-4xl mb-2">Team Management</div>
            <p class="text-gray-700 text-sm mb-4">Manage teams and oversee team-related operations efficiently.</p>
            <a href="{{ route('admin.team-management', ['id' => $user->id]) }}" 
                class="bg-white text-purple-500 font-bold py-2 px-4 rounded w-full inline-block text-center hover:bg-purple-50 transition-colors">
                 View Team
             </a>
        </div>
    </div>

    <!-- Document Management Card  -->
    <div class="bg-blue-300 rounded-lg shadow-lg overflow-hidden">
        <div class="p-5">
            <div class="text-gray-800 text-4xl mb-2">Document Management</div>
            <p class="text-gray-700 text-sm mb-4">Manage and organize documents, including player Birth certificate, Parental Consent, and team records.</p>
             
                <a href="{{ route('admin.document-management', ['id' => $user->id]) }}"
                    class="bg-white text-blue-500 font-bold py-2 px-4 rounded w-full"
                    >View Document</a>
            
        </div>
    </div> -->

            <!-- Logs card -->
             <!--
            <div class="bg-green-500 rounded-lg overflow-hidden shadow-lg">
                <div class="p-5">
                    <div class="text-white text-4xl mb-2">JS</div>
                    <h3 class="text-white font-bold mb-2">Logs</h3>
                    <p class="text-white text-sm mb-4">View and analyze user activity logs, including login history, document uploads, and system interactions.</p>
                    <button class="bg-white text-green-500 font-bold py-2 px-4 rounded">
                        <a href="{{ route('admin.logs-management', ['id' => $user->id]) }}">View Logs</a>
                    </button>
                </div>
            </div>
            -->

        </div>
    </div>

    <script>
        function updateStatus(userId, action) {
            fetch(`/admin/update-status/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ action: action })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the status display
                    const statusElement = document.getElementById('userStatus');
                    if (statusElement) {
                        statusElement.textContent = action === 'activate' ? 'Active' : 'Inactive';
                    }
                    alert(data.message);
                    // Instead of redirecting, we'll reload the current page
                    location.reload();
                } else {
                    alert('Failed to update status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating status');
            });
        }

        // Function to periodically check for updates
        function checkForUpdates() {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const newDoc = parser.parseFromString(html, 'text/html');
                    const newStatus = newDoc.getElementById('userStatus');
                    const currentStatus = document.getElementById('userStatus');
                    
                    if (newStatus && currentStatus && newStatus.textContent !== currentStatus.textContent) {
                        currentStatus.textContent = newStatus.textContent;
                    }
                });
        }

        // Check for updates every 5 seconds
        setInterval(checkForUpdates, 5000);
    </script>
</x-app-layout>