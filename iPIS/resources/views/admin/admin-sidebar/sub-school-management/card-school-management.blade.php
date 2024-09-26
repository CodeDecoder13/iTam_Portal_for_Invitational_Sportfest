<x-app-layout>
    <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">


            <a href="{{ route('admin.school-management', ['id' => $user->id]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Go Back
                </a>
    </div>
        
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-3xl font-semibold mb-2">{{ $user->school_name ?? 'N/A' }}</h2>
            
            <div class="mb-4">
                <p><strong>Name:</strong> {{ $user->first_name . ' ' . $user->last_name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Role:</strong> {{ $user->role ?? 'N/A' }}</p>
                <p><strong>Status:</strong> <span id="userStatus">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></p>
                
                
                
               
            </div>
            
            <div class="flex flex-col">
                
                <div class="self-end">
                    <button class="bg-green-700 hover:bg-green-800 text-white px-2 py-1 rounded-lg mr-2" onclick="updateStatus({{ $user->id }}, 'activate')">Activate</button>
                    <button class="bg-red-700 hover:bg-red-800 text-white px-2 py-1 rounded-lg" onclick="updateStatus({{ $user->id }}, 'deactivate')">Deactivate</button>
                </div>
            </div>
        </div>
        

        <!-- debug this drei nasisisra ui pag naa alis ko to sa side bar -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-8">
            
        
    </div>

        <!-- New cards section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-8">
            <!-- Player Management card -->
            <div class="bg-orange-500 rounded-lg overflow-hidden shadow-lg">
                <div class="flex flex-col h-full">
    
                    <div class="p-5">
                    <div class="text-white text-4xl mb-2">Player Management</div>
                        <!--<h3 class="text-white font-bold mb-2">Player Management</h3> -->
                        <p class="text-white text-sm mb-4">Manage player information with Create, Read, Update, and Delete operations</p>
                        <button class="bg-white text-orange-500 font-bold py-2 px-4 rounded">
                        <a href="{{ route('admin.player-management', ['id' => $user->id]) }}">Player Management</a>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Team Management card -->
            <div class="bg-purple-500 rounded-lg overflow-hidden shadow-lg">
                <div class="p-5">
                    <div class="text-white text-4xl mb-2">Team Management</div>
                   <!-- <h3 class="text-white font-bold mb-2">Team Management</h3> -->
                    <p class="text-white text-sm mb-4">Manage teams and oversee team-related operations efficiently.</p>
                    <button class="bg-white text-purple-500 font-bold py-2 px-4 rounded">
                        <a href="{{ route('admin.team-management', ['id' => $user->id]) }}">View Team</a>
                    </button>
                </div>
            </div>
                
            <!-- Document Management card -->
            <!--
            <div class="bg-blue-500 rounded-lg overflow-hidden shadow-lg">
                <div class="p-5">
                    <div class="text-white text-4xl mb-2">HTML5</div>
                    <h3 class="text-white font-bold mb-2">Document Management</h3>
                    <p class="text-white text-sm mb-4">Manage and organize documents, including player Birth certificate, Parental Consent, and team records.</p>
                    <button class="bg-white text-blue-500 font-bold py-2 px-4 rounded">
                        <a href="{{ route('admin.document-management', ['id' => $user->id]) }}">View Document</a>
                    </button>
                </div>
            </div>
            -->

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