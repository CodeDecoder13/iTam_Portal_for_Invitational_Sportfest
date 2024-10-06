<x-app-layout>
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Calendar</h1>
            <div>
                <button id="openAddGameModal" class="bg-green-700 text-white px-4 py-2 rounded-md mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Add Game
                </button>

            </div>
        </div>
        <p class="mb-6">Manage and schedule games</p>

        <div class="flex justify-between items-center mb-6">
            <div class="relative">
                <input type="text" placeholder="Search..." class="border rounded-md py-2 px-4 pr-10">
                <svg class="w-5 h-5 text-gray-500 absolute right-3 top-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
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

    <!-- add game modal -->
    <div id="addGameModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-6 border w-[600px] shadow-lg rounded-md bg-white">
            <h3 class="text-xl font-medium text-gray-900 mb-6 text-center">Add Game</h3>
            <form id="addGameForm">
                <div class="mb-6">
                    <select id="sportCategory" class="w-full px-4 py-3 border rounded-md mb-6 text-lg">
                        <option value="" disabled selected>Select Sport Category</option>
                        <option value="Boys Basketball Developmental">Boys Basketball Developmental</option>
                        <option value="Boys Basketball Competitive">Boys Basketball Competitive</option>
                        <option value="Girls Basketball Developmental">Girls Basketball Developmental</option>
                        <option value="Boys Volleyball Developmental">Boys Volleyball Developmental</option>
                        <option value="Boys Volleyball Competitive">Boys Volleyball Competitive</option>
                        <option value="Girls Volleyball Developmental">Girls Volleyball Developmental</option>
                        <option value="Girls Volleyball Competitive">Girls Volleyball Competitive</option>
                    </select>

                    <div class="flex justify-between items-center mb-4">

                        <!--Add validation here to avoid having the same team on 1 and 2-->
                        <div class="w-[45%]">
                            <select id="team1" class="w-full px-4 py-3 border rounded-md text-lg mb-2">
                                <option value="" disabled selected>Select Team 1</option>
                            </select>
                            <input type="text" id="school1" placeholder="School 1"
                                class="w-full px-4 py-3 border rounded-md text-lg" readonly>
                        </div>
                        <span class="text-2xl font-bold">VS</span>
                        <div class="w-[45%]">
                            <select id="team2" class="w-full px-4 py-3 border rounded-md text-lg mb-2">
                                <option value="" disabled selected>Select Team 2</option>
                            </select>
                            <input type="text" id="school2" placeholder="School 2"
                                class="w-full px-4 py-3 border rounded-md text-lg" readonly>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="gameDate" class="block text-sm font-medium text-gray-700 mb-1">Game Date</label>
                        <input type="date" id="gameDate" name="gameDate"
                            class="bg-white border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>

                    <div class="flex space-x-4 mb-4">
                        <div class="w-1/2">
                            <label for="startTime" class="block text-sm font-medium text-gray-700 mb-1">Start
                                Time</label>
                            <input type="time" id="startTime" name="startTime"
                                class="bg-white border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>
                        <div class="w-1/2">
                            <label for="endTime" class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                            <input type="time" id="endTime" name="endTime"
                                class="bg-white border border-gray-300 text-gray-900 text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>
                    </div>
                </div>


                <div class="flex justify-end">
                    <button id="closeAddGameModal"
                        class="px-6 py-3 bg-gray-200 text-black rounded-md mr-4 text-lg">Cancel</button>
                    <button class="px-6 py-3 bg-green-700 text-white rounded-md text-lg">Add Game</button>
                </div>
            </form>
        </div>
    </div>


    <!-- View modal -->
    <div id="viewGameModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center space-x-4">
                    <div
                        class="bg-green-700 text-white text-2xl font-bold w-12 h-12 flex items-center justify-center rounded">
                        <span id="team1Score">1</span> <!-- Dynamic score for Team 1 -->
                    </div>
                    <div>
                        <h3 id="team1Name" class="text-xl font-semibold">Team 1 Name</h3>
                        <!-- Dynamic name for Team 1 -->
                        <p id="school1Name" class="text-sm text-gray-500">School 1 Name</p>
                        <!-- Dynamic school name for Team 1 -->
                    </div>
                </div>
                <div class="text-3xl font-bold">VS</div>
                <div class="flex items-center space-x-4">
                    <div>
                        <h3 id="team2Name" class="text-xl font-semibold text-right">Team 2 Name</h3>
                        <!-- Dynamic name for Team 2 -->
                        <p id="school2Name" class="text-sm text-gray-500 text-right">School 2 Name</p>
                        <!-- Dynamic school name for Team 2 -->
                    </div>
                    <div
                        class="bg-green-700 text-white text-2xl font-bold w-12 h-12 flex items-center justify-center rounded">
                        <span id="team2Score">1</span> <!-- Dynamic score for Team 2 -->
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mb-4">
                <div>
                    <h4 id="gameDate2" class="text-lg font-semibold">Game Date</h4> <!-- Dynamic game date -->
                    <p id="sportCategory2" class="text-md text-gray-600">Sport Category</p>
                    <!-- Dynamic sport category -->
                </div>
                <div>
                    <button id="editScoresBtn" class="bg-green-700 text-white px-3 py-1 rounded-md text-sm mr-2">Edit
                        Scores</button>
                    <button id="setDefaultBtn" class="bg-yellow-500 text-white px-3 py-1 rounded-md text-sm mr-2">Set
                        Default</button>
                    <button id="deleteMatchBtn" class="bg-red-500 text-white px-3 py-1 rounded-md text-sm">Delete
                        Match</button>
                </div>
            </div>

            <div class="mb-4">
                <h4 class="text-lg font-semibold mb-2">Comments</h4>
                <div id="commentsSection" class="bg-gray-100 p-3 rounded-md max-h-40 overflow-y-auto">
                    <!-- Dynamic comments will be added here -->
                </div>
            </div>

            <div class="mb-4">
                <textarea id="newComment" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none" rows="2"
                    placeholder="Add a comment..."></textarea>
            </div>

            <div class="flex justify-end">
                <button id="addCommentBtn" class="bg-green-700 text-white px-4 py-2 rounded-md flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Comment
                </button>
            </div>

            <button class="absolute top-0 right-0 mt-4 mr-4 text-gray-600 hover:text-gray-900"
                onclick="closeViewGameModal()">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
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
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: '{{ route('admin.get.games') }}',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            console.log(data); // Log the data to inspect it
                            successCallback(data);
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            failureCallback(xhr);
                        }
                    });
                },

                eventColor: '#15803d',
                firstDay: 0,
                height: 'auto',
                aspectRatio: 1.35,
                dayMaxEvents: true,
                fixedWeekCount: false,
                editable: true,
                eventDrop: function(info) {
                    updateGame(info.event);
                },
                eventResize: function(info) {
                    updateGame(info.event);
                },
                eventClick: function(info) {
                    openViewGameModal(info.event.id);
                },
            });
            calendar.render();
        });
    </script>

    <script>
        function closeViewGameModal() {
            document.getElementById('viewGameModal').classList.add('hidden');
        }
        // view modal
        function openViewGameModal(gameId) {
            $.ajax({
                url: `/admin/official-game/${gameId}`, //  '{{ route('admin.store.game') }}    `/official-game/${gameId}`
                method: 'GET',
                success: function(data) {
                    console.log(data);
                    // Populate modal with game data
                    $('#team1Name').text(data.team1.name);
                    $('#team1Score').text(data.team1.score);
                    $('#school1Name').text(data.team1.school);
                    $('#team2Name').text(data.team2.name);
                    $('#team2Score').text(data.team2.score);
                    $('#school2Name').text(data.team2.school);

                    const gameDate = new Date(data.game_date);
                    const options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    $('#gameDate2').text(gameDate.toLocaleDateString('en-US',
                        options
                    )); // Format: October 11, 2024  this is the date of the game has the same id still not showing in the view modal
                    $('#sportCategory2').text(data.sport_category); // not showing with same id
                    console.log(gameDate.toLocaleDateString('en-US',
                        options));
                    // Populate comments section
                    $('#commentsSection').empty(); // Clear previous comments
                    data.comments.forEach(function(comment) {
                        $('#commentsSection').append(
                            `<p class="text-sm"><span class="font-semibold">${comment.user}:</span> ${comment.text}</p>`
                        );
                    });

                    // Show the modal
                    $('#viewGameModal').removeClass('hidden');
                },
                error: function(xhr) {
                    console.error('Error fetching game data:', xhr.responseText);
                    alert('Error fetching game data. Please try again.');
                }
            });
        }
        // Close modal when clicking outside (View Game Modal)
        document.getElementById('viewGameModal').addEventListener('click', function(event) {
            if (event.target === this) {
                this.classList.add('hidden');
            }
        });
    </script>

    <script>
        // get teams    
        document.addEventListener('DOMContentLoaded', function() {
            const sportCategory = document.getElementById('sportCategory');
            const team1Select = document.getElementById('team1');
            const team2Select = document.getElementById('team2');
            const school1Input = document.getElementById('school1');
            const school2Input = document.getElementById('school2');

            sportCategory.addEventListener('change', function() {
                fetchTeams(this.value);
            });

            team1Select.addEventListener('change', function() {
                const selectedTeam = this.options[this.selectedIndex];
                school1Input.value = selectedTeam.textContent.match(/\((.*?)\)/)[1];
            });

            team2Select.addEventListener('change', function() {
                const selectedTeam = this.options[this.selectedIndex];
                school2Input.value = selectedTeam.textContent.match(/\((.*?)\)/)[1];
            });

            function fetchTeams(category) {
                fetch(`{{ route('admin.get.teams') }}?category=${encodeURIComponent(category)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        updateTeamSelect(team1Select, data);
                        updateTeamSelect(team2Select, data);
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                    });
            }

            function updateTeamSelect(select, teams) {
                select.innerHTML = '<option value="" disabled selected>Select Team</option>';
                teams.forEach(team => {
                    const option = document.createElement('option');
                    option.value = team.id;
                    option.textContent = `${team.name} (${team.school})`;
                    select.appendChild(option);
                });
            }
        });


        // add game 
        // AJAX request for adding a game
        $(document).ready(function() {
            $('#addGameForm').on('submit', function(e) {
                e.preventDefault();

                // Fetch form data
                var formData = {
                    sport_category: $('#sportCategory').val(),
                    team1_id: $('#team1').val(),
                    team2_id: $('#team2').val(),
                    game_date: $('#gameDate').val(),
                    start_time: $('#startTime').val(),
                    end_time: $('#endTime').val(),
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                };

                // Send the AJAX request
                $.ajax({
                    url: '{{ route('admin.store.game') }}', // The route for storing the game
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        alert(response.message);
                        // Close the modal after successful submission
                        $('#addGameModal').addClass('hidden');
                        location
                            .reload(); // Optional: Reload the page to see the new game in the calendar
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        // Handle validation errors and display them in the modal if necessary
                        alert('Error adding game: ' + JSON.stringify(errors));
                    }
                });
            });
        });



        document.addEventListener('DOMContentLoaded', function() {
            // Open the Add Game Modal
            document.getElementById('openAddGameModal').addEventListener('click', function() {
                document.getElementById('addGameModal').classList.remove('hidden');
            });

            // Close the Add Game Modal
            document.getElementById('closeAddGameModal').addEventListener('click', function() {
                document.getElementById('addGameModal').classList.add('hidden');
            });

            // Close modal when clicking outside (Add Game Modal)
            document.getElementById('addGameModal').addEventListener('click', function(event) {
                if (event.target === this) {
                    this.classList.add('hidden');
                }
            });

        });
    </script>
</x-app-layout>
