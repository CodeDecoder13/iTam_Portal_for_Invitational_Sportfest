<x-app-layout>
    <button 
        class="bg-green-600 text-white font-bold py-2 px-4 rounded-full hover:bg-green-700"
        data-bs-toggle="modal" 
        data-bs-target="#addPlayerModal"
    >
        Add Player
    </button>
    <section class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">Summary Of Players</h1>
        <h3>Fill in player's summary to complete your requirements.</h3>
        
        <div class="grid grid-cols-1 mt-5">
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="font-bold col-span-3">Jersey No.</div>
                <div class="font-bold col-span-3">Given Name</div>
                <div class="font-bold col-span-2">Gender</div>
                <div class="font-bold col-span-2">Status</div>
                <div class="font-bold col-span-2">Action</div>
            </div>
            <div class="player-list">
                @foreach ($players as $player)
                    <div class="grid grid-cols-12 px-4 py-3 bg-white rounded-lg border mt-3">
                        <div class="col-span-3">{{ $player->jersey_no }}</div>
                        <div class="col-span-3">{{ $player->first_name }} {{ $player->last_name }}</div>
                        <div class="col-span-2">{{ $player->gender }}</div>
                        <div class="col-span-2">
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
    </x-app-layout>

    <!-- Add Player Modal -->
      <!-- Modal for Adding Player -->
<div class="modal fade" id="addPlayerModal" tabindex="-1" aria-labelledby="addPlayerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPlayerModalLabel">Player Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addPlayerForm" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="Enter Middle Name">
                    </div>
                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Birth Date</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date" placeholder="Select your Birth Date" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="">Select your Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jersey_no" class="form-label">Jersey No.</label>
                        <input type="text" class="form-control" id="jersey_no" name="jersey_no" placeholder="Type your Jersey No." required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="savePlayer">Add Player</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
