<x-app-layout>
    <section class="grid grid-cols-1">
        <div class="grid grid-cols-2">
            <div class="grid-cols-1">
                <h1 class="font-bold mb-2 text-3xl">Summary Of Players</h1>
                <h3>Fill in player's summary to complete your requirements.</h3>
            </div>
            
            <div class="w-full flex justify-end items-end">
                <!-- Button to trigger the Bootstrap modal -->
                <button class="btn btn-success h-2/3" data-bs-toggle="modal" data-bs-target="#addPlayerModal">
                    <sup>+</sup>Add New Player
                </button>
            </div>
        </div>
        <div class="w-full flex justify-end mt-4">
            <a href="{{ route('team-management', ['id' => $team->id]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Go Back
            </a>
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
            @if ($players->isEmpty())
                <div class="grid px-4 py-3 bg-white rounded-lg border mt-3">
                    No Recorded Players
                </div>
            @endif
            <!-- Players List -->
            @foreach ($players as $player)
                <div class="grid grid-cols-12 px-4 py-3 bg-white rounded-lg border mt-3">
                    <div class="col-span-1">{{ $player->jersey_no }}</div>
                    <div class="col-span-2">{{ $player->first_name }} {{ $player->last_name }}</div>
                    <div class="col-span-2">{{ $team->sport_category }}</div>
                    <div class="col-span-2">{{ $team->name }}</div>
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
                        <button type="button" id="deletePlayer" class="btn btn-danger btn-sm delete-btn" data-id="{{ $player->id }}">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Bootstrap Modal for Adding Players -->
    <div class="modal fade" id="addPlayerModal" tabindex="-1" aria-labelledby="addPlayerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-green-700">
                    <h5 class="modal-title text-white" id="addPlayerModalLabel">Add New Player</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPlayerForm">
                        @csrf
                        <input type="hidden" name="team_id" value="{{ $team->id }}">
                        <div class="mb-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" id="firstName" name="firstName" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="middleName" class="form-label">Middle Name</label>
                            <input type="text" id="middleName" name="middleName" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" id="lastName" name="lastName" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="birthday" class="form-label">Birthday</label>
                            <input type="date" id="birthday" name="birthday" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select id="gender" name="gender" class="form-select">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jersey_no" class="form-label">Jersey Number</label>
                            <input type="number" id="jersey_no" name="jersey_no" class="form-control" min="1" max="99">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal for Editing Players -->
    <div class="modal fade" id="editPlayerModal" tabindex="-1" aria-labelledby="editPlayerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-blue-700">
                    <h5 class="modal-title text-white" id="editPlayerModalLabel">Edit Player</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPlayerForm">
                        @csrf
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
                            <button type="submit" id="editPlayer" class="btn btn-primary">Save Changes</button>
                            
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
       
        // Add Player Form Submission
        $('#addPlayerForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('store.sub-players') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if(response.success) {
                        alert('Player added successfully!');
                        location.reload();
                    } else {
                        alert('Error adding player. Please try again.');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = 'Validation errors:\n';
                        for (var field in errors) {
                            errorMessage += field + ': ' + errors[field].join(', ') + '\n';
                        }
                        alert(errorMessage);
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        });

        
        // Edit Player Functionality
        $(document).on('click', '[data-bs-toggle="modal"][data-bs-target="#editPlayerModal"]', function (event) {
            var button = $(event.currentTarget);
            var player = button.data('player');
            var modal = $('#editPlayerModal');

            // Fill the form with player data
            modal.find('#editPlayerId').val(player.id);
            modal.find('#editPlayerTeam').val(player.team_id);  // Make sure this line is present
            modal.find('#editFirstName').val(player.first_name);
            modal.find('#editMiddleName').val(player.middle_name);
            modal.find('#editLastName').val(player.last_name);
            modal.find('#editBirthday').val(player.birthday);
            modal.find('#editGender').val(player.gender);
            modal.find('#editJerseyNo').val(player.jersey_no);
        });;

            $('#editPlayerForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            formData += '&_token=' + $('meta[name="csrf-token"]').attr('content');
            
            // Ensure team_id is included
            var teamId = $('#editPlayerTeam').val();
            if (!teamId) {
                alert('Team ID is missing. Please try again.');
                return;
            }
            formData += '&team_id=' + teamId;
            
            $.ajax({
                url: "{{ route('update.sub-players') }}",
                method: 'POST',
                data: formData,
                success: function(response) {
                    if(response.success) {
                        alert('Player updated successfully!');
                        $('#editPlayerModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Error updating player. Please try again.');
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);  // Log the full response for debugging
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = 'Validation errors:\n';
                        for (var field in errors) {
                            errorMessage += field + ': ' + errors[field].join(', ') + '\n';
                        }
                        alert(errorMessage);
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        });     


        // Delete Player Functionality
        $('.delete-btn').on('click', function() {
            if(confirm('Are you sure you want to delete this player?')) {
                var playerId = $(this).data('id');
                $.ajax({
                    url: "{{ route('player.delete') }}",
                    method: 'DELETE',
                    data: {id: playerId, _token: '{{ csrf_token() }}'},
                    success: function(response) {
                        if(response.status === 200) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred. Please try again.');
                    }
                });
            }
        });
    </script>
</x-app-layout>