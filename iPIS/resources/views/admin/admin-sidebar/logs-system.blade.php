<x-app-layout>
    <div class="p-6">
        <div class="mb-8">
            <h1 class="font-bold text-3xl">Logs Management Dashboard</h1>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Users Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500">Total Users</span>
                    <span class="text-3xl font-bold">{{ $activeUsers }}</span>
                    <span class="text-xs text-gray-500 mt-1">Registered users in the system</span>
                </div>
            </div>

            <!-- Active Admins Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500">Active Admins</span>
                    <span class="text-3xl font-bold">{{ $activeAdmins }}</span>
                    <span class="text-xs text-gray-500 mt-1">Currently active administrators</span>
                </div>
            </div>

            <!-- Current Active Users Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500">Current Active Users</span>
                    <span class="text-3xl font-bold">{{ $currentActiveUsers }}</span>
                    <span class="text-xs text-gray-500 mt-1">Users with active status</span>
                </div>
            </div>

            <!-- Total Players Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500">Total Players</span>
                    <span class="text-3xl font-bold">{{ $totalPlayers }}</span>
                    <span class="text-xs text-gray-500 mt-1">Registered players in teams</span>
                </div>
            </div>

            <!-- Total Teams Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500">Total Teams</span>
                    <span class="text-3xl font-bold">{{ $totalTeams }}</span>
                    <span class="text-xs text-gray-500 mt-1">Active teams in the system</span>
                </div>
            </div>

            <!-- Total Games Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500">Total Games</span>
                    <span class="text-3xl font-bold">{{ $totalGames }}</span>
                    <span class="text-xs text-gray-500 mt-1">Games created in the system</span>
                </div>
            </div>
        </div>

        <!-- Activity Chart and User Profiles -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Activity Chart -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">User Activity (Last 7 Days)</h2>
                <div class="h-[300px]">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

            <!-- User Profiles -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Recent Active Users</h2>
                <div class="space-y-4">
                    @foreach($recentUsers as $user)
                    <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer user-card" 
                         data-user-id="{{ $user->id }}"
                         onclick="showUserDetails({{ $user->id }})">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium">{{ $user->first_name }} {{ $user->last_name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- User Details Modal -->
    <div id="userDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">User Details</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div id="userDetailsContent" class="mb-4">
                    <!-- User details will be populated here -->
                </div>

                <div class="flex flex-col space-y-2">
                    <button id="toggleStatusBtn" class="px-4 py-2 text-white rounded-md"></button>
                    <button onclick="editUser()" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Edit User
                    </button>
                    <button onclick="deleteUser()" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                        Delete User
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Edit User</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="editUserForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">School Name</label>
                        <input type="text" name="school_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Define all functions first
        let currentUserId = null;

        function showUserDetails(userId) {
            currentUserId = userId;
            fetch(`/admin/user-details/${userId}`)
                .then(response => response.json())
                .then(user => {
                    const modal = document.getElementById('userDetailsModal');
                    const content = document.getElementById('userDetailsContent');
                    const statusBtn = document.getElementById('toggleStatusBtn');
                    
                    content.innerHTML = `
                        <div class="space-y-2">
                            <p><strong>Name:</strong> ${user.first_name} ${user.last_name}</p>
                            <p><strong>Email:</strong> ${user.email}</p>
                            <p><strong>School:</strong> ${user.school_name}</p>
                            <p><strong>Role:</strong> ${user.role}</p>
                            <p><strong>Status:</strong> ${user.is_active ? 'Active' : 'Inactive'}</p>
                        </div>
                    `;

                    statusBtn.textContent = user.is_active ? 'Deactivate User' : 'Activate User';
                    statusBtn.className = `px-4 py-2 text-white rounded-md ${user.is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600'}`;
                    statusBtn.onclick = toggleUserStatus;
                    
                    modal.classList.remove('hidden');
                });
        }

        function closeModal() {
            document.getElementById('userDetailsModal').classList.add('hidden');
        }

        function closeEditModal() {
            document.getElementById('editUserModal').classList.add('hidden');
        }

        function toggleUserStatus() {
            const newStatus = document.getElementById('toggleStatusBtn').textContent.includes('Deactivate') ? 0 : 1;
            
            fetch(`/admin/user-status/${currentUserId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        function editUser() {
            fetch(`/admin/user-details/${currentUserId}`)
                .then(response => response.json())
                .then(user => {
                    const form = document.getElementById('editUserForm');
                    form.first_name.value = user.first_name;
                    form.last_name.value = user.last_name;
                    form.email.value = user.email;
                    form.school_name.value = user.school_name;
                    form.role.value = user.role;
                    
                    document.getElementById('editUserModal').classList.remove('hidden');
                });
        }

        function deleteUser() {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`/admin/user-delete/${currentUserId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }

        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Chart
            const ctx = document.getElementById('activityChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($dates),
                    datasets: [{
                        label: 'Active Users',
                        data: @json($userCounts),
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Add event listener to form
            document.getElementById('editUserForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                fetch(`/admin/user-update/${currentUserId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            });

            // Add click handlers to user cards
            document.querySelectorAll('.user-card').forEach(card => {
                card.addEventListener('click', function() {
                    showUserDetails(this.dataset.userId);
                });
            });
        });
    </script>
   
</x-app-layout>