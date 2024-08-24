<x-app-layout>

    <!-- User and Admin Management Section -->
    <div class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">User Management</h1>
        <h3>Manage and Organize Users/Admins</h3>
    </div>


        <li class="w-full flex justify-end items-end">
        
            <button class="btn btn-success h-2/3" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                <sup>+</sup>Add New Admin
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
                <div class="grid grid-cols-12 px-4 py-3 rounded-lg border mt-2">
                    <div class="col-span-3">{{ $admin->created_at->format('F d, Y') }}</div>
                    <div class="col-span-3">{{ $admin->name }}</div>
                    <div class="col-span-3">{{ $admin->email }}</div>
                    <div class="col-span-1">{{ $admin->role }}</div>
                    <div class="col-span-1">{{ $admin->is_active ? 'Active' : 'Inactive' }}</div>
                    <div class="col-span-1">
                        <button class="bg-green-700 text-white px-2 py-1 rounded-lg" data-bs-toggle="modal" data-bs-target="#editAdminModal"
                        data-admin="{{ json_encode($admin) }}">
                            Edit
                        </button>
                        <button class="bg-red-700 text-white px-2 py-1 rounded-lg" data-bs-toggle="modal" data-bs-target="#deleteAdminModal"
                        data-admin="{{ json_encode($admin) }}">
                            Delete
                        </button>
                    </div>
                </div>
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
                                        <input id="name" name="name" type="text" required autofocus autocomplete="name" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('name') }}">
                                    </div>
                                </div>

                                <!-- Email Address -->
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <div class="mt-1">
                                        <input id="email" name="email" type="email" required autocomplete="username" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('email') }}">
                                    </div>
                                </div>

                                <!-- Role -->
                                <div class="mb-4">
                                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                                    <div class="mt-1">
                                        <select id="role" name="role" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="" disabled selected>Select Role</option>
                                            <option value="SysAdmin" {{ old('role') == 'SysAdmin' ? 'selected' : '' }}>SysAdmin</option>
                                            <option value="SADO" {{ old('role') == 'SADO' ? 'selected' : '' }}>SADO</option>
                                            <option value="RAC OFFICER" {{ old('role') == 'RAC OFFICER' ? 'selected' : '' }}>RAC OFFICER</option>
                                            <option value="Guest Admin" {{ old('role') == 'Guest Admin' ? 'selected' : '' }}>Guest Admin</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                    <div class="mt-1">
                                        <input id="password" name="password" type="password" required autocomplete="new-password" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                    <div class="mt-1">
                                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" id="saveAdmin" class="btn btn-primary">Register Admin</button>
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
                                <input type="hidden" id="editAdminId" name="admin_id">
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
                                        <select id="editRole" name="role" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="" disabled selected>Select Role</option>
                                            <option value="SysAdmin">SysAdmin</option>
                                            <option value="SADO">SADO</option>
                                            <option value="RAC OFFICER">RAC OFFICER</option>
                                            <option value="Guest Admin">Guest Admin</option>
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
                                        <input type="password" id="editConfirmPassword" name="password_confirmation" class="form-control">
                                    </div>
                                </div>
                                <!-- Submit Button -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" id="saveAdmin" class="btn btn-primary">Save Admin</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

  
  
  
  

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.x.x/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.esm.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

 
    // added for admin add
    document.getElementById('saveAdmin').addEventListener('click', function() {
        var adminForm = document.getElementById('addAdminForm');
        var formData = new FormData(adminForm);

        var adminData = 
        {
            name: formData.get('name'),
            email: formData.get('email'),
            role: formData.get('role'),
            password: formData.get('password'),
            password_confirmation: formData.get('password_confirmation'),
            is_active: formData.get('is_active')

        };

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
        //added for Edit Admin

            document.getElementById('editAdminForm').addEventListener('submit', function(event) {
                event.preventDefault();
                
                var adminForm = document.getElementById('editAdminForm');
                var formData = new FormData(adminForm);
                var adminData = {
                    adminid: formData.get('admin_id'),
                    name: formData.get('name'),
                    email: formData.get('email'),
                    role: formData.get('role'),
                    password: formData.get('Password'), // Adjusted to match input name
                    confirmPassword: formData.get('Confirm_Password') // Adjusted to match input name
                };

                $.ajaxSetup({
                    headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                            $.ajax({
                                url: '/update-admin',
                                type: 'POST',
                                data: JSON.stringify(adminData),
                                success: function(response) {
                                    alert(response.message);
                                    window.location.href = "{{ route('admin.user-management') }}";
                                },
                                error: function(xhr) {
                                    if (xhr.status === 422) {
                                        // Validation error
                                        var errors = xhr.responseJSON.errors;
                                        var errorMessage = 'Validation Error:\n';
                                        for (var field in errors) {
                                            if (errors.hasOwnProperty(field)) {
                                                errorMessage += errors[field].join('\n') + '\n';
                                            }
                                        }
                                        alert(errorMessage);
                                    } else {
                                        alert('Error saving Admin data');
                                    }
                                }
                            });
                        });

    });

</script>
        
    
</x-app-layout>
