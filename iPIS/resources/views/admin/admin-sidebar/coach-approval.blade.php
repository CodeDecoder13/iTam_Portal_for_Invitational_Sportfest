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
            @foreach ($data['teams'] as $team)
                <div class="grid grid-cols-12 px-4 py-3 bg-white rounded-lg border mt-3">
                    <div class="col-span-2">{{ $team->created_at }}</div>
                    <div class="col-span-2">
                        @foreach ($data['users'] as $user)
                            @if ($user->id == $team->coach_id)
                                {{ $user->first_name }} {{ $user->last_name }}
                            @endif
                        @endforeach
                    </div>
                    <div class="col-span-2">{{ $team->sport_category }}</div>
                    <div class="col-span-2">{{ $team->acronym }}</div>
                    <div class="col-span-2" id="status-{{ $team->coach_id }}">
                        @foreach ($data['users'] as $user)
                            @if ($user->id == $team->coach_id)
                                <span class="status">{{ $user->is_active }}</span>
                            @endif
                        @endforeach
                    </div>
                    <div class="col-span-1 flex space-x-2">
                        @foreach ($data['users'] as $user)
                            @if ($user->id == $team->coach_id)
                                <button class="bg-green-700 text-white px-4 py-2 rounded-lg" onclick="updateStatus({{ $user->id }}, 'activate')">Approve</button>
                                <button class="bg-red-700 text-white px-4 py-2 rounded-lg" onclick="updateStatus({{ $user->id }}, 'deactivate')">Deactivate</button>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center text-gray-500 py-10">No data available.</div>
        @endif
    </div>

</x-app-layout>
