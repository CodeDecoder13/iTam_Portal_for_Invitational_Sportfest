<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-8">
            <h2 class="text-3xl font-semibold mb-4">{{ $team->sport_category ?? 'N/A' }}</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p><strong>Team Name:</strong> {{ $team->name ?? 'N/A' }}</p>
                    <p><strong>Supervised by:</strong> {{ $team->coach->first_name . ' ' . $team->coach->last_name ?? 'N/A' }}</p>
                    <p><strong>Players:</strong> {{ $team->players->count() ?? 'N/A' }}</p>
                </div>
                <div>
                    <p><strong>Document Status:</strong></p>
                    <p class="mb-1">Birth Certificate:
                        @if ($team->players->whereIn('birth_certificate_status', ['1', '2', '3'])->count() > 0)
                            @if ($team->players->where('birth_certificate_status', '3')->count() > 0)
                                <span class="text-red-500">Rejected</span>
                            @elseif ($team->players->where('birth_certificate_status', '2')->count() > 0)
                                <span class="text-green-500">Approved</span>
                            @elseif ($team->players->where('birth_certificate_status', '1')->count() > 0)
                                <span class="text-primary">Submitted</span>
                            @endif
                        @else
                            <span class="text-muted">Not Submitted</span>
                        @endif
                    </p>
                    <p class="mb-1">Parental Consent:
                        @php
                            $rejectedCount = $team->players->where('parental_consent_status', '3')->count();
                            $approvedCount = $team->players->where('parental_consent_status', '2')->count();
                            $submittedCount = $team->players->where('parental_consent_status', '1')->count();
                            $totalPlayers = $team->players->count();
                        @endphp
                        @if ($rejectedCount > 0)
                            <span class="text-red-500">Rejected ({{ $rejectedCount }}/{{ $totalPlayers }})</span>
                        @elseif ($approvedCount > 0)
                            <span class="text-green-500">Approved ({{ $approvedCount }}/{{ $totalPlayers }})</span>
                        @elseif ($submittedCount > 0)
                            <span class="text-primary">Submitted ({{ $submittedCount }}/{{ $totalPlayers }})</span>
                        @else
                            <span class="text-muted">Not Submitted (0/{{ $totalPlayers }})</span>
                        @endif
                    </p>
                    <p><strong>Status:</strong> <span id="userStatus" class="{{ $team->coach->is_active ? 'text-green-600' : 'text-red-600' }}">{{ $team->coach->is_active ? 'Active' : 'Inactive' }}</span></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
            <div class="bg-orange-500 rounded-lg overflow-hidden shadow-lg">
                <div class="p-6">
                    <h3 class="text-white text-2xl font-bold mb-2">Player Management</h3>
                    <p class="text-white mb-4">Manage player information with Create, Read, Update, and Delete operations</p>
                    <a href="{{ route('sub-player-management', ['id' => $team->id]) }}" class="bg-white text-orange-500 font-bold py-2 px-4 rounded inline-block">
                        Player Management
                    </a>
                </div>
            </div>

            <div class="bg-purple-500 rounded-lg overflow-hidden shadow-lg">
                <div class="p-6">
                    <h3 class="text-white text-2xl font-bold mb-2">Documents Management</h3>
                    <p class="text-white mb-4">Manage and organize documents, including player Birth certificate, Parental Consent, and team records.</p>
                    <a href="{{ route('sub-documents-management', ['id' => $team->id]) }}" class="bg-white text-purple-500 font-bold py-2 px-4 rounded inline-block">
                        View Documents
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to periodically check for updates
        function checkForUpdates() {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const newDoc = parser.parseFromString(html, 'text/html');
                    const newStatus = newDoc.getElementById('userStatus');
                    const currentStatus = document.getElementById('userStatus');
                    
                    if (newStatus && currentStatus && newStatus.textContent !== currentStatus.textContent) {
                        currentStatus.textContent = newStatus.textContent;
                        currentStatus.className = newStatus.className;
                    }
                });
        }

        // Check for updates every 5 seconds
        setInterval(checkForUpdates, 5000);
    </script>
</x-app-layout>