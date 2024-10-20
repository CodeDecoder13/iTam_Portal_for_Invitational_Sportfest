<x-app-layout>
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
    </section>
    <section class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">My Team Management</h1>
        <h3>Manage and Organize My Team</h3>
        <div class="w-full flex justify-end items-end mb-4">
            <button onclick="openModal()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                Add New Team
            </button>
        </div>
        
        <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border mt-4">
            <div class="col-span-3">Team Name</div>
            <div class="col-span-3">Sport Category</div>
            <div class="col-span-3">Status</div>
            <div class="col-span-3">Action</div>
        </div>
        @foreach ($teams as $team)
            <div class="grid grid-cols-12 px-4 py-3 rounded-lg border mt-2">
                <div class="col-span-3">{{ $team->name }}</div>
                <div class="col-span-3">{{ $team->sport_category }}</div>
                <div class="col-span-3">
                    {{ $team->coach->is_active ? 'Active' : 'Inactive' }}
                </div>
                <div class="col-span-3">
                    <a href="{{ route('team-management', $team->id) }}" class="btn btn-primary">View</a>
                    <a href="javascript:void(0);" class="btn btn-danger" onclick="deleteTeam({{ $team->id }})">Delete</a>

                </div>
                
            </div>
        @endforeach
    </section>

    <!-- Add Team Modal -->
        <div id="addTeamModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Team</h3>
                <div class="mt-2 px-7 py-3">
                    <form id="addTeamForm">
                    @csrf
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

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script> 
     // add team ajax
     $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function openModal() {
            document.getElementById('addTeamModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('addTeamModal').classList.add('hidden');
        }

        $(document).ready(function() {
            $('#addTeamForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('store.myTeam') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if(response.success) {
                            alert('Team added successfully!');
                            closeModal();
                            location.reload(); // Reload the page to show the new team
                        } else {
                            alert('Error adding team. Please try again.');
                        }
                    },
                    error: function(response) {
                        alert('Error adding team. Please try again.');
                        console.log(response);
                    }
                });
            });
        });

    </script>

<script>
    function deleteTeam(teamId) {
        if (confirm("Are you sure you want to delete this team?")) {
            $.ajax({
                url: "{{ route('delete.team') }}",  // URL of the delete route
                type: "DELETE",                   // Method for the request
                data: {
                    id: teamId,                   // Pass the team ID
                    _token: "{{ csrf_token() }}"  // Include CSRF token for security
                },
                success: function(response) {
                    if (response.status === 200) {
                        alert(response.message);
                        // Optionally, you can remove the deleted team from the UI
                        location.reload(); // Refresh the page to update the team list
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('An error occurred while deleting the team.');
                }
            });
        }
    }
</script>

</x-app-layout>