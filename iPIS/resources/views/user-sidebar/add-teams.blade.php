<x-app-layout>
    <div class="container my-5">
        <h1 class="text-center mb-4 font-bold text-3xl">Team Details</h1>
        <p class="text-center mb-4">Fill in the required information to create your Team.</p>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form id="team-create-form" method="POST" action="{{ route('store.team') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="sport" class="form-label">Sports</label>
                        <select class="form-select" id="sport" name="sport" required>
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

                    <div class="mb-3">
                        <label for="team-name" class="form-label">Team Name</label>
                        <select class="form-select" id="team-name" name="team_name" required>
                            <option selected disabled>Select Team Name</option>
                            <option value="Team A">Team A</option>
                            <option value="Team B">Team B</option>
                            <option value="Team C">Team C</option>
                            <option value="Team D">Team D</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="team-logo" class="form-label">Please Provide School Logo (max of 25mb)</label>
                        <input type="file" class="form-control" id="team-logo" name="team_logo" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Add Team</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('team-create-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var players = new FormData(this);

    fetch("{{ route('store.team') }}", {
        method: 'POST',
        body: players,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(JSON.stringify(data.errors));
            });
        }
        return response.json();
    })
    .then(data => {
        alert(data.message);
        window.location.href = "{{ route('dashboard') }}";
    })
    .catch(error => {
        console.error('There was an error with the request:', error);
        alert('Validation errors occurred: ' + error.message);
    });
});
    </script>
</x-app-layout>