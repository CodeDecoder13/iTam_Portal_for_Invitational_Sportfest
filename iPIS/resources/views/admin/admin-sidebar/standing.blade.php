<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-4">Game Standing Editor</h2>

                    <!-- Add Standing Form -->
                    <div class="mb-8 bg-gray-50 p-4 rounded-lg">
                        <form method="POST" action="{{ route('admin.standings.store') }}" class="space-y-4">   
                            @csrf
                            <div>
                                <label for="sport_category" class="block text-sm font-medium text-gray-700">Sport Category</label>
                                <select name="sport_category" id="sport_category" class="mt-1 block w-full rounded-md border-gray-300">
                                    <option value="">Select Sport Category</option>
                                    @foreach($sportCategories as $category)
                                        <option value="{{ $category }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="team_id" class="block text-sm font-medium text-gray-700">School Name</label>
                                <select name="team_id" id="team_id" class="mt-1 block w-full rounded-md border-gray-300" disabled>
                                    <option value="">Select a sport category first</option>
                                </select>
                            </div>

                            <div>
                                <label for="wins" class="block text-sm font-medium text-gray-700">Number of Wins</label>
                                <input type="number" name="wins" id="wins" value="0" min="0" class="mt-1 block w-full rounded-md border-gray-300">
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                    Add Standing
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Standings Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">School</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sport Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Wins</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($standings->sortByDesc('wins')->groupBy('sport_category') as $category => $categoryStandings)
                                    @foreach($categoryStandings as $index => $standing)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $standing->team->coach->school_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $standing->sport_category }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $standing->wins }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button 
                                                    class="edit-standing bg-blue-500 text-white px-2 py-1 rounded" 
                                                    data-id="{{ $standing->id }}">
                                                    Edit
                                                </button>

                                                <button 
                                                    class="delete-standing bg-red-500 text-white px-2 py-1 rounded" 
                                                    data-id="{{ $standing->id }}">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Edit Modal -->
                    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
                        <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-1/3 mx-auto mt-20">
                            <h3 class="text-lg font-bold mb-4">Edit Standing</h3>
                            <form id="editForm">
                                @csrf
                                <input type="hidden" id="editStandingId">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Wins</label>
                                    <input type="number" id="editWins" class="mt-1 block w-full rounded-md border-gray-300">
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
                                    <button type="button" class="close-modal bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
       $(document).ready(function() {
    // Add CSRF token to all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#sport_category').on('change', function() {
        const sportCategory = $(this).val();
        const teamSelect = $('#team_id');
        
        if (sportCategory) {
            teamSelect.prop('disabled', false);
            
            $.ajax({
                url: "{{ route('admin.standings.schools-by-category') }}",
                type: 'GET',
                data: { sport_category: sportCategory },
                success: function(response) {
                    let options = '<option value="">Select School</option>';
                    response.forEach(function(team) {
                        options += `<option value="${team.id}">${team.school_name}</option>`;
                    });
                    teamSelect.html(options);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Error loading schools. Please try again.');
                }
            });
        } else {
            teamSelect.prop('disabled', true);
            teamSelect.html('<option value="">Select a sport category first</option>');
        }
    });
});
        </script>
    <script>
    $(document).ready(function() {
    // Edit standing
    $('.edit-standing').on('click', function() {
        const id = $(this).data('id');
        $.ajax({
            url: `/admin/standings/${id}/edit`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#editForm').attr('action', `/admin/standings/${id}`);
                    $('#editWins').val(response.standing.wins);
                    $('#editModal').removeClass('hidden');
                }
            },
            error: function(xhr) {
                alert('Error fetching standing data');
            }
        });
    });

    // Delete standing
    $('.delete-standing').on('click', function() {
        if (confirm('Are you sure you want to delete this standing?')) {
            const id = $(this).data('id');
            $.ajax({
                url: `/admin/standings/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Error deleting standing');
                }
            });
        }
    });

    // Close modal
    $('.close-modal').on('click', function() {
        $('#editModal').addClass('hidden');
    });

    // Update form submit
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: 'PUT',
            data: {
                wins: $('#editWins').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function(xhr) {
                alert('Error updating standing');
            }
        });
    });
});
    </script>
    
</x-app-layout>