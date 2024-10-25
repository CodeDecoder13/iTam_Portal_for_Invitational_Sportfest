
<x-app-layout>
    <section class="grid grid-cols-1">
        <div class="grid grid-cols-2">
            <div class="grid-cols-1">
                <h1 class="font-bold mb-2 text-3xl">Summary Of Players</h1>
                <h3>Fill in player's summary to complete your requirements.</h3>
            </div>
            <div class="w-full flex justify-end items-end">
                <!-- Button to trigger the Bootstrap modal -->
                <button class="bg-green-700 text-white rounded-md px-4 py-2" onclick="document.getElementById('addPlayerModal').classList.remove('hidden')">
                    <sup>+</sup>Add New Player
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 mt-5">
            <!-- Header Row -->
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="font-bold col-span-1">Jersey No.</div>
                <div class="font-bold col-span-2">Given Name</div>
                <div class="font-bold col-span-2">Sport Category</div>
                <div class="font-bold col-span-2">Team Name</div>
                <div class="font-bold col-span-1">Gender</div>
                <div class="font-bold col-span-2">Status</div>
                <div class="font-bold col-span-2">Action</div>
            </div>
            <!-- No Players -->
            @if ($players == '[]')
                <div class="grid px-4 py-3 bg-white rounded-lg border mt-3">
                    No Recorded Players
                </div>
            @endif
            <!-- Players List -->
            @foreach ($players as $player)
                <div class="grid grid-cols-12 px-4 py-3 bg-white rounded-lg border mt-3">
                    <div class="col-span-1">{{ $player->jersey_no }}</div>
                    <div class="col-span-2">{{ $player->first_name }} {{ $player->last_name }}</div>
                    <div class="col-span-2">{{ $player->team->sport_category }}</div>
                    <div class="col-span-2">{{ $player->team->name }}</div>
                    <div class="col-span-1">{{ $player->gender }}</div>
                    <div class="col-span-2">
                        @php
                            $status = 'No File Attached'; // Default status

                            if ($player->birth_certificate_status == 3 || $player->parental_consent_status == 3) {
                                $status = 'Rejected';
                            } elseif ($player->birth_certificate_status == 2 && $player->parental_consent_status == 2) {
                                $status = 'Approved';
                            } elseif ($player->birth_certificate_status == 1 || $player->parental_consent_status == 1) {
                                $status = 'For Review';
                            }
                        @endphp

                        @switch($status)
                            @case('Approved')
                                <span class="text-green-500">Approved</span>
                            @break

                            @case('For Review')
                                <span class="text-yellow-500">For Review</span>
                            @break

                            @case('Rejected')
                                <span class="text-red-500">Rejected</span>
                            @break

                            @case('No File Attached')
                                <span class="text-gray-500">No File Attached</span>
                            @break
                        @endswitch
                    </div>
                    
                    <div class="col-span-2">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editPlayerModal" 
                                data-player="{{ json_encode($player) }}">
                            Edit
                        </button>
                        <button type="button" id="deletePlayer" class="btn btn-danger btn-sm delete-btn" data-id="{{ $player->id }}"><i class="fas fa-trash-alt"></i> Delete</button>
                         </div>
                </div>
            @endforeach

        </div>
    </section>

    <!-- Tailwind Modal for Adding Players -->
<div class="fixed inset-0 flex items-center justify-center z-50 hidden" id="addPlayerModal">
    <div class="bg-white rounded-lg shadow-lg w-96"> <!-- Adjusted width to 96 (24rem) -->
        <div class="p-4">
            <h5 class="text-lg font-bold mb-4" id="addPlayerModalLabel">Player Details</h5>
            <form id="addPlayerForm">
                <div class="mb-4">
                    <label for="teamModal" class="block text-sm font-medium text-gray-700">Select Team</label>
                    <select id="teamModal" name="teamModal" autocomplete="team-name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="" selected>Select Team</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}"
                                {{ isset($newTeam) && $newTeam->id === $team->id ? 'selected' : '' }}>
                                {{ $team->name }} - {{ $team->sport_category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4 flex">
                    <div class="w-1/2 pr-2">
                        <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" id="firstName" name="firstName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" placeholder="Enter First Name">
                    </div>
                    <div class="w-1/2 pl-2">
                        <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" id="lastName" name="lastName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" placeholder="Enter Last Name">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="middleName" class="block text-sm font-medium text-gray-700">Middle Name</label>
                    <input type="text" id="middleName" name="middleName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" placeholder="Enter Middle Name">
                </div>
                <div class="mb-4">
                    <label for="birthday" class="block text-sm font-medium text-gray-700">Birth Date</label>
                    <input type="date" id="birthday" name="birthday" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>
                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="" disabled selected>Select your Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="jersey_no" class="block text-sm font-medium text-gray-700">Jersey No.</label>
                    <input type="number" id="jersey_no" name="jersey_no" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" placeholder="Type your Jersey No." min="1" max="99">
                </div>
                <div class="flex justify-between">
                    <button type="button" class="bg-gray-300 text-gray-700 rounded-md px-4 py-2" onclick="document.getElementById('addPlayerModal').classList.add('hidden')">Cancel</button>
                    <button type="button" id="savePlayer" class="bg-green-700 text-white rounded-md px-4 py-2">Add Player</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <div class="modal fade" id="editPlayerModal" tabindex="-1" aria-labelledby="editPlayerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-blue-700">
                    <h5 class="modal-title text-white" id="editPlayerModalLabel">Edit Player</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPlayerForm">
                        <input type="hidden" id="editPlayerId" name="player_id">
                        <input type="hidden" id="editPlayerTeam" name="team_id">
                        <div class="mb-3">
                            <label for="editFirstName" class="form-label">First Name</label>
                            <input type="text" id="editFirstName" name="firstName" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="editMiddleName" class="form-label">Middle Name</label>
                            <input type="text" id="editMiddleName" name="middleName" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="editLastName" class="form-label">Last Name</label>
                            <input type="text" id="editLastName" name="lastName" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="editBirthday" class="form-label">Birthday</label>
                            <input type="date" id="editBirthday" name="birthday" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="editGender" class="form-label">Gender</label>
                            <select id="editGender" name="gender" class="form-select">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editJerseyNo" class="form-label">Jersey Number</label>
                            <input type="number" id="editJerseyNo" name="jersey_no" class="form-control" min="1" max="99">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="editPlayer" class="btn btn-primary">Save Changes</button>
                            
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Script for AJAX Form Submission -->
    <script>
        /*document.getElementById('addPlayerForm').addEventListener('submit', function(event) {
                    event.preventDefault();

                    const player = new FormData(this);

                    fetch('{{ route('store.players') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: player
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.errors) {
                                alert('Failed to save players. Please check your inputs.');
                            } else {
                                alert('Player added successfully!');
                                location.reload(); // Reload to show the new player
                            }
                        })
                        .catch(error => {
                            alert('An error occurred. Please try again.');
                            console.error('Error:', error);
                        });
                });*/

                $(document).ready(function() {
        // Set up CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#savePlayer').on('click', function() {
            // Gather form data
            var formData = {
                teamNumber: $('#teamModal').val(),
                firstName: $('#firstName').val(),
                middleName: $('#middleName').val(),
                lastName: $('#lastName').val(),
                birthday: $('#birthday').val(),
                gender: $('#gender').val(),
                jersey_no: $('#jersey_no').val(),
            };

            // Send AJAX request
            $.ajax({
                url: '/store-players', // Adjust the URL to your route
                type: 'POST',
                data: formData,
                success: function(response) {
                    alert('Player added successfully!');
                    $('#addPlayerModal').addClass('hidden'); // Hide the modal
                    $('#addPlayerForm')[0].reset(); // Reset the form
                },
                error: function(xhr) {
                    if (xhr.status === 419) {
                        alert('Session expired. Please refresh the page and try again.');
                        location.reload(); // Reload the page
                    } else if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = 'Validation Error:\n';
                        for (var field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                errorMessage += errors[field].join('\n') + '\n';
                            }
                        }
                        alert(errorMessage);
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        });
    });

        document.querySelectorAll('[data-bs-target="#editPlayerModal"]').forEach(button => {
            button.addEventListener('click', function() {
                const player = JSON.parse(this.getAttribute('data-player'));
                document.getElementById('editPlayerId').value = player.id;
                document.getElementById('editPlayerTeam').value = player.team_id;
                document.getElementById('editFirstName').value = player.first_name;
                document.getElementById('editMiddleName').value = player.middle_name || '';
                document.getElementById('editLastName').value = player.last_name;
                document.getElementById('editBirthday').value = player.birthday;
                document.getElementById('editGender').value = player.gender;
                document.getElementById('editJerseyNo').value = player.jersey_no;
            });
        });


        document.getElementById('editPlayer').addEventListener('click', function() {
            // Get team form data

            var playerForm = document.getElementById('editPlayerForm');
            var formData = new FormData(playerForm);
            var teamData = {
                playerid: formData.get('player_id'),
                teamNumber: formData.get('team_id'),
                firstName: formData.get('firstName'),
                middleName: formData.get('middleName'),
                lastName: formData.get('lastName'),
                birthday: formData.get('birthday'),
                gender: formData.get('gender'),
                jersey_no: formData.get('jersey_no')
            };
            console.log('Submitting player data:', teamData);
            $.ajaxSetup({
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/update-players',
                type: 'POST',
                data: JSON.stringify(teamData),
                success: function(response) {
                    alert(response.message);
                    window.location.href = "{{ route('my-players') }}";
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
                        alert('Error saving Player data');
                    }
                }
            });
        });

        // for delete player
        $(document).on('click', '.delete-btn', function() {
    var playerId = $(this).data('id'); // Get the player ID from the button
    if (confirm('Are you sure you want to delete this player?')) {
        $.ajax({
            url: '{{ route('player.delete') }}', // Route for deleting player
            type: 'DELETE',
            data: { id: playerId }, // Player ID being sent to the server
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token for security
            },
            success: function(response) {
                if (response.status === 200) {
                    alert(response.message); // Show success message
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert(response.message); // Show error message if any
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error); // Show generic error message
            }
        });
    }
});


         

    </script>
</x-app-layout>
