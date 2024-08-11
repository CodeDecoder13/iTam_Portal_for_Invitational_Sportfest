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
        @if ($data['users']->isNotEmpty())
            @foreach ($data['users'] as $user)
                @php
                    $userTeams = $data['teams']->where('coach_id', $user->id);
                    $sports = $userTeams->pluck('sport_category')->unique();
                    $teams = $userTeams->pluck('acronym')->unique();
                @endphp
                <div class="grid grid-cols-12 px-4 py-3 bg-white rounded-lg border mt-3">
                    <div class="col-span-2">{{ $user->created_at->format('Y-m-d H:i:s') }}</div>
                    <div class="col-span-2">{{ $user->first_name }} {{ $user->last_name }}</div>
                    <div class="col-span-2">{{ $sports->isNotEmpty() ? $sports->implode(', ') : 'N/A' }}</div>
                    <div class="col-span-2">{{ $teams->isNotEmpty() ? $teams->implode(', ') : 'N/A' }}</div>
                    <div class="col-span-2" id="status-{{ $user->id }}">
                        <span class="status">{{ $user->is_active ? 'Activate' : 'Deactivate' }}</span>
                    </div>
                    <div class="col-span-1 flex space-x-2">
                        <button class="bg-green-700 text-white px-4 py-2 rounded-lg" onclick="updateStatus({{ $user->id }}, 'activate')">Activate</button>
                        <button class="bg-red-700 text-white px-4 py-2 rounded-lg" onclick="updateStatus({{ $user->id }}, 'deactivate')">Deactivate</button>
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const statusElement = document.querySelector(`#status-${userId} .status`);
                    statusElement.textContent = data.user.is_active ? 'activate' : 'Deactivate';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
</x-app-layout>
