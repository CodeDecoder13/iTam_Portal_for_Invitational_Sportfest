<x-app-layout>

    <!-- User and Admin Management Section -->
    <div class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">User Management</h1>
        <h3>Manage and Organize Users/Admins</h3>
    </div>


    <li class="w-full flex justify-end items-end">

        <button class="btn btn-success h-2/3" data-bs-toggle="modal" data-bs-target="#addAdminModal">
            <sup>+</sup>Add New Account
        </button>
    </li>


    <div class="grid grid-cols-1 mt-5">
        <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
            <div class="col-span-3">Date Created</div>
            <div class="col-span-3">Name</div>
            <div class="col-span-3">Email</div>
            <div class="col-span-1">Role</div>
            <div class="col-span-1">Status</div>
            <div class="col-span-1">Action</div>
        </div>

        <!-- Loop through Admins -->
        @foreach ($data['admins'] as $admin)
        @if ($admin->role !== 'SysAdmin')
            <div class="grid grid-cols-12 px-4 py-3 rounded-lg border mt-2">
                <div class="col-span-3">{{ $admin->created_at->format('F d, Y') }}</div>
                <div class="col-span-3">{{ $admin->name }}</div>
                <div class="col-span-3">{{ $admin->email }}</div>
                <div class="col-span-1">{{ $admin->role }}</div>
                <div class="col-span-1">{{ $admin->is_active ? 'Active' : 'Inactive' }}</div>
                <div class="col-span-1">
                    <button class="bg-green-700 text-white px-2 py-1 rounded-lg" data-bs-toggle="modal"
                        data-bs-target="#editAdminModal" data-admin-id="{{ $admin->id }}"
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

            

                
    </script>


</x-app-layout>