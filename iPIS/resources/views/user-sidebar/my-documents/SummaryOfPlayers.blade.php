<x-app-layout>
    <section class="grid grid-cols-1">
           <!-- Success and Error Messages -->
           @if (session('success'))
           <div class="alert alert-success mb-4">
               {{ session('success') }}
           </div>
           @endif
   
           @if ($errors->any())
           <div class="alert alert-danger mb-4">
               <ul>
                   @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
                   @endforeach
               </ul>
           </div>
           @endif
   
        <h1 class="font-bold mb-2 text-3xl">Summary Of Players</h1>
        <h3>Fill in player's summary to complete your requirements.</h3>
        <div class="grid grid-cols-1 mt-5">
            <!-- Header row -->
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="col-span-4">Jersey No.</div>
                <div class="col-span-4">Given Name</div>
                <div class="col-span-3">Status</div>
                <div class="col-span-1">Action</div>
            </div>
            <!-- Player rows -->
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
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editPlayerModal" onclick="editPlayer({{ $player->id }}, '{{ $player->first_name }}', '{{ $player->middle_name }}', '{{ $player->last_name }}', '{{ $player->birthday }}', '{{ $player->gender }}', '{{ $player->jersey_no }}', '{{ $player->team_id }}', '{{ $player->status }}')">
                        Edit
                    </button>
                    <form action="{{ route('players.destroy', $player->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <!-- Add Player button -->
        <div class="mt-5 text-center">
            <button type="button" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150" data-bs-toggle="modal" data-bs-target="#addPlayerModal">
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
                <form id="add-player-form" method="POST" action="{{ route('players.store') }}">
                    @csrf
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
                        <input type="date" class="form-control" id="birthday" name="birthday" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jersey_no" class="form-label">Jersey Number</label>
                        <input type="number" class="form-control" id="jersey_no" name="jersey_no" required>
                    </div>
                    <div class="mb-3">
                        <label for="team_id" class="form-label">Team</label>
                        <input type="text" class="form-select" id="team_id" name="team_id" required>
                        <!-- Populate teams dynamically -->
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Add Player</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for editing players -->
<div class="modal fade" id="editPlayerModal" tabindex="-1" aria-labelledby="editPlayerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPlayerModalLabel">Edit Player</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-player-form" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="editFirstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="editFirstName" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editMiddleName" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="editMiddleName" name="middle_name">
                    </div>
                    <div class="mb-3">
                        <label for="editLastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="editLastName" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editBirthday" class="form-label">Birthday</label>
                        <input type="date" class="form-control" id="editBirthday" name="birthday" required>
                    </div>
                    <div class="mb-3">
                        <label for="editGender" class="form-label">Gender</label>
                        <select class="form-select" id="editGender" name="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editJerseyNo" class="form-label">Jersey Number</label>
                        <input type="number" class="form-control" id="editJerseyNo" name="jersey_no" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTeamId" class="form-label">Team</label>
                        <input type="text" class="form-select" id="editTeamId" name="team_id" required>
                        <!-- Populate teams dynamically -->
                    </div>
                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Status</label>
                        <input type="text" class="form-control" id="editStatus" name="status">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update Player</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function editPlayer(id, firstName, middleName, lastName, birthday, gender, jerseyNo, teamId, status) {
    document.querySelector('#edit-player-form').action = `/players/${id}`;
    document.querySelector('#editFirstName').value = firstName;
    document.querySelector('#editMiddleName').value = middleName;
    document.querySelector('#editLastName').value = lastName;
    document.querySelector('#editBirthday').value = birthday;
    document.querySelector('#editGender').value = gender;
    document.querySelector('#editJerseyNo').value = jerseyNo;
    document.querySelector('#editTeamId').value = teamId;
    document.querySelector('#editStatus').value = status;
}
</script>
