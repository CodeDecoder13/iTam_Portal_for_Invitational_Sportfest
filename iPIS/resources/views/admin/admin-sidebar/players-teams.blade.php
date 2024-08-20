<x-app-layout>
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
    </section>
    <section class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">Team and Players</h1>
        <h3>Manage and Organize Teams</h3>
        <form method="GET" action="{{ route('admin.players-teams') }}" class="grid grid-cols-1 mt-5">
            <div class="grid grid-cols-12 gap-4 px-4 py-3 rounded-lg bg-gray-100">
                <div class="col-span-5">
                    <input type="text" name="search" placeholder="Search" value="{{ request('search') }}"
                        class="w-8/12 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-span-1 flex items-center">Filtered By:</div>
                <div class="col-span-2">
                    <select name="sport"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sports</option>
                        <!-- Add options dynamically or statically here -->
                    </select>
                </div>
                <div class="col-span-2">
                    <select name="team"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Team</option>
                        <!-- Add options dynamically or statically here -->
                    </select>
                </div>
                <div class="col-span-2">
                    <select name="status"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Status</option>
                        <!-- Add options dynamically or statically here -->
                    </select>
                </div>
            </div>
            <button type="submit" class="hidden"></button>
        </form>
        
        <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
            <div class="col-span-3">Team Name</div>
            <div class="col-span-3">Sports</div>
            <div class="col-span-3">Document Status</div>
            <div class="col-span-1">Action</div>
        </div>
        @foreach ($teams as $team)
            <div class="grid grid-cols-12 px-4 py-3 rounded-lg border mt-2">
                <div class="col-span-3">{{ $team->name }}</div>
                <div class="col-span-3">{{ $team->sport_category }}</div>
                <div class="col-span-3">???</div>
                <div class="col-span-1">
                    <button class="view-button btn btn-primary" data-bs-toggle="modal" data-bs-target="#teamModal" data-id="{{ $team->id }}">View</button>
                </div>
            </div>
        @endforeach

        <div class="mt-4">
            {{ $teams->links() }}
        </div>
    </section>

    <div class="modal fade" id="teamModal" tabindex="-1" aria-labelledby="teamModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="teamModalLabel">Team Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Team details will be populated here -->
                    <div id="team-details"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.view-button').click(function() {
                var teamId = $(this).data('id');
                $.ajax({
                    url: '/admin/teams/' + teamId,
                    method: 'GET',
                    success: function(response) {
                        var team = response.team;
                        var coach = response.coach;
                        var players = response.players;

                        var teamDetails = `
                        <h3>Team Information</h3>
                        <p><strong>Name:</strong> ${team.name}</p>
                        <p><strong>Acronym:</strong> ${team.acronym}</p>
                        <p><strong>Sport Category:</strong> ${team.sport_category}</p>
                        <p><strong>Coach:</strong> ${coach.first_name} ${coach.last_name} (${coach.email}) </p>
                        
                        <h3 class="font-bold">Players</h3>
                        <ul>
                    `;

                        players.forEach(function(player) {
                            teamDetails += `
                            <li>${player.jersey_no} ${player.first_name} ${player.middle_name} ${player.last_name}, ${player.gender}, ${player.birthday}</li>
                        `;
                        });

                        teamDetails += '</ul>';

                        $('#team-details').html(teamDetails);
                        $('#teamModal').modal('show');
                    }
                });
            });
        });
    </script>
</x-app-layout>
