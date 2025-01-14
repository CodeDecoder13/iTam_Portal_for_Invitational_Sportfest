<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-4">Game Standing Editor</h2>

                    <!-- Add Standing Form -->
                    <div class="mb-8 bg-gray-50 p-4 rounded-lg">
                        <form method="POST" action="" class="space-y-4">   <!--action="" -->
                            @csrf
                            <div>
                                <label for="school_name" class="block text-sm font-medium text-gray-700">School Name</label>
                                <input type="text" name="school_name" id="school_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="sport_category" class="block text-sm font-medium text-gray-700">Sport Category</label>
                                <input type="text" name="sport_category" id="sport_category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="number_of_wins" class="block text-sm font-medium text-gray-700">Number of Wins</label>
                                <input type="number" name="number_of_wins" id="number_of_wins" value="0" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded-md hover:bg-green-500">Add Standing</button>
                                <button type="button" class="text-gray-700 px-4 py-2 rounded-md border border-gray-300 hover:bg-gray-300">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <!-- Standings Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sport Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wins</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Example row -->
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">1</td>
                                    <td class="px-6 py-4 whitespace-nowrap">feu tech</td>
                                    <td class="px-6 py-4 whitespace-nowrap">basketball</td>
                                    <td class="px-6 py-4 whitespace-nowrap">2</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex gap-2">
                                            <button class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                            <button class="text-red-600 hover:text-red-900">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        
        </script>
</x-app-layout>