<x-app-layout>
    <div class="p-6">
        <h1 class="font-bold mb-6 text-3xl">Account Settings</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Profile Settings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Profile Settings</h2>
                <form id="profileForm" method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ $admin->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ $admin->email }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        @if($admin->role === 'SysAdmin')
                            <input type="text" name="role" id="role" value="{{ $admin->role }}" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" readonly disabled>
                            <input type="hidden" name="role" value="{{ $admin->role }}">
                        @else
                            <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="SADO" {{ $admin->role === 'SADO' ? 'selected' : '' }}>SADO</option>
                                <option value="RAC Officer" {{ $admin->role === 'RAC Officer' ? 'selected' : '' }}>RAC Officer</option>
                                <option value="Guest Admin" {{ $admin->role === 'Guest Admin' ? 'selected' : '' }}>Guest Admin</option>
                            </select>
                        @endif
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Settings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Change Password</h2>
                <form id="passwordForm">
                    @csrf
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <div class="relative">
                            <input type="password" name="current_password" id="current_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <button type="button" id="togglePassword" class="absolute right-2 top-[60%] transform -translate-y-1/2 px-2 py-1 text-sm text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <button type="button" id="fetchPassword" class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                            Click to Show Current Password
                        </button>
                        <div id="passwordMessage" class="mt-2 text-sm text-gray-600 hidden"></div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <script>
        $(document).ready(function() {
            // Fetch Current Password
            $('#fetchPassword').on('click', function() {
                const currentPassword = $('#current_password').val();
                
                $.ajax({
                    url: '{{ route("admin.settings.get-current-password") }}',
                    type: 'GET',
                    data: {
                        current_password: currentPassword
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            $('#current_password').val(response.password);
                            $('#current_password').attr('type', 'text');
                            $('#togglePassword i').removeClass('fa-eye').addClass('fa-eye-slash');
                            $('#passwordMessage')
                                .removeClass('hidden text-red-600')
                                .addClass('text-green-600')
                                .text('Password revealed successfully');
                        } else {
                            $('#passwordMessage')
                                .removeClass('hidden text-green-600')
                                .addClass('text-red-600')
                                .text(response.message);
                        }
                    },
                    error: function(xhr) {
                        $('#passwordMessage')
                            .removeClass('hidden text-green-600')
                            .addClass('text-red-600')
                            .text('Error fetching password');
                    }
                });
            });

            // Toggle Password Visibility
            $('#togglePassword').on('click', function() {
                const passwordInput = $('#current_password');
                const icon = $(this).find('i');
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Profile Update Form
            $('#profileForm').on('submit', function(e) {
                e.preventDefault();
                
                // Show loading state
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.text();
                submitBtn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message || 'An error occurred while updating settings'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while updating settings';
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('\n');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    },
                    complete: function() {
                        // Reset button state
                        submitBtn.prop('disabled', false).text(originalText);
                    }
                });
            });

            // Password Update Form
            $('#passwordForm').on('submit', function(e) {
                e.preventDefault();
                
                // Show loading state
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.text();
                submitBtn.prop('disabled', true).text('Updating...');
                
                $.ajax({
                    url: '{{ route("admin.settings.update-password") }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            // Show success message and reset form
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message
                            });
                            $('#passwordForm')[0].reset();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while updating password';
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('\n');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    },
                    complete: function() {
                        // Reset button state
                        submitBtn.prop('disabled', false).text(originalText);
                    }
                });
            });
        });
    </script>
    
</x-app-layout>