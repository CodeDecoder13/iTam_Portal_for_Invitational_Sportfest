<x-app-layout>
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

    </section>


    <section class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">Coach Management</h1>
        <h3>Manage and Organize Coaches</h3>
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
        <div class="grid grid-cols-1 mt-5">
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="col-span-3">Date Created</div>
                <div class="col-span-3">Name</div>
                <div class="col-span-3">Sport</div>
                <div class="col-span-1">Team</div>
                <div class="col-span-1">Status</div>
                <div class="col-span-1">Action</div>
            </div>
            @php
                $array = $users;
            @endphp
            @foreach ($array as $a)
                <div class="grid grid-cols-12 px-4 py-3 rounded-lg border mt-2">
                    <div class="col-span-3">{{ date_format($a->created_at, 'F d, Y') }}</div>
                    <div class="col-span-3">{{ $a->name }}</div>
                    <div class="col-span-3">???<!--wala namang sport sa registration so ano toh--></div>
                    <div class="col-span-1">???<!--wala namang team sa registration so ano toh--></div>
                    <div class="col-span-1">???<!--wala pang pang verify ng status sa user--></div>
                    <div class="col-span-1">Action</div>
                </div>
            @endforeach
        </div>
    </section>
</x-app-layout>
