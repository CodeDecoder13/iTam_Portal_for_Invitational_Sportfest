<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-xl font-bold text-gray-700 mb-4">Edit User</h1>
            <form action="{{ route('user.update', $user->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="border border-gray-300 rounded p-2 w-full" value="{{ $user->name }}">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="border border-gray-300 rounded p-2 w-full" value="{{ $user->email }}">
                </div>
                <div class="mb-4">
                    <label for="role" class="block text-gray-700">Role</label>
                    <input type="text" name="role" id="role" class="border border-gray-300 rounded p-2 w-full" value="{{ $user->role }}">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
            </form>
        </div>
    </div>
</body>
</html>
