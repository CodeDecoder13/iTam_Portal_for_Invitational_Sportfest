  <x-app-layout>
        <div class="p-6">
            <h1 class="font-bold mb-6 text-3xl">Account Settings</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Profile Settings -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Profile Settings</h2>
                    <form id="profileForm" method="POST" action="{{ route('settings.update') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ $user->first_name }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <div class="mb-4">
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ $user->last_name }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ $user->email }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="Coach" {{ $user->role === 'Coach' ? 'selected' : '' }}>Coach</option>
                                <option value="School Representative" {{ $user->role === 'School Representative' ? 'selected' : '' }}>School Representative</option>
                                <option value="Captain" {{ $user->role === 'Captain' ? 'selected' : '' }}>Captain</option>
                            </select>
                        </div>
                    
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                Update Profile
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
                            <input type="password" name="current_password" id="current_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                        data: $(this).serialize(),
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message
                            });
                            submitBtn.prop('disabled', false).text(originalText);
                        },
                        error: function(xhr) {
                            const response = xhr.responseJSON;
                            let errorMessage = 'An error occurred while updating profile.';
                            if (response && response.errors) {
                                errorMessage = Object.values(response.errors).flat().join('\n');
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage
                            });
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
                        url: '{{ route("settings.update-password") }}',
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


            
        
        </script>
        
    </x-app-layout>

