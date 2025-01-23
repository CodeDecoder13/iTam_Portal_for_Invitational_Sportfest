<x-app-layout>

    <div class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">Summary Of Players</h1>
        <h3>Fill in player's summary to complete your requirements.</h3>
    </div>

    <div class="w-full flex flex-col items-end justify-end space-y-2">
        <a href="{{ route('admin.documents') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Go Back
            </a>
    </div>

    <div class="grid grid-cols-1 mt-5">
        <!-- Header Row -->
        <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
            <div class="font-bold col-span-2">Given Name</div>
            <div class="font-bold col-span-2">School Name</div>
            <div class="font-bold col-span-2">Sport Category</div>
            <div class="font-bold col-span-2">Team Name</div>
            <div class="font-bold col-span-2">Status</div>
            <div class="font-bold col-span-2">Action</div>
        </div>
    
        <!-- Player Rows -->
        @foreach ($players as $player)
            <div class="grid grid-cols-12 px-4 py-3 bg-white rounded-lg border mt-3">
                <div class="col-span-2">{{ $player->first_name }} {{ $player->last_name }}</div>
                <div class="col-span-2">
                    {{ $player->user ? $player->user->school_name : 'N/A' }}
                </div>
                <div class="col-span-2">{{ $player->team->sport_category }}</div>
                <div class="col-span-2">{{ $player->team->name }}</div>
                <div class="col-span-2">
                    @php
                        $status = 'No File Attached'; // Default status
                        if ($player->birth_certificate_status == 3 || $player->parental_consent_status == 3) {
                            $status = 'Rejected';
                        } elseif (
                            $player->birth_certificate_status == 2 &&
                            $player->parental_consent_status == 2
                        ) {
                            $status = 'Approved';
                        } elseif (
                            $player->birth_certificate_status == 1 ||
                            $player->parental_consent_status == 1
                        ) {
                            $status = 'For Review';
                        }
                    @endphp
    
                    @switch($status)
                        @case('Approved')
                            <span class="text-green-500">Approved</span>
                        @break
    
                        @case('For Review')
                            <span class="text-yellow-500">For Review</span>
                        @break
    
                        @case('Rejected')
                            <span class="text-red-500">Rejected</span>
                        @break
    
                        @case('No File Attached')
                            <span class="text-gray-500">No File Attached</span>
                        @break
                    @endswitch
                </div>
    
                <div class="col-span-2">
                    <div class="flex flex-col gap-2">
                        <!-- Consent Button -->
                        <button
                            class="flex items-center gap-1 text-emerald-600 border-emerald-600 hover:bg-emerald-100 w-full font-bold py-2 px-4 rounded border"
                            data-toggle="modal" data-target="#documentModal" data-doc="Parental Consent"
                            data-team_id="{{ $player->team_id }}" data-player_id="{{ $player->id }}"
                            data-school_name="{{ $player->user->school_name }}"
                            data-sport_category="{{ $player->team->sport_category }}"
                            data-status="{{ $player->parental_consent_status }}"
                            data-file_name="{{ $player->parental_consent }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 11l3 3L22 4"></path>
                                <path d="M21 21H3V7a4 4 0 014-4h10a4 4 0 014 4z"></path>
                            </svg>
                            Consent
                        </button>
    
                        <!-- Certificate Button -->
                        <button
                            class="flex items-center gap-1 text-blue-600 border-blue-600 hover:bg-blue-100 w-full font-bold py-2 px-4 rounded border"
                            data-toggle="modal" data-target="#documentModal" data-doc="Birth Certificate"
                            data-team_id="{{ $player->team_id }}" data-player_id="{{ $player->id }}"
                            data-school_name="{{ $player->user->school_name }}"
                            data-sport_category="{{ $player->team->sport_category }}"
                            data-status="{{ $player->birth_certificate_status }}"
                            data-file_name="{{ $player->birth_certificate }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16v16H4z"></path>
                                <path d="M4 9h16"></path>
                                <path d="M9 4v16"></path>
                            </svg>
                            Certificate
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal -->
<div id="documentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900" id="modalTitle">Document Details</h3>
                <button type="button" class="close-modal text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div id="documentDisplay" class="mt-4">
                    <div id="loadingState" class="text-center py-12 hidden">
                        <svg class="animate-spin h-8 w-8 mx-auto text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div id="documentContent" class="hidden">
                        <iframe id="documentViewer" class="w-full h-[500px] border-0"></iframe>
                    </div>
                    <div id="errorState" class="text-center py-12 hidden">
                        <p class="text-red-500">Unable to load document</p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-between p-4 border-t">
                <div class="flex gap-2">
                    <button id="approveBtn" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                        Approve
                    </button>
                    <button id="declineBtn" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                        Decline
                    </button>
                    <button id="deleteBtn" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                        Delete
                    </button>
                </div>
                <div class="flex gap-2">
                    <button class="close-modal px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('documentModal');
        const buttons = document.querySelectorAll('[data-toggle="modal"]');
        const closeButtons = document.querySelectorAll('.close-modal');
    
        // Show modal when buttons are clicked
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });
        });
    
        // Close modal functionality
        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    
        // Close on outside click
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
    </script>