    <x-app-layout>
        <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Calendar</h1>
        <div>
            <button id="openAddGameModal" class="bg-green-700 text-white px-4 py-2 rounded-md mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add Game
            </button>
            <button id="openViewGameModal" class="bg-blue-600 text-white px-4 py-2 rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                </svg>
                View Game
            </button>
                </div>
            </div>
        <p class="mb-6">Manage and schedule games</p>
        
        <div class="flex justify-between items-center mb-6">
            <div class="relative">
                <input type="text" placeholder="Search..." class="border rounded-md py-2 px-4 pr-10">
                <svg class="w-5 h-5 text-gray-500 absolute right-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <div class="flex items-center">
                <span class="mr-2">View Filter</span>
                <select class="border rounded-md py-2 px-4">
                    <option>Monthly</option>
                </select>
            </div>
        </div>

        <div id="calendar"></div>
    </div>

    <div id="addGameModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-6 border w-[500px] shadow-lg rounded-md bg-white">
        <h3 class="text-xl font-medium text-gray-900 mb-6 text-center">Add Game</h3>
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <input type="text" placeholder="Team A" class="w-[45%] px-4 py-3 border rounded-md text-lg">
                <span class="text-2xl font-bold">VS</span>
                <input type="text" placeholder="Team B" class="w-[45%] px-4 py-3 border rounded-md text-lg">
            </div>
            <div class="mb-4">
                <input type="date" class="bg-white border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Select date">
            </div>
        </div>
        <select class="w-full px-4 py-3 border rounded-md mb-6 text-lg">
            <option value="" disabled selected>Select game type</option>
            <option value="Boys Basketball Developmental">Boys Basketball Developmental</option>
            <option value="Boys Basketball Competitive">Boys Basketball Competitive</option>
            <option value="Girls Basketball Developmental">Girls Basketball Developmental</option>
            <option value="Boys Volleyball Developmental">Boys Volleyball Developmental</option>
            <option value="Boys Volleyball Competitive">Boys Volleyball Competitive</option>
            <option value="Girls Volleyball Developmental">Girls Volleyball Developmental</option>
            <option value="Girls Volleyball Competitive">Girls Volleyball Competitive</option>
        </select>
        <textarea placeholder="Comments" class="w-full px-4 py-3 border rounded-md mb-6 text-lg" rows="4"></textarea>
        <div class="flex justify-end">
            <button id="closeAddGameModal" class="px-6 py-3 bg-gray-200 text-black rounded-md mr-4 text-lg">Cancel</button>
            <button class="px-6 py-3 bg-green-700 text-white rounded-md text-lg">Add Game</button>
        </div>
        </div>
    </div>


    <!-- View modal -->
    <div id="viewGameModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center space-x-4">
                <div class="bg-green-700 text-white text-2xl font-bold w-12 h-12 flex items-center justify-center rounded">
                    <span id="teamAScore">3</span>
                </div>
                <div>
                    <h3 id="teamAName" class="text-xl font-semibold">FITGC</h3>
                    <p class="text-sm text-gray-500">FEU INSTITUTE OF TECHNOLOGY</p>
                </div>
            </div>
            <div class="text-3xl font-bold">VS</div>
            <div class="flex items-center space-x-4">
                <div>
                    <h3 id="teamBName" class="text-xl font-semibold text-right">DLSUG</h3>
                    <p class="text-sm text-gray-500 text-right">DE LA SALLE UNIVERSITY</p>
                </div>
                <div class="bg-green-700 text-white text-2xl font-bold w-12 h-12 flex items-center justify-center rounded">
                    <span id="teamBScore">0</span>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center mb-4">
            <div>
                <h4 id="gameDate" class="text-lg font-semibold">August 11, 2024</h4>
                <p id="gameType" class="text-md text-gray-600">Basketball Mens (C)</p>
            </div>
            <div>
                <button id="editScoresBtn" class="bg-green-700 text-white px-3 py-1 rounded-md text-sm mr-2">Edit Scores</button>
                <button id="setDefaultBtn" class="bg-yellow-500 text-white px-3 py-1 rounded-md text-sm mr-2">Set Default</button>
                <button id="deleteMatchBtn" class="bg-red-500 text-white px-3 py-1 rounded-md text-sm">Delete Match</button>
            </div>
        </div>

        <div class="mb-4">
            <h4 class="text-lg font-semibold mb-2">Comments</h4>
            <div id="commentsSection" class="bg-gray-100 p-3 rounded-md max-h-40 overflow-y-auto">
                <p class="text-sm"><span class="font-semibold">Sir. Rem:</span> The game ended on 3-0 meanwhile, Team B losses the game.</p>
            </div>
        </div>

        <div class="mb-4">
            <textarea id="newComment" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none" rows="2" placeholder="Add a comment..."></textarea>
        </div>

        <div class="flex justify-end">
            <button id="addCommentBtn" class="bg-green-700 text-white px-4 py-2 rounded-md flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Comment
            </button>
        </div>

        <button class="absolute top-0 right-0 mt-4 mr-4 text-gray-600 hover:text-gray-900" onclick="closeViewGameModal()">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>
    
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>
    
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <style>
        .fc-theme-standard th {
            background-color: #15803d;
            color: white;
        }
        .fc-day-today {
            background-color: #e5e7eb !important;
            border: 2px solid #15803d !important;
        }
        .fc-event {
            background-color: #15803d;
            border: none;
            padding: 2px 4px;
            margin-bottom: 2px;
        }
        .fc-toolbar-title {
            font-size: 1.5rem !important;
            font-weight: bold;
        }
        .fc-button-primary {
            background-color: #15803d !important;
            border-color: #15803d !important;
        }
        .fc-col-header-cell {
            padding: 10px 0;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    {
                        title: 'FITGC - DLSU',
                        start: '2023-05-11'
                    },
                    {
                        title: 'USTET - DLSUT',
                        start: '2023-05-12'
                    },
                    {
                        title: 'FITGC - USTET',
                        start: '2023-05-12'
                    },
                    {
                        title: 'USTET - DLSUT',
                        start: '2023-05-19'
                    }
                ],
                eventColor: '#15803d',
                firstDay: 0, // Start week on Sunday
                height: 'auto',
                aspectRatio: 1.35,
                dayMaxEvents: true, // Allow "more" link when too many events
                fixedWeekCount: false, // Adjust the number of weeks shown based on the month
            });
            calendar.render();
        });

        //
        // Modal open/close functionality
        
        // Modal open/close functionality
        document.getElementById('openAddGameModal').addEventListener('click', function() {
            document.getElementById('addGameModal').classList.remove('hidden');
        });

        document.getElementById('closeAddGameModal').addEventListener('click', function() {
            document.getElementById('addGameModal').classList.add('hidden');
        });
         // View Game Modal
    document.getElementById('openViewGameModal').addEventListener('click', function() {
            openViewGameModal();
        });

        // Close modal when clicking outside
        document.getElementById('addGameModal').addEventListener('click', function(event) {
            if (event.target === this) {
                this.classList.add('hidden');
            }
        });
        document.getElementById('viewGameModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeViewGameModal();
            }
        });
    
        // View Game Modal
    function openViewGameModal() {
        document.getElementById('viewGameModal').classList.remove('hidden');
    }

    function closeViewGameModal() {
        document.getElementById('viewGameModal').classList.add('hidden');
    }
    // Add event listeners and functionality for buttons here
    document.getElementById('editScoresBtn').addEventListener('click', function() {
        // Implement edit scores functionality
    });

    document.getElementById('setDefaultBtn').addEventListener('click', function() {
        // Implement set default functionality
    });

    document.getElementById('deleteMatchBtn').addEventListener('click', function() {
        // Implement delete match functionality
    });

    document.getElementById('addCommentBtn').addEventListener('click', function() {
        const newComment = document.getElementById('newComment').value;
        if (newComment.trim() !== '') {
            const commentsSection = document.getElementById('commentsSection');
            const commentElement = document.createElement('p');
            commentElement.className = 'text-sm mb-2';
            commentElement.innerHTML = `<span class="font-semibold">User:</span> ${newComment}`;
            commentsSection.appendChild(commentElement);
            document.getElementById('newComment').value = '';
        }
    });
    </script>
</x-app-layout>