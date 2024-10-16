<x-app-layout>

    <div class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">Coach Approval</h1>
        <h3>Manage and Organize Coach/Captain/School Representative</h3>
    </div>

    <li class="w-full flex justify-end items-end">
        <button class="btn btn-success h-2/3" data-modal-toggle="addUserModal" data-modal-target="addUserModal">
            <sup>+</sup>Add New Coach
        </button>
    </li>

    
   
    <div class="grid grid-cols-12 px-4 py-3 bg-grey-700 text-white rounded-lg border">
        @foreach ($data['users'] as $user) 
            @php
                $userTeams = $data['teams']->where('coach_id', $user->id);
                $sports = $userTeams->pluck('sport_category')->unique();
                $teams = $userTeams->pluck('name')->unique();
            @endphp
            <div class="col-span-4 p-4 relative">
                <div class="bg-white text-black p-4 rounded-lg shadow cursor-pointer relative" 
                onclick="openViewModal({{ $user->is_active ? 'true' : 'false' }}, '{{ $user->first_name }}', '{{ $user->last_name }}', '{{ $user->school_name ?? 'N/A' }}', '{{ $user->role ?? 'N/A' }}', '{{ $sports->isNotEmpty() ? $sports->implode(', ') : 'N/A' }}', '{{ $teams->isNotEmpty() ? $teams->implode(', ') : 'N/A' }}', '{{ $sports->isNotEmpty() ? $sports->implode(', ') : 'N/A' }}', '{{ $teams->isNotEmpty() ? $teams->implode(', ') : 'N/A' }}', '{{ $user->birth_date }}', '{{ $user->gender }}', {{ $user->id }})">
                    
                    <!-- User Info -->
                    <h2 class="font-bold text-2xl mb-4 inline-flex items-center">
                        {{ $user->first_name }} {{ $user->last_name }} 
                        <!-- Conditional Activation Badge -->
                        <span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full ml-2 {{ $user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                            <span class="w-2 h-2 me-1 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            {{ $user->is_active ? 'Activate' : 'Deactivate' }}
                        </span>
                    </h2>
                    <p>{{ $user->role }}</p>
                    <p>{{ $user->school_name }}</p>
                    <p>Email: {{ $user->email }}</p>
    
                    <!-- Kebab Menu Button (moved inside the container) -->
                    <button class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-2xl p-2 kebab-menu" id="kebabMenuButton-{{ $user->id }}">&#x22EE;</button>
                </div>
    
                <!-- Kebab Menu Dropdown (still inside the relative container) -->
                <div id="kebabMenuDropdown-{{ $user->id }}" class="hidden absolute right-4 top-12 bg-white rounded-md shadow-lg z-20 w-40">
                    <ul class="py-1">
                        <li>
                            <!-- Edit Button -->
                        <button class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editUserModal" 
                        data-user-id="{{ $user->id }}" 
                        data-user-firstname="{{ $user->first_name }}" 
                        data-user-lastname="{{ $user->last_name }}" 
                        data-user-email="{{ $user->email }}" 
                        data-user-role="{{ $user->role }}" 
                        data-user-schoolname="{{ $user->school_name }}">
                    Edit
                </button>
                        </li>
                        <li> <button type="button" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-id="{{ $user->id }}">Delete</button></li>
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
  <!-- Modal -->
<div id="userModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-black opacity-50 absolute inset-0"></div>
    <div class="bg-white rounded-lg p-6 z-10 w-11/12 md:w-1/3 relative">
        <button class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-3xl p-2" onclick="closeViewModal()">&times;</button>
        <!-- Name and Role -->
        <h2 class="font-bold text-2xl mb-4 inline-flex items-center" id="modalUserName"></h2> 
        
        <!-- Activation Badge -->
        <span id="activationBadge" class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full ml-2"></span>
        
        <p class="font-semibold">School Name</p>
        <p id="modalUserSchool" class="mb-3"></p>
        
        <p class="font-semibold">Sport Category</p>
        <p id="modalUserSportCategory" class="mb-2"></p>
        
        <p class="font-semibold">Team Name</p>
        <p id="modalUserTeamName" class="mb-2"></p>
        
        <p class="font-semibold">Birth Date</p>
        <p id="modalUserBirthDate" class="mb-2"></p>
        
        <p class="font-semibold">Gender</p>
        <p id="modalUserGender" class="mb-2"></p>

        <div class="flex flex-col">
            <div class="self-end">
                <!-- Change here: Add userId to the buttons -->
                <button class="bg-green-700 hover:bg-green-800 text-white px-2 py-1 rounded-lg mr-2" id="activateButton">Activate</button>
                <button class="bg-red-700 hover:bg-red-800 text-white px-2 py-1 rounded-lg" id="deactivateButton">Deactivate</button>
                
                
            </div>
        </div>
        
    </div>
</div>




  
  <!-- Main modal -->
  <div id="addUserModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      <div class="relative p-4 w-full max-w-md max-h-full">
          <!-- Modal content -->
          <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
              <!-- Modal header -->
              <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                  <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                      Create New Product
                  </h3>
                  <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                      <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                      </svg>
                      <span class="sr-only">Close modal</span>
                  </button>
              </div>
              <!-- Modal body -->
              <form class="p-4 md:p-5">
                  <div class="grid gap-4 mb-4 grid-cols-2">
                      <div class="col-span-2">
                          <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                          <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type product name" required="">
                      </div>
                      <div class="col-span-2 sm:col-span-1">
                          <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                          <input type="number" name="price" id="price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="$2999" required="">
                      </div>
                      <div class="col-span-2 sm:col-span-1">
                          <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                          <select id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                              <option selected="">Select category</option>
                              <option value="TV">TV/Monitors</option>
                              <option value="PC">PC</option>
                              <option value="GA">Gaming/Console</option>
                              <option value="PH">Phones</option>
                          </select>
                      </div>
                      <div class="col-span-2">
                          <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Description</label>
                          <textarea id="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write product description here"></textarea>                    
                      </div>
                  </div>
                  <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                      <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                      Add new product
                  </button>
              </form>
          </div>
      </div>
  </div> 
  

        



        

   
    
        <script type="module" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.esm.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
   function updateStatus(userId, action) {
    console.log(`Updating User ID: ${userId}, Action: ${action}`);
    
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
            const statusElement = document.getElementById('userStatus');
            if (statusElement) {
                statusElement.textContent = action === 'activate' ? 'Active' : 'Inactive';
            }
            alert(data.message);
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

    </script>
<script>
    

    function closeViewModal() {
        document.getElementById('userModal').classList.add('hidden');
    }

    function openViewModal(is_active, firstName, lastName, schoolName, role, sports, teams, sport_category, name, birth_date, gender, userId) {
    // Update Name with Role
    document.getElementById('modalUserName').innerText = `${firstName} ${lastName} (${role})`;
    
    // School, Sport, Team Details
    document.getElementById('modalUserSchool').innerText = schoolName;
    document.getElementById('modalUserSportCategory').innerText = sport_category;
    document.getElementById('modalUserTeamName').innerText = name;
    
    // Birth Date and Gender
    document.getElementById('modalUserBirthDate').innerText = new Date(birth_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    document.getElementById('modalUserGender').innerText = `${gender}`;

    // Activation Badge Logic
    let activationBadge = document.getElementById('activationBadge');
    if (is_active) {
        activationBadge.classList.add('bg-green-100', 'text-green-800', 'dark:bg-green-900', 'dark:text-green-300');
        activationBadge.classList.remove('bg-red-100', 'text-red-800', 'dark:bg-red-900', 'dark:text-red-300');
        activationBadge.innerHTML = `<span class="w-2 h-2 me-1 bg-green-500 rounded-full"></span> Activate`;
    } else {
        activationBadge.classList.add('bg-red-100', 'text-red-800', 'dark:bg-red-900', 'dark:text-red-300');
        activationBadge.classList.remove('bg-green-100', 'text-green-800', 'dark:bg-green-900', 'dark:text-green-300');
        activationBadge.innerHTML = `<span class="w-2 h-2 me-1 bg-red-500 rounded-full"></span> Deactivate`;
    }

    // Set button actions with the correct user ID
    document.getElementById('activateButton').onclick = function() { updateStatus(userId, 'activate'); };
    document.getElementById('deactivateButton').onclick = function() { updateStatus(userId, 'deactivate'); };

    // Show the modal
    document.getElementById('userModal').classList.remove('hidden');

}

</script>
<script>

$(document).ready(function() {
        // Toggle Kebab Menu Dropdown
        $('[id^=kebabMenuButton]').click(function(event) {
            event.stopPropagation(); // Prevent triggering modal when clicking the kebab
            const id = $(this).attr('id').split('-')[1];
            $(`#kebabMenuDropdown-${id}`).toggleClass('hidden');
        });

        // Close dropdown when clicked outside
        $(document).click(function(e) {
            if (!$(e.target).closest('.kebab-menu').length && !$(e.target).closest('[id^=kebabMenuDropdown]').length) {
                $('[id^=kebabMenuDropdown]').addClass('hidden');
            }
        });
    });

    // Edit User modal
    document.querySelectorAll('[data-bs-target="#editUserModal"]').forEach(button => {
    button.addEventListener('click', function () {
        const userId = this.getAttribute('data-user-id');
        const userFirstName = this.getAttribute('data-user-firstname');
        const userLastName = this.getAttribute('data-user-lastname');
        const userEmail = this.getAttribute('data-user-email');
        const userRole = this.getAttribute('data-user-role');
        const userSchoolName = this.getAttribute('data-user-schoolname');
        
        // Populate the modal form
        document.getElementById('edituserid').value = userId;
        document.getElementById('editFirstName').value = userFirstName;
        document.getElementById('editLastName').value = userLastName;
        document.getElementById('editEmail').value = userEmail;
        document.getElementById('editRole').value = userRole;
        document.getElementById('editSchoolName').value = userSchoolName;

        // Set the delete button's data-id attribute
        document.querySelector('#editUserModal .delete-btn').setAttribute('data-id', userId);

        // Clear password fields
        document.getElementById('editPassword').value = '';
        document.getElementById('editConfirmPassword').value = '';
    });
});

        // Update user
        document.getElementById('EditCoach').addEventListener('click', function () {
            var formData = new FormData(document.getElementById('editUserForm')); // Collect form data

            // Append the user ID from the hidden input field in the form to FormData
            var userid = document.getElementById('edituserid').value; // User ID
            formData.append('id', userid); // Append the user ID to formData

            // Debug: Check form data
            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            $.ajax({
                type: 'POST',
                url: '{{ route("admin.users.update") }}',
                data: formData, // Send FormData directly
                contentType: false, // Required for FormData
                processData: false, // Required for FormData
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message); // Show success message
                    window.location.reload(); // Reload page after success
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
                        alert(errorMessage); // Show validation error message
                    } else {
                        console.error('Error updating user data:', xhr);
                        alert('Error updating user data'); // Show error message
                    }
                }
            });
        });

        //delete ajax
        $(document).on('click', '.delete-btn', function() {
    var id = $(this).data('id');
    console.log('Attempting to delete user with ID:', id);

    if (confirm('Are you sure you want to delete this user?')) {
        $.ajax({
            url: '{{ route('delete.coach') }}',
            type: 'DELETE',
            data: { id: id },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Delete response:', response);
                if (response.status === 200) {
                    alert(response.message);
                    // Close the modal
                    $('#editUserModal').modal('hide');
                    // Remove the user row from the table
                    $('div[data-user-id="' + id + '"]').remove();
                    window.location.reload(); // Reload page after success
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Delete error:', xhr.responseText);
                alert('Error: ' + error);
            }
        });
    }
});

</script>



</x-app-layout>