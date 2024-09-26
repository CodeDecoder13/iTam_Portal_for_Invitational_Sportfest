<x-app-layout>
    <section class="grid grid-cols-1">
        
        <div class="grid grid-cols-2">
            <div class="grid-cols-1">
                <h1 class="font-bold mb-2 text-3xl">Team Management</h1>
                <h3 class="text-gray-600">Manage team information with Create, Read, Update, and Delete operations</h3>
            </div>
            <div class="w-full flex flex-col items-end justify-end space-y-2">

            <a href="{{ route('admin.card-school-management', ['id' => $user->id]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Go Back
                </a>

                <button onclick="openModal()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Add New Team
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 mt-5">
            <!-- Header Row -->
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="font-bold col-span-2">Team Name</div>
                <div class="font-bold col-span-2">Coach Name</div>
                <div class="font-bold col-span-2">Sport Category</div>
                <div class="font-bold col-span-2">Number of Players</div>
                <div class="font-bold col-span-2">Team Status</div>
                <div class="font-bold col-span-2">Action</div>
            </div>
            
            <!-- Team Data Rows -->
            @forelse($teams as $team)
            <div class="grid grid-cols-12 px-4 py-3 border-b">
                <div class="col-span-2">{{ $team->name }}</div>
                <div class="col-span-2">{{ $team->coach->first_name }} {{ $team->coach->last_name }}</div>
                <div class="col-span-2">{{ $team->sport_category ?? 'N/A' }}</div>
                <div class="col-span-2">{{ $players->where('team_id', $team->id)->count() }}</div>
                <div class="col-span-2">{{ $team->status ?? 'N/A' }}</div>
                <div class="col-span-2">
                    
                    <button class="btn btn-danger" onclick="deleteTeam({{ $team->id }})">Delete</button>
                </div>
            </div>
            @empty
            <div class="grid px-4 py-3 bg-white rounded-lg border mt-3">
                No Recorded Teams
            </div>
            @endforelse
        </div>
    </section>
    

    <!-- Add Team Modal -->
    <!-- Add Team Modal -->
<div id="addTeamModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Team</h3>
            <div class="mt-2 px-7 py-3">
                <form id="addTeamForm">
                    <!-- Sport Selection -->
                    <div class="mb-4">
                        <label for="sport" class="block text-sm font-medium text-gray-700">Sport Category</label>
                        <select id="sport" name="sport" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option selected disabled>Select Sport</option>
                            <option value="Boys Basketball Developmental">Boys Basketball Developmental</option>
                            <option value="Boys Basketball Competitive">Boys Basketball Competitive</option>
                            <option value="Girls Basketball Developmental">Girls Basketball Developmental</option>
                            <option value="Boys Volleyball Developmental">Boys Volleyball Developmental</option>
                            <option value="Boys Volleyball Competitive">Boys Volleyball Competitive</option>
                            <option value="Girls Volleyball Developmental">Girls Volleyball Developmental</option>
                            <option value="Girls Volleyball Competitive">Girls Volleyball Competitive</option>
                        </select>
                    </div>

                    <!-- Team Name Selection -->
                    <div class="mb-4">
                        <label for="team-name" class="block text-sm font-medium text-gray-700">Team Name</label>
                        <select id="team-name" name="team_name" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option selected disabled>Select Team Name</option>
                            <option value="Team A">Team A</option>
                            <option value="Team B">Team B</option>
                            <option value="Team C">Team C</option>
                            <option value="Team D">Team D</option>
                        </select>
                    </div>

                    <!-- Team Logo Upload -->
                    <div class="mb-4">
                        <label for="team-logo" class="block text-sm font-medium text-gray-700">School Logo (max 25MB)</label>
                        <input type="file" id="team-logo" name="team_logo" class="mt-1 block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-violet-50 file:text-violet-700
                            hover:file:bg-violet-100
                        ">
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end mt-4">
                        <button type="button" onclick="closeModal()" class="mr-2 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Add Team
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Edit Team Modal -->
    <!-- ... (add your modal code for editing a team) ... -->

    <!-- Script for handling team operations -->
    <script>
        // add team ajax
        function openModal() {
        document.getElementById('addTeamModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('addTeamModal').classList.add('hidden');
    }

    // Add event listener to the "Add New Team" button
    document.querySelector('button[onclick="openModal()"]').addEventListener('click', openModal);

    document.getElementById('addTeamForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        let coachId = {{ $user->id }}; // Assuming you're passing the user object to the view

        fetch(`/admin/store-team/${coachId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                // Handle validation errors
                console.error(data.errors);
                // Display errors to the user (you might want to add this)
                alert('There were errors in your submission. Please check the form and try again.');
            } else {
                // Handle success
                console.log(data.message);
                closeModal();
                // Refresh the team list or update the UI
                location.reload(); // This will refresh the page. You might want to implement a more sophisticated update.
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the form. Please try again.');
        });
    });


        function editTeam(teamId) {
            // Implement your edit functionality here
            console.log('Edit team with ID:', teamId);
        }

        function deleteTeam(teamId) {
    if (confirm('Are you sure you want to delete this team?')) {
        fetch(`/admin/delete-team/${teamId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log(data.message);
            // Remove the team from the UI or refresh the page
            location.reload(); // Or implement a more sophisticated UI update
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the team. Please try again.');
        });
    }
}
    </script>
</x-app-layout>