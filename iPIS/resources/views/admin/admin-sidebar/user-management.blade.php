<x-app-layout>
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- Your content here -->
    </section>

    <!-- User and Admin Management Section -->
    <section class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">User Management</h1>
        <h3>Manage and Organize Users/Admins</h3>

        <div class="grid grid-cols-1 mt-5">
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="col-span-3">Date Created</div>
                <div class="col-span-3">Name</div>
                <div class="col-span-3">Email</div>
                <div class="col-span-1">Role</div>
                <div class="col-span-1">Status</div>
                <div class="col-span-1">Action</div>
            </div>

            <!-- Loop through Users -->
            @foreach ($data['users'] as $user)
                <div class="grid grid-cols-12 px-4 py-3 rounded-lg border mt-2">
                    <div class="col-span-3">{{ $user->created_at->format('F d, Y') }}</div>
                    <div class="col-span-3">{{ $user->first_name }} {{ $user->last_name }}</div>
                    <div class="col-span-3">{{ $user->email }}</div>
                    <div class="col-span-1">{{ $user->role }}</div>
                    <div class="col-span-1">{{ $user->is_active ? 'Active' : 'Inactive' }}</div>
                    <div class="col-span-1">Action</div>
                </div>
            @endforeach

            <!-- Loop through Admins -->
            @foreach ($data['admins'] as $admin)
                <div class="grid grid-cols-12 px-4 py-3 rounded-lg border mt-2">
                    <div class="col-span-3">{{ $admin->created_at->format('F d, Y') }}</div>
                    <div class="col-span-3">{{ $admin->name }}</div>
                    <div class="col-span-3">{{ $admin->email }}</div>
                    <div class="col-span-1">{{ $admin->role }}</div>
                    <div class="col-span-1">{{ $admin->is_active ? 'Active' : 'Inactive' }}</div>
                    <div class="col-span-1">Action</div>
                </div>
            @endforeach
        </div>
    </section>
</x-app-layout>
