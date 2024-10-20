<x-app-layout>

               
                        <div class="container mx-auto p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h1 class="text-2xl font-bold">Calendar</h1>
                
                            </div>
                            <p class="mb-6">Manage and schedule games</p>
                            
                            <div class="flex justify-between items-center mb-6">
                              <!--  <div class="relative">
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
                                -->
                            </div>

                            <div id="calendar"></div>

                        </div>
        <!-- View modal -->
        <div id="viewGameModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center space-x-4">
                        <div class="bg-green-700 text-white text-2xl font-bold w-12 h-12 flex items-center justify-center rounded">
                            <span id="team1Score">1</span> <!-- Dynamic score for Team 1 -->
                        </div>
                        <div>
                            <h3 id="team1Name" class="text-xl font-semibold">Team 1 Name</h3> <!-- Dynamic name for Team 1 -->
                            <p id="school1Name" class="text-sm text-gray-500">School 1 Name</p> <!-- Dynamic school name for Team 1 -->
                        </div>
                    </div>
                    <div class="text-3xl font-bold">VS</div>
                    <div class="flex items-center space-x-4">
                        <div>
                            <h3 id="team2Name" class="text-xl font-semibold text-right">Team 2 Name</h3> <!-- Dynamic name for Team 2 -->
                            <p id="school2Name" class="text-sm text-gray-500 text-right">School 2 Name</p> <!-- Dynamic school name for Team 2 -->
                        </div>
                        <div class="bg-green-700 text-white text-2xl font-bold w-12 h-12 flex items-center justify-center rounded">
                            <span id="team2Score">1</span> <!-- Dynamic score for Team 2 -->
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h4 id="gameDate" class="text-lg font-semibold">Game Date</h4> <!-- Dynamic game date -->
                        <p id="sportCategory" class="text-md text-gray-600">Sport Category</p> <!-- Dynamic sport category -->
                    </div>
                    
                </div>

                <div class="mb-4">
                    <h4 class="text-lg font-semibold mb-2">Comments</h4>
                    <div id="commentsSection" class="bg-gray-100 p-3 rounded-md max-h-40 overflow-y-auto">
                        <!-- Dynamic comments will be added here -->
                    </div>
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
                    events: function(fetchInfo, successCallback, failureCallback) {
                        $.ajax({
                            url: '{{ route('get.games.user') }}',
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
           url: `/official-game-match/${gameId}`,
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
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                $('#gameDate').text(gameDate.toLocaleDateString('en-US', options)); 
                $('#sportCategory').text(data.sport_category);  

               // Populate comments section
               $('#commentsSection').empty(); // Clear previous comments
                    data.comments.forEach(function(comment) {
                        $('#commentsSection').append(
                            `<p class="text-sm"><span class="font-semibold">${comment.admin_name}:</span> ${comment.content}</p>`
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


</x-app-layout>