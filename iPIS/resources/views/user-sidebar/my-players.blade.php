
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
        <div class="grid grid-cols-1 mt-5">
            <!-- Header Row -->
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="font-bold col-span-1">Jersey No.</div>
                <div class="font-bold col-span-2">Given Name</div>
                <div class="font-bold col-span-2">Sport Category</div>
                <div class="font-bold col-span-2">Team Name</div>
                <div class="font-bold col-span-2">Gender</div>
                <div class="font-bold col-span-1">Status</div>
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
                    <div class="col-span-2">{{ $player->gender }}</div>
                    <div class="col-span-1">
                        @switch($player->status)
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
                        Edit View
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
                        <div class="mb-3">
                            <label for="teamModal">Select Team</label>
                            <select id="teamModal" name="teamModal" autocomplete="team-name" class="form-control">
                                <option value="" selected>Select Team</option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}"
                                        {{ isset($newTeam) && $newTeam->id === $team->id ? 'selected' : '' }}>
                                        {{ $team->name }} - {{ $team->sport_category }}</option>
                                @endforeach
                            </select>
                        </div>
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
                            <input type="number" id="jersey_no" name="jersey_no" class="form-control" min="1"
                                max="99">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="savePlayer" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

        document.getElementById('savePlayer').addEventListener('click', function() {
            // Get team form data

            var playerForm = document.getElementById('addPlayerForm');
            var formData = new FormData(playerForm);
            var teamData = {
                teamNumber: formData.get('teamModal'),
                firstName: formData.get('firstName'),
                middleName: formData.get('middleName'),
                lastName: formData.get('lastName'),
                birthday: formData.get('birthday'),
                gender: formData.get('gender'),
                jersey_no: formData.get('jersey_no')
            };
            console.log('Submitting team data:', teamData);
            $.ajaxSetup({
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/store-players',
                type: 'POST',
                data: JSON.stringify(teamData),
                success: function(response) {
                    alert(response.message);
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
                        alert('Error saving Team data');
                    }
                }
            });
        });
    </script>
</x-app-layout>
