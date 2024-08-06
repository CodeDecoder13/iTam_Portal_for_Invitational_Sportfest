<x-app-layout>
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold">Coaches Approval</h1>
        <p>Manage and organize coaches</p>
    </div>
    <div class="grid grid-cols-1 mt-5">
        <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
            <div class="col-span-2">Date Created</div>
            <div class="col-span-2">Name</div>
            <div class="col-span-2">Sport</div>
            <div class="col-span-2">Team</div>
            <div class="col-span-2">Status</div>
            <div class="col-span-1">Actions</div>
        </div>
        @if ($data['teams']->isNotEmpty())
            @php
                $coachIds = [];
            @endphp
            @foreach ($data['teams'] as $team)
                @php
                    $coachId = $team->coach_id;
                    if (in_array($coachId, $coachIds)) {
                        continue;
                    }
                    $coachIds[] = $coachId;
                @endphp
                <div class="grid grid-cols-12 px-4 py-3 bg-white rounded-lg border mt-3">
                    <div class="col-span-2">{{ $team->created_at }}</div>
                    <div class="col-span-2">
                        @foreach ($data['users'] as $user)
                            @if ($user->id == $coachId)
                                {{ $user->first_name }} {{ $user->last_name }}
                                @break
                            @endif
                        @endforeach
                    </div>
                    <div class="col-span-2">
                        @php
                            $sports = [];
                            foreach ($data['teams'] as $t) {
                                if ($t->coach_id == $coachId) {
                                    $sports[] = $t->sport_category;
                                }
                            }
                            echo implode(', ', array_unique($sports));
                        @endphp
                    </div>
                    <div class="col-span-2">
                        @php
                            $teams = [];
                            foreach ($data['teams'] as $t) {
                                if ($t->coach_id == $coachId) {
                                    $teams[] = $t->acronym;
                                }
                            }
                            echo implode(', ', array_unique($teams));
                        @endphp
                    </div>
                    <div class="col-span-2" id="status-{{ $coachId }}">
                        @foreach ($data['users'] as $user)
                            @if ($user->id == $coachId)
                                <span class="status">{{ $user->is_active ? 'Verified' : 'Unverified' }}</span>
                                @break
                            @endif
                        @endforeach
                    </div>
                    <div class="col-span-1 flex space-x-2">
                        @foreach ($data['users'] as $user)
                            @if ($user->id == $coachId)
                                <button class="bg-green-700 text-white px-4 py-2 rounded-lg" onclick="updateStatus({{ $user->id }}, 'activate')">Approve</button>
                                <button class="bg-red-700 text-white px-4 py-2 rounded-lg" onclick="updateStatus({{ $user->id }}, 'deactivate')">Deactivate</button>
                                @break
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center text-gray-500 py-10">No data available.</div>
        @endif
    </div>

    <script>
        function updateStatus(userId, action) {
            fetch(`/admin/update-status/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ action: action })
            })
            .then(response => response.text()) // Changed to text() to log the raw response
            .then(text => {
                try {
                    const data = JSON.parse(text); // Attempt to parse the response as JSON
                    if (data.success) {
                        // Update the status display on the page
                        const statusElement = document.querySelector(`#status-${userId} .status`);
                        statusElement.textContent = data.user.is_active ? 'Verified' : 'Unverified';
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    // Log the raw response text
                    console.error('Response was not valid JSON:', text);
                    alert('An error occurred. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
</x-app-layout>