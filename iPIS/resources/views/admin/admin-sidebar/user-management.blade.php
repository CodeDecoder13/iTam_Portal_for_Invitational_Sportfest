<x-app-layout>
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-bold mb-2 text-3xl">User Management</h1>
            <h3>Manage and Organize Admins Access</h3>
        </div>
        <!-- Add Admin Button -->
        <div class="mt-4 flex justify-end">
            <button class="btn btn-success h-2/3" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                <sup>+</sup>Add New Account
            </button>
        </div>
    </div>

    <div class="p-6">
        <!-- Search Section -->
        <div class="mt-6">
            <div class="relative w-full sm:w-96">
                <input 
                    type="text" 
                    class="pl-10 pr-4 py-2 w-full bg-white rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-300 focus:border-green-300" 
                    placeholder="Search by name, email, or role..."
                    id="searchTerm"
                />
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <div id="searchStatus" class="mt-2 text-sm text-gray-500"></div>
            </div>
        
            <!-- Results container -->
            <div id="searchResults" class="mt-4 space-y-2">
            </div>
        </div>

        <!-- Admins Table -->
        <div id="adminsTable" class="mt-6">
            <div class="space-y-4">
                @foreach ($data['admins'] as $admin)
                @if ($admin->role !== 'SysAdmin')
                    <div class="p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-lg">{{ $admin->name }}</h3>
                                <p class="text-gray-600">{{ $admin->email }}</p>
                                <p class="text-gray-500 text-sm">Created: {{ $admin->created_at->format('F d, Y') }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $admin->role === 'SADO' ? 'bg-blue-100 text-blue-800' : ($admin->role === 'RAC OFFICER' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $admin->role }}
                                </span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $admin->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $admin->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-2 flex justify-end gap-2">
                            <button class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600"
                                onclick="openModal({{ $admin->is_active ? 'true' : 'false' }}, 
                                    '{{ $admin->name }}',
                                    '{{ $admin->email }}',
                                    '{{ $admin->role }}',
                                    '{{ $admin->created_at->format('F d, Y') }}',
                                    {{ $admin->id }})">
                                View Details
                            </button>
                            <button class="px-3 py-1 text-sm bg-gray-500 text-white rounded hover:bg-gray-600"
                                data-bs-toggle="modal"
                                data-bs-target="#editAdminModal"
                                data-admin-id="{{ $admin->id }}"
                                data-admin-name="{{ $admin->name }}"
                                data-admin-email="{{ $admin->email }}"
                                data-admin-role="{{ $admin->role }}">
                                Edit
                            </button>
                        </div>
                    </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div id="userModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="bg-black opacity-50 absolute inset-0"></div>
        <div class="bg-white rounded-lg p-6 z-10 w-11/12 md:w-1/3 relative">
            <button class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-3xl p-2" onclick="closeModal()">&times;</button>
            
            <h2 class="font-bold text-2xl mb-4 inline-flex items-center" id="modalUserName"></h2>
            <span id="activationBadge" class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full ml-2"></span>
            
            <p class="font-semibold">Email</p>
            <p id="modalUserEmail" class="mb-3"></p>
            
            <p class="font-semibold">Role</p>
            <p id="modalUserRole" class="mb-2"></p>
            
            <p class="font-semibold">Created Date</p>
            <p id="modalUserCreated" class="mb-2"></p>

            <div class="flex flex-col">
                <div class="self-end">
                    <button class="bg-green-700 hover:bg-green-800 text-white px-2 py-1 rounded-lg mr-2" id="activateButton">Activate</button>
                    <button class="bg-red-700 hover:bg-red-800 text-white px-2 py-1 rounded-lg" id="deactivateButton">Deactivate</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Admin Modal -->
    <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAdminModalLabel">Add a Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="addAdminForm" method="POST">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <div class="mt-1">
                                <input id="name" name="name" type="text" required autofocus
                                    autocomplete="name"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    value="{{ old('name') }}">
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" required autocomplete="username"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    value="{{ old('email') }}">
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <div class="mt-1">
                                <select id="role" name="role" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="" disabled selected>Select Role</option>
                                    <option value="SADO" {{ old('role') == 'SADO' ? 'selected' : '' }}>SADO</option>
                                    <option value="RAC OFFICER" {{ old('role') == 'RAC OFFICER' ? 'selected' : '' }}>
                                        RAC OFFICER</option>
                                    <option value="Guest Admin" {{ old('role') == 'Guest Admin' ? 'selected' : '' }}>
                                        Guest Admin</option>
                                </select>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" required
                                    autocomplete="new-password"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                                Password</label>
                            <div class="mt-1">
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                    autocomplete="new-password"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="registerAdmin" class="btn btn-primary">Register Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit an Admin Modal -->
    <div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-green-700">
                    <h5 class="modal-title text-white" id="editAdminModalLabel">Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAdminForm">
                        @csrf
                        <input type="hidden" id="adminid" name="adminid" value="">
                        <!-- Name -->
                        <div class="mb-4">
                            <div class="mt-1">
                                <label for="editName" class="form-label">Name</label>
                                <input type="text" id="editName" name="name" class="form-control">
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="mb-4">
                            <div class="mt-1">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="text" id="editEmail" name="email" class="form-control">
                            </div>
                        </div>
                        <!-- Role -->
                        <div class="mb-4">
                            <div class="mt-1">
                                <label for="editRole" class="form-label">Role</label>
                                <select id="editRole" name="role" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="" disabled selected>Select Role</option>
                                    <option value="SADO" {{ old('role') == 'SADO' ? 'selected' : '' }}>SADO</option>
                                    <option value="RAC OFFICER" {{ old('role') == 'RAC OFFICER' ? 'selected' : '' }}>
                                        RAC OFFICER</option>
                                    <option value="Guest Admin" {{ old('role') == 'Guest Admin' ? 'selected' : '' }}>
                                        Guest Admin</option>
                                </select>
                            </div>
                        </div>
                        <!-- Password -->
                        <div class="mb-4">
                            <div class="mt-1">
                                <label for="editPassword" class="form-label">Password</label>
                                <input type="password" id="editPassword" name="password" class="form-control">
                            </div>
                        </div>
                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <div class="mt-1">
                                <label for="editConfirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" id="editConfirmPassword" name="password_confirmation"
                                    class="form-control">
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="EditAdmin" class="btn btn-primary">Save Admin</button>
                            <button type="button" class="btn btn-danger delete-admin-btn" data-id="{{ $admin->id }}">Delete Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.x.x/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.esm.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // added for admin add
        document.getElementById('registerAdmin').addEventListener('click', function() {
            var adminForm = document.getElementById('addAdminForm');
            var formData = new FormData(adminForm);

            var adminData = {
                name: $('#name').val(),
                email: $('#email').val(),
                role: $('#role').val(),
                password: $('#password').val(),
                password_confirmation: $('#password_confirmation').val()
            };
            console.log(adminData);
            $.ajaxSetup({
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/admin/store-admin-accounts',
                type: 'POST',
                data: JSON.stringify(adminData),
                success: function(response) {
                    alert(response.message);
                    window.location.href = "{{ route('admin.user-management') }}";
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = 'Validation Error:\n';
                        for (var field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                errorMessage += errors[field].join('\n') + '\n';
                            }
                        }
                        alert(errorMessage);
                    } else {
                        alert('Error saving user data');
                    }
                }
            });
        });

        //edit modal
        document.querySelectorAll('[data-bs-target="#editAdminModal"]').forEach(button => {
            button.addEventListener('click', function () {
                // Get admin details directly from the button's data attributes
                const adminId = this.getAttribute('data-admin-id');
                const adminName = this.getAttribute('data-admin-name');
                const adminEmail = this.getAttribute('data-admin-email');
                const adminRole = this.getAttribute('data-admin-role');
                
                // Check if this is correctly fetched
                console.log('Admin ID:', adminId); 
                console.log('Admin Name:', adminName);
                console.log('Admin Email:', adminEmail);
                console.log('Admin Role:', adminRole);

                // Populate the hidden input and other form fields
                document.getElementById('adminid').value = adminId;
                document.getElementById('editName').value = adminName;
                document.getElementById('editEmail').value = adminEmail;
                document.getElementById('editRole').value = adminRole;

                // Clear password fields
                document.getElementById('editPassword').value = '';
                document.getElementById('editConfirmPassword').value = '';
            });
        });


        document.getElementById('EditAdmin').addEventListener('click', function () {
            var adminid = document.getElementById('adminid').value;
            console.log(adminid); // Debug: Check if adminid is populated
            var adminForm = document.getElementById('editAdminForm');
            var formData = new FormData(adminForm);

            var adminData = {
                adminid: adminid,
                name: formData.get('name'),
                email: formData.get('email'),
                role: formData.get('role'),
                password: formData.get('password'),
                password_confirmation: formData.get('password_confirmation')
            };

            console.log('Submitting admin data:', adminData); // Debug: Check if adminData includes adminid

            $.ajax({
                url: '{{ route('admin.update.admin') }}',
                type: 'POST',
                data: JSON.stringify(adminData),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    window.location.reload();
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = 'Validation Error:\n';
                        for (var field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                errorMessage += errors[field].join('\n') + '\n';
                            }
                        }
                        alert(errorMessage);
                    } else {
                        console.error('Error updating admin data:', xhr);
                        alert('Error updating admin data');
                    }
                }
            });
        });

            //delete function
            $(document).on('click', '.delete-admin-btn', function() {
                var adminid = $(this).data('id'); // Get the admin ID from the button

                // Confirmation dialog before deletion
                if (confirm('Are you sure you want to delete this admin?')) {
                    $.ajax({
                        url: '{{ route('delete.admin') }}', // Admin deletion route
                        type: 'DELETE', // HTTP method for deletion
                        data: { adminid: adminid }, // Send the admin ID in the request
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token for security
                        },
                        success: function(response) {
                            console.log(response); // Debugging: log the response
                            if (response.status === 200) {
                                alert(response.message); // Show success message
                                window.location.reload();
                                $('button[data-id="' + adminid + '"]').closest('tr').remove();
                            } else {
                                alert(response.message); // Show error message
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr); // Debugging: log the error
                            alert('Error: ' + error); // Show a generic error message
                        }
                    });
                }
            });

        function closeModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        function openModal(is_active, name, email, role, created_date, userId) {
            document.getElementById('modalUserName').innerText = name;
            document.getElementById('modalUserEmail').innerText = email;
            document.getElementById('modalUserRole').innerText = role;
            document.getElementById('modalUserCreated').innerText = created_date;

            let activationBadge = document.getElementById('activationBadge');
            if (is_active) {
                activationBadge.classList.add('bg-green-100', 'text-green-800');
                activationBadge.classList.remove('bg-red-100', 'text-red-800');
                activationBadge.innerHTML = `<span class="w-2 h-2 me-1 bg-green-500 rounded-full"></span> Active`;
            } else {
                activationBadge.classList.add('bg-red-100', 'text-red-800');
                activationBadge.classList.remove('bg-green-100', 'text-green-800');
                activationBadge.innerHTML = `<span class="w-2 h-2 me-1 bg-red-500 rounded-full"></span> Inactive`;
            }

            document.getElementById('activateButton').onclick = function() { updateStatus(userId, 'activate'); };
            document.getElementById('deactivateButton').onclick = function() { updateStatus(userId, 'deactivate'); };

            document.getElementById('userModal').classList.remove('hidden');
        }

        const searchInput = document.getElementById('searchTerm');
        const resultsContainer = document.getElementById('searchResults');
        const searchStatus = document.getElementById('searchStatus');
        const adminsTable = document.getElementById('adminsTable');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = this.value.trim();
            
            if (searchTerm === '') {
                // Show original content, hide search results
                resultsContainer.innerHTML = '';
                adminsTable.style.display = 'block';
                searchStatus.textContent = '';
                return;
            }

            // Hide original content, prepare for search results
            adminsTable.style.display = 'none';
            searchStatus.textContent = 'Searching...';
            
            searchTimeout = setTimeout(() => {
                fetch(`{{ route('admin.search.admins') }}?term=${encodeURIComponent(searchTerm)}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(response => {
                    const data = response.data || [];
                    searchStatus.textContent = `Found ${data.length} results`;

                    if (data.length === 0) {
                        resultsContainer.innerHTML = `
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-gray-500">No results found</p>
                            </div>
                        `;
                        return;
                    }

                    resultsContainer.innerHTML = '';
                    data.forEach(admin => {
                        if (admin.role !== 'SysAdmin') {
                            const resultCard = document.createElement('div');
                            resultCard.className = 'p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow';
                            resultCard.innerHTML = `
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold text-lg">${admin.name}</h3>
                                        <p class="text-gray-600">${admin.email}</p>
                                        <p class="text-gray-500 text-sm">Created: ${new Date(admin.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</p>
                                    </div>
                                    <div class="flex flex-col items-end gap-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full ${
                                            admin.role === 'SADO' ? 'bg-blue-100 text-blue-800' :
                                            admin.role === 'RAC OFFICER' ? 'bg-purple-100 text-purple-800' :
                                            'bg-gray-100 text-gray-800'
                                        }">${admin.role}</span>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full ${
                                            admin.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                        }">${admin.is_active ? 'Active' : 'Inactive'}</span>
                                    </div>
                                </div>
                                <div class="mt-2 flex justify-end gap-2">
                                    <button class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600"
                                        onclick="openModal(${admin.is_active}, 
                                            '${admin.name}',
                                            '${admin.email}',
                                            '${admin.role}',
                                            '${new Date(admin.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}',
                                            ${admin.id})">
                                        View Details
                                    </button>
                                    <button class="px-3 py-1 text-sm bg-gray-500 text-white rounded hover:bg-gray-600"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editAdminModal"
                                        data-admin-id="${admin.id}"
                                        data-admin-name="${admin.name}"
                                        data-admin-email="${admin.email}"
                                        data-admin-role="${admin.role}">
                                        Edit
                                    </button>
                                </div>
                            `;
                            resultsContainer.appendChild(resultCard);
                        }
                    });

                    // Reattach event listeners for edit buttons
                    attachEditButtonListeners();
                })
                .catch(error => {
                    console.error('Error:', error);
                    searchStatus.textContent = 'Error occurred while searching';
                    resultsContainer.innerHTML = `
                        <div class="p-4 bg-red-50 rounded-lg">
                            <p class="text-red-500">An error occurred while searching. Please try again.</p>
                        </div>
                    `;
                });
        }, 300);
    });

    // Function to attach event listeners to edit buttons
    function attachEditButtonListeners() {
        document.querySelectorAll('[data-bs-target="#editAdminModal"]').forEach(button => {
            button.addEventListener('click', function() {
                const adminId = this.getAttribute('data-admin-id');
                const adminName = this.getAttribute('data-admin-name');
                const adminEmail = this.getAttribute('data-admin-email');
                const adminRole = this.getAttribute('data-admin-role');

                document.getElementById('adminid').value = adminId;
                document.getElementById('editName').value = adminName;
                document.getElementById('editEmail').value = adminEmail;
                document.getElementById('editRole').value = adminRole;

                document.getElementById('editPassword').value = '';
                document.getElementById('editConfirmPassword').value = '';
            });
        });
    }

    // Initial attachment of event listeners
    attachEditButtonListeners();
    </script>
</x-app-layout>