<x-app-layout>
    <section class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">Summary Of Players</h1>
        <h3>Fill in player's summary to complete your requirements.</h3>
        <div class="grid grid-cols-1 mt-5">
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="col-span-4">Jersey No.</div>
                <div class="col-span-4">Given Name</div>
                <div class="col-span-3">Status</div>
                <div class="col-span-1">Action</div>
            </div>
            @foreach ($players as $player)
            <div class="grid grid-cols-12 px-4 py-3 bg-white rounded-lg border mt-3">
                <div class="col-span-4">{{ $player->jersey_no }}</div>
                <div class="col-span-4">{{ $player->first_name }} {{ $player->last_name }}</div>
                <div class="col-span-3">
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
                <div class="col-span-1">
                    @if ($player->status == 'For Review')
                     <!-- <a href="{{ route('players.approve', $player->id) }}" class="btn btn-success btn-sm">Approve</a> -->
                    <a href="" class="btn btn-success btn-sm">Approve</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-5 text-center">
            <!-- Button to trigger the modal -->
            <button type="button" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150" data-toggle="modal" data-target="#addPlayerModal" id="addPlayerButton">
                Add Player
            </button>
        </div>
    </section>
</x-app-layout>

<!-- Modal for adding players -->
<div class="modal fade" id="addPlayerModal" tabindex="-1" aria-labelledby="addPlayerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPlayerModalLabel">Add Player</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="player-form">
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="middleName" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middleName" name="middle_name">
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="birthday" class="form-label">Birthday</label>
                        <input type="date" class="form-control" id="birthday" name="birthday">
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jersey_no" class="form-label">Jersey Number</label>
                        <input type="text" class="form-control" id="jersey_no" name="jersey_no" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="save-player-btn" class="btn btn-primary">Save Player</button>
            </div>
        </div>
    </div>
</div>


<script>
var players = [];

    document.getElementById('save-player-btn').addEventListener('click', function() {
        // Get player details from the form
        var firstName = document.getElementById('firstName').value;
        var middleName = document.getElementById('middleName').value;
        var lastName = document.getElementById('lastName').value;
        var birthday = document.getElementById('birthday').value;
        var gender = document.getElementById('gender').value;
        var jersey_no = document.getElementById('jersey_no').value;

        // Validate required fields
        if (!firstName || !lastName || !birthday || !gender || !jersey_no) {
            alert('Please fill in all required fields.');
            return;
        }

        // Create a player object
        var newPlayer = {
            firstName: firstName,
            middleName: middleName,
            lastName: lastName,
            birthday: birthday,
            gender: gender,
            jersey_no: jersey_no
        };

        // Add the new player to the player array
        players.push(newPlayer);

        // Send the player data to the server via AJAX
        $.ajax({
            url: '/store-players',
            type: 'POST',
            data: {
                players: players,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message);
                // Clear the players array after successful save
                players = [];
                // Optionally reload the page or update the UI as needed
                location.reload();
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
                    alert('Error saving player data.');
                }
            }
        });

        // Close the modal
        var modal = bootstrap.Modal.getInstance(document.getElementById('addPlayerModal'));
        modal.hide();

        // Clear the form
        document.getElementById('player-form').reset();
    });

document.getElementById('addPlayerButton').addEventListener('click', function() {
    console.log('Add Player button clicked.');
    $('#addPlayerModal').modal('show');
});

</script>
