<x-app-layout>
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
    </section>
    <section class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">School Management</h1>
        <h3>Manage and Organize School Players</h3>
        <form method="GET" action="{{ route('admin.school-management') }}" class="grid grid-cols-1 mt-5">
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
            <div class="col-span-3">School Name</div>
            <div class="col-span-3">Coach Name</div>
            <div class="col-span-3">Status</div>
            <div class="col-span-3">Action</div>
        </div>
        @foreach ($teams as $team)
    <div class="grid grid-cols-12 px-4 py-3 rounded-lg border mt-2">
        <div class="col-span-3">{{ $team->coach->school_name ?? 'No School Assigned' }}</div>
        <div class="col-span-3">{{ $team->coach ? $team->coach->first_name . ' ' . $team->coach->last_name : 'No Coach Assigned' }}</div>
        <div class="col-span-3">
            @if($team->coach)
                {{ $team->coach->is_active ? 'Active' : 'Inactive' }}
            @else
                N/A
            @endif
        </div>
        <div class="col-span-3">
            <a href="{{ route('admin.card-school-management', $team->id) }}" class="btn btn-primary">View</a>
        </div>
    </div>
@endforeach

   

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

    
</x-app-layout>
