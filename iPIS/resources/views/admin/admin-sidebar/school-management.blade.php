<x-app-layout>
    <section class="p-6 space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-bold mb-2 text-3xl">School Management</h1>
                <p class="text-sm text-gray-600">Manage and Organize School Players</p>
            </div>
            <!-- Add User Button -->
         <div class="mt-4 flex justify-end">
            <button type="button" class="btn btn-primary py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-800 focus:outline-none focus:bg-green-700 disabled:opacity-50 disabled:pointer-events-none" data-bs-toggle="modal" data-bs-target="#addUserModal">
                Add A New Coach
            </button>
        </div>

        <!-- Include the Add User Modal Component -->
        <x-add-user-form />
        </div>

        <div class="mt-6">
            <!-- Search bar -->
            <div class="relative w-full sm:w-96">
                <input 
                    type="text" 
                    class="pl-10 pr-4 py-2 w-full bg-white rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-300 focus:border-green-300" 
                    placeholder="Search by name, email, or school..."
                    id="searchTerm"
                />
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <div id="searchStatus" class="mt-2 text-sm text-gray-500"></div>
            </div>
        
            <!-- Results container -->
            <div id="searchResults" class="mt-4 space-y-2">
                
            </div>
        </div>

        <!-- Cards Section -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($users as $user)
                <div class="border rounded-lg shadow-lg bg-white p-4">
                    <!-- Header with Logo and School Name -->
                    <div class="flex items-center gap-4 mb-4">
                        <div class="relative h-16 w-16">
                            <img 
                                src="{{ $user->logo_url }}"
                                alt="{{ $user->school_name ?? 'School Logo' }}"
                                class="w-full h-full object-contain"
                                onerror="this.src='{{ asset('images/placeholder.png') }}'"
                            />
                        </div>
                        <h2 class="text-lg font-semibold">{{ $user->school_name ?? 'N/A' }}</h2>
                    </div>

                    <!-- User Details -->
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                            <span><strong>Coach:</strong> {{ $user->first_name . ' ' . $user->last_name }}</span>
                        </div>

                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm2-8h-4V7h4v2z" />
                            </svg>
                            <span><strong>Status:</strong> 
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </span>
                        </div>

                        <!-- View Details Button -->
                        <div class="mt-4 flex justify-end">
                            <a href="{{ route('admin.card-school-management', ['id' => $user->id]) }}" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>

        
    </section>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

    <script>
        // Search functionality
        const searchInput = document.getElementById('searchTerm');
        const resultsContainer = document.getElementById('searchResults');
        const searchStatus = document.getElementById('searchStatus');
        const usersTable = document.querySelector('.grid');
        let searchTimeout;

        // Add New Coach button HTML
        const addNewCoachButton = `
            <li class="w-full sm:w-auto flex justify-end items-end mb-4">
                <button class="btn btn-success h-2/3" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <sup>+</sup>Add New Coach
                </button>
            </li>
        `;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = this.value.trim();
            
            if (searchTerm === '') {
                resultsContainer.innerHTML = addNewCoachButton;
                searchStatus.textContent = '';
                usersTable.style.display = 'block';
                return;
            }

            usersTable.style.display = 'none';
            searchStatus.textContent = 'Searching...';
            
            searchTimeout = setTimeout(() => {
                fetch(`/admin/search-users?term=${encodeURIComponent(searchTerm)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(response => {
                        const data = response.data || [];
                        searchStatus.textContent = `Found ${data.length} results`;
                        resultsContainer.innerHTML = addNewCoachButton;

                        if (data.length === 0) {
                            resultsContainer.innerHTML += `
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <p class="text-gray-500">No results found</p>
                                </div>
                            `;
                            return;
                        }

                        data.forEach(user => {
                            const resultCard = document.createElement('div');
                            resultCard.className = 'border rounded-lg shadow-lg bg-white p-4';
                            resultCard.innerHTML = `
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="relative h-16 w-16">
                                        <img 
                                            src="${user.logo_url}"
                                            alt="${user.school_name ?? 'School Logo'}"
                                            class="w-full h-full object-contain"
                                            onerror="this.src='{{ asset('images/placeholder.png') }}'"
                                        />
                                    </div>
                                    <h2 class="text-lg font-semibold">${user.school_name ?? 'N/A'}</h2>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                        </svg>
                                        <span><strong>Coach:</strong> ${user.first_name} ${user.last_name}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm2-8h-4V7h4v2z" />
                                        </svg>
                                        <span><strong>Status:</strong> 
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold ${user.is_active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600'}">
                                                ${user.is_active ? 'Active' : 'Inactive'}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="mt-4 flex justify-end">
                                        <a href="{{ route('admin.card-school-management', ['id' => $user->id]) }}" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            `;
                            resultsContainer.appendChild(resultCard);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching search results:', error);
                        searchStatus.textContent = 'Error fetching search results';
                    });
            }, 300);
        });

    </script>
</x-app-layout>
