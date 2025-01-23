<x-app-layout>

    <section class="grid grid-cols-1">

        <div class="grid grid-cols-2">
            <div class="grid-cols-1">
                <h1 class="font-bold mb-2 text-3xl">Documents Management</h1>
                <h3 class="text-gray-600">Manage and organize documents, including player Birth certificate, Parental Consent, and team records.</h3>
            </div>
            <div class="w-full flex flex-col items-end justify-end space-y-2">

                <a href="" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Go Back
                    </a>

            </div>
        </div>

    </section>
    <div class="grid grid-cols-1 mt-5">
        <!-- Header Row -->
        <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
            <div class="font-bold col-span-2">Given Name</div>
            
            <div class="font-bold col-span-2">Sport Category</div>
            <div class="font-bold col-span-2">Team Name</div>
            <div class="font-bold col-span-2">Status</div>
            <div class="font-bold col-span-2">Action</div>
        </div>
    
        <!-- Player Rows -->
        @foreach ($players as $player)
            <div class="grid grid-cols-12 px-4 py-3 bg-white rounded-lg border mt-3">
                <div class="col-span-2">{{ $player->first_name }} {{ $player->last_name }}</div>
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
    <div id="documentModal" class="modal hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white w-2/3 rounded-lg shadow-lg p-6">
            <div class="modal-header flex justify-between items-center border-b pb-4">
                <h2 id="documentModalTitle" class="text-2xl font-bold"></h2>
                <button class="text-gray-500 hover:text-gray-700" onclick="closeModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body mt-4">
                <div id="documentDetails" class="space-y-4">
                    <!-- Content will be dynamically populated -->
                </div>
                <div id="documentPreview" class="mt-6">
                    <!-- Document preview will be shown here -->
                </div>
            </div>
            <div class="modal-footer mt-6 flex justify-center space-x-2">
                <button id="approveBtn" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Approve
                </button>
                <button id="rejectBtn" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Reject
                </button>
                <button id="deleteBtn" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Delete
                </button>
                <button id="showCommentsBtn" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                    </svg>
                    Comments History
                </button>
                <button class="bg-gray-400 hover:bg-gray-500 text-white py-2 px-4 rounded transition duration-200" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Add this after your existing modal -->
    <div id="rejectCommentModal" class="modal hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white w-1/2 rounded-lg shadow-lg p-6">
            <div class="modal-header flex justify-between items-center border-b pb-4">
                <h2 class="text-xl font-bold">Rejection Reason</h2>
                <button class="text-gray-500 hover:text-gray-700" onclick="closeRejectModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body mt-4">
                <textarea id="rejectComment" rows="4" class="w-full p-2 border rounded-lg" placeholder="Please provide a reason for rejection..."></textarea>
            </div>
            <div class="modal-footer mt-4 flex justify-end space-x-2">
                <button onclick="submitRejection()" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded transition duration-200">
                    Submit Rejection
                </button>
                <button onclick="closeRejectModal()" class="bg-gray-400 hover:bg-gray-500 text-white py-2 px-4 rounded transition duration-200">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Add this new modal for comments -->
    <div id="commentsModal" class="modal hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white w-1/2 rounded-lg shadow-lg p-6">
            <div class="modal-header flex justify-between items-center border-b pb-4">
                <h2 class="text-xl font-bold">Comments History</h2>
                <button class="text-gray-500 hover:text-gray-700" onclick="closeCommentsModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body mt-4">
                <div id="commentsList" class="space-y-4 max-h-96 overflow-y-auto">
                    <!-- Comments will be populated here -->
                </div>
            </div>
            <div class="modal-footer mt-4 flex justify-end space-x-2">
                <button onclick="closeCommentsModal()" class="bg-gray-400 hover:bg-gray-500 text-white py-2 px-4 rounded transition duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>

    <script>
    let currentPlayerId = null;
    let currentDocType = null;

    document.querySelectorAll('[data-toggle="modal"]').forEach(button => {
        button.addEventListener('click', function() {
            currentPlayerId = this.getAttribute('data-player_id');
            currentDocType = this.getAttribute('data-doc');
            const playerId = this.getAttribute('data-player_id');
            const schoolName = this.getAttribute('data-school_name');
            const sportCategory = this.getAttribute('data-sport_category');
            const teamId = this.getAttribute('data-team_id');

            fetch(`/admin/get-document?doc_type=${currentDocType}&player_id=${playerId}&school_name=${encodeURIComponent(schoolName)}&sport_category=${encodeURIComponent(sportCategory)}&team_id=${teamId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('documentModalTitle').innerText = `${currentDocType} Details`;
                    
                    let statusClass = '';
                    switch(data.status) {
                        case 'Approved':
                            statusClass = 'text-green-600';
                            break;
                        case 'For Review':
                            statusClass = 'text-yellow-600';
                            break;
                        case 'Rejected':
                            statusClass = 'text-red-600';
                            break;
                        default:
                            statusClass = 'text-gray-600';
                    }

                    const content = `
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <p class="text-gray-600">Player Name</p>
                                <p class="font-semibold">${data.player_name}</p>
                            </div>
                            <div class="space-y-2">
                                <p class="text-gray-600">School</p>
                                <p class="font-semibold">${data.school_name}</p>
                            </div>
                            <div class="space-y-2">
                                <p class="text-gray-600">Team</p>
                                <p class="font-semibold">${data.team}</p>
                            </div>
                            <div class="space-y-2">
                                <p class="text-gray-600">Status</p>
                                <p class="font-semibold ${statusClass}">${data.status}</p>
                            </div>
                        </div>
                        ${data.view_url ? `
                            <div class="mt-6">
                                <iframe src="${data.view_url}" class="w-full h-[500px] border rounded-lg"></iframe>
                            </div>
                        ` : '<p class="mt-6 text-red-500">No document uploaded</p>'}
                    `;

                    document.getElementById('documentDetails').innerHTML = content;
                    openModal();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading document details');
                });
        });
    });

    function openModal() {
        document.getElementById('documentModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('documentModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('documentModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    document.getElementById('approveBtn').addEventListener('click', function() {
        handleDocumentAction('approve');
    });

    document.getElementById('rejectBtn').addEventListener('click', function() {
        openRejectModal();
    });

    document.getElementById('deleteBtn').addEventListener('click', function() {
        if (confirm('Are you sure you want to delete this document?')) {
            handleDocumentAction('delete');
        }
    });

    function handleDocumentAction(action) {
        if (!currentPlayerId || !currentDocType) {
            alert('No document selected');
            return;
        }

        const url = `/admin/document/${action}/${currentPlayerId}/${currentDocType.toLowerCase().replace(' ', '-')}`;
        const method = action === 'delete' ? 'DELETE' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeModal();
                location.reload(); // Refresh the page to show updated status
            } else {
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing your request');
        });
    }

    function openRejectModal() {
        document.getElementById('rejectCommentModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectCommentModal').classList.add('hidden');
    }

    function submitRejection() {
        const comment = document.getElementById('rejectComment').value.trim();
        
        if (!comment) {
            alert('Please provide a reason for rejection');
            return;
        }

        if (!currentPlayerId || !currentDocType) {
            alert('No document selected');
            return;
        }

        const url = `/admin/document/reject/${currentPlayerId}/${currentDocType.toLowerCase().replace(' ', '-')}`;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ comment: comment })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeRejectModal();
                closeModal();
                location.reload();
            } else {
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing your request');
        });
    }

    // Add these new functions to your existing script
    document.getElementById('showCommentsBtn').addEventListener('click', function() {
        loadComments();
    });

    function loadComments() {
        if (!currentPlayerId || !currentDocType) {
            alert('No document selected');
            return;
        }

        const documentType = currentDocType.toLowerCase().replace(' ', '-');
        
        // Add error handling for the fetch request
        fetch(`/admin/document/comments/${currentPlayerId}/${documentType}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const commentsList = document.getElementById('commentsList');
                if (data.comments && data.comments.length > 0) {
                    const commentsHtml = data.comments.map(comment => `
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div class="font-semibold text-gray-800">${comment.user_name}</div>
                                <div class="text-sm text-gray-500">${new Date(comment.created_at).toLocaleString()}</div>
                            </div>
                            <div class="mt-2 text-gray-600">${comment.comment}</div>
                        </div>
                    `).join('');
                    commentsList.innerHTML = commentsHtml;
                } else {
                    commentsList.innerHTML = '<p class="text-gray-500 text-center">No comments yet</p>';
                }
                openCommentsModal();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading comments. Please try again later.');
            });
    }

    function openCommentsModal() {
        document.getElementById('commentsModal').classList.remove('hidden');
    }

    function closeCommentsModal() {
        document.getElementById('commentsModal').classList.add('hidden');
    }

    // Add click outside handler for comments modal
    document.getElementById('commentsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCommentsModal();
        }
    });
    </script>
    
</x-app-layout>