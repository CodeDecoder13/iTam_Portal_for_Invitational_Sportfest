
<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-bold text-gray-700">Users</h1>
                <button class="bg-blue-500 text-white px-4 py-2 rounded">Add user</button>
            </div>

            <div class="mb-4 flex justify-between items-center">
                <div>
                    <label for="entries" class="mr-2">Show</label>
                    <select id="entries" class="border border-gray-300 rounded p-2">
                        <option value="Admin">Admin</option>
                        <option value="Coach">Coach</option>
                    </select>
                    <span class="ml-2">category</span>
                </div>
                <div>
                    <input type="text" class="border border-gray-300 rounded p-2" placeholder="Search">
                </div>
            </div>
            <table class="w-full border-collapse bg-white text-left">
                <thead>
                    <tr>
                        <th class="border-b py-2 px-4">NAME</th>
                        <th class="border-b py-2 px-4">EMAIL</th>
                        <th class="border-b py-2 px-4">ROLE</th>
                        <th class="border-b py-2 px-4">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allUsers as $user)
                    <tr>
                        <td class="border-b py-2 px-4">{{ $user->name }}</td>
                        <td class="border-b py-2 px-4">{{ $user->email }}</td>
                        <td class="border-b py-2 px-4">{{ $user->role }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 flex justify-between items-center">
                <p class="text-gray-600">Showing {{ $allUsers->count() }} entries</p>
                <div class="flex items-center">
                    <button class="border border-gray-300 p-2 rounded-l">Previous</button>
                    <button class="border-t border-b border-gray-300 p-2">1</button>
                    <button class="border border-gray-300 p-2 rounded-r">Next</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
