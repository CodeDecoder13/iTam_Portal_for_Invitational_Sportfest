<x-app-layout>
    <section class="flex flex-col w-full">
        <div class="m-auto bg-green-700 text-white w-full lg:w-1/2 p-5 rounded-xl">
            <h1 class="text-center font-bold">Add Team</h1>
            <form id="team-form" class="p-5" type="POST">
                <div class="w-full">
                    <label for="teamname" class="block text-sm font-medium leading-6">Team Name</label>
                    <div class="mt-2">
                        <div
                            class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-slate-600">
                            <input type="text" name="teamname" id="teamname" autocomplete="teamname"
                                class="block flex-1 border-1 rounded-md bg-transparent py-1.5 pl-1 text-white w-full placeholder:text-slate-300 focus:ring-0 sm:text-sm sm:leading-6"
                                placeholder="Enter Team Name">
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <label for="teamacro" class="block text-sm font-medium leading-6">Team Name Acronym (Max of 5
                        letters ex. FITEC)</label>
                    <div class="mt-2">
                        <div
                            class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-slate-600">
                            <input type="text" name="teamacro" id="teamacro" autocomplete="teamacro"
                                class="block flex-1 border-1 rounded-md bg-transparent py-1.5 pl-1 text-white w-full placeholder:text-slate-300 focus:ring-0 sm:text-sm sm:leading-6"
                                placeholder="Enter Team Name">
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <label for="sportcategory" class="block text-sm font-medium leading-6">Sport Category</label>
                    <div class="mt-2">
                        <div
                            class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-slate-600">
                            <select name="sportcategory" id="sportcategory"
                                class="block flex-1 border-1 rounded-md bg-transparent py-1.5 pl-1 text-white w-full placeholder:text-slate-300 focus:ring-0 sm:text-sm sm:leading-6">
                                <option class="text-black" value="" disabled selected>Select a Sport Category
                                </option>
                                <option class="text-black" value="basketball">Basketball</option>
                                <option class="text-black" value="soccer">Soccer</option>
                                <option class="text-black" value="baseball">Baseball</option>
                                <option class="text-black" value="tennis">Tennis</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="m-auto mt-4 border w-full lg:w-1/2 p-5 rounded-xl">
            <h1 class="text-2xl font-bold mb-4">Team Members</h1>
            <div id="team-members-list">
                <!-- Team members will be added here -->
                <div id="hidethis">There are no players added yet.</div>
            </div>
            <div class="w-full flex justify-center">
                <button id="add-player-btn-modal"
                    class="hover:bg-green-700 mt-2 hover:text-white transition duration-200 px-4 py-1 rounded-full border"
                    type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <span class="bg-green-700 text-white rounded-full px-1">+</span> Add Player
                </button>
            </div>
        </div>
        <div class="w-full flex justify-center mt-4">
            <button id="submit"
                class="bg-green-700 font-bold text-white transition duration-200 px-4 py-1 rounded-full border">
                Proceed
            </button>
        </div>

        <!-- Add Player Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Player</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="player-form">
                            <div class="mb-3">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="first_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="middleName" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middleName" name="middle_name">
                            </div>
                            <div class="mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="last_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="birthday" class="form-label">Birthday</label>
                                <input type="date" class="form-control" id="birthday" name="birthday">
                            </div>
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="save-player-btn" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Player Modal -->
        <div class="modal fade" id="editPlayerModal" tabindex="-1" aria-labelledby="editPlayerModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPlayerModalLabel">Edit Player</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-player-form">
                            <div class="mb-3">
                                <label for="editFirstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="editFirstName" name="edit_first_name"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="editMiddleName" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="editMiddleName"
                                    name="edit_middle_name">
                            </div>
                            <div class="mb-3">
                                <label for="editLastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="editLastName" name="edit_last_name"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="editBirthday" class="form-label">Birthday</label>
                                <input type="date" class="form-control" id="editBirthday" name="edit_birthday">
                            </div>
                            <div class="mb-3">
                                <label for="editGender" class="form-label">Gender</label>
                                <select class="form-select" id="editGender" name="edit_gender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="save-edit-player-btn" class="btn btn-primary">Save
                            Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Initialize player array
        var players = [];
        var counter = 0;

        document.getElementById('save-player-btn').addEventListener('click', function() {
            if (counter == 0) {
                document.getElementById('hidethis').style.display = 'none';
                counter++;
            }
            // Get player details from the form
            var firstName = document.getElementById('firstName').value;
            var middleName = document.getElementById('middleName').value;
            var lastName = document.getElementById('lastName').value;
            var birthday = document.getElementById('birthday').value;
            var gender = document.getElementById('gender').value;

            // Create a player object
            var newPlayer = {
                firstName,
                middleName,
                lastName,
                birthday,
                gender
            };

            // Add the new player to the player array
            players.push(newPlayer);

            // Add the new player to the team members list
            var playerList = document.getElementById('team-members-list');
            var playerItem = document.createElement('div');
            playerItem.className = 'p-4 mb-4 border rounded flex';
            playerItem.innerHTML = `
                <div class="w-7/12">
                    <p class="text-green-700"><strong>${firstName} ${lastName}</strong></p>
                    <p>${middleName}</p>
                    <p>${birthday}</p>
                    <p>${gender}</p>
                </div>
                <div class="w-5/12 flex justify-between">
                    <button class="bg-green-700 text-white px-4 py-1 rounded-full edit-player-btn" data-index="${players.length - 1}" data-bs-toggle="modal" data-bs-target="#editPlayerModal">Edit</button>
                    <button class="bg-red-700 text-white px-4 py-1 rounded-full remove-player-btn" data-index="${players.length - 1}">Remove</button>
                </div>
            `;
            playerList.appendChild(playerItem);

            // Close the modal
            var modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
            modal.hide();

            // Clear the form
            document.getElementById('player-form').reset();
        });

        // Edit player button click event
        document.getElementById('team-members-list').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('edit-player-btn')) {
                var index = e.target.getAttribute('data-index');
                var player = players[index];

                // Fill the edit form with player data
                document.getElementById('editFirstName').value = player.firstName;
                document.getElementById('editMiddleName').value = player.middleName;
                document.getElementById('editLastName').value = player.lastName;
                document.getElementById('editBirthday').value = player.birthday;
                document.getElementById('editGender').value = player.gender;

                // Save the index in the save button
                document.getElementById('save-edit-player-btn').setAttribute('data-index', index);
            }
        });

        // Save changes in the edit modal
        document.getElementById('save-edit-player-btn').addEventListener('click', function() {
            var index = this.getAttribute('data-index');
            var player = players[index];

            // Get edited details from the form
            player.firstName = document.getElementById('editFirstName').value;
            player.middleName = document.getElementById('editMiddleName').value;
            player.lastName = document.getElementById('editLastName').value;
            player.birthday = document.getElementById('editBirthday').value;
            player.gender = document.getElementById('editGender').value;

            // Update the player in the array
            players[index] = player;

            // Update the player in the list
            var playerList = document.getElementById('team-members-list');
            var playerItems = playerList.getElementsByClassName('p-4 mb-4 border rounded flex');
            var playerItem = playerItems[index];
            playerItem.innerHTML = `
                <div class="w-7/12">
                    <p class="text-green-700"><strong>${player.firstName} ${player.lastName}</strong></p>
                    <p>${player.middleName}</p>
                    <p>${player.birthday}</p>
                    <p>${player.gender}</p>
                </div>
                <div class="w-5/12 flex justify-between">
                    <button class="bg-green-700 text-white px-4 py-1 rounded-full edit-player-btn" data-index="${index}" data-bs-toggle="modal" data-bs-target="#editPlayerModal">Edit</button>
                    <button class="bg-red-700 text-white px-4 py-1 rounded-full remove-player-btn" data-index="${index}">Remove</button>
                </div>
            `;

            // Close the modal
            var modal = bootstrap.Modal.getInstance(document.getElementById('editPlayerModal'));
            modal.hide();
        });

        // Remove player button click event
        document.getElementById('team-members-list').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-player-btn')) {
                var index = e.target.getAttribute('data-index');

                // Remove the player from the array
                players.splice(index, 1);

                // Remove the player from the list
                var playerList = document.getElementById('team-members-list');
                playerList.removeChild(e.target.parentNode.parentNode);

                // Re-index the remaining players and update the data-index attributes
                var playerItems = playerList.getElementsByClassName('p-4 mb-4 border rounded flex');
                for (var i = 0; i < playerItems.length; i++) {
                    var editButton = playerItems[i].getElementsByClassName('edit-player-btn')[0];
                    var removeButton = playerItems[i].getElementsByClassName('remove-player-btn')[0];
                    editButton.setAttribute('data-index', i);
                    removeButton.setAttribute('data-index', i);
                }
            }
        });

        document.getElementById('submit').addEventListener('click', function() {
            // Get team form data
            var teamForm = document.getElementById('team-form');
            var formData = new FormData(teamForm);
            var teamData = {
                teamName: formData.get('teamname'),
                teamAcronym: formData.get('teamacro'),
                sportCategory: formData.get('sportcategory'),
                players: players
            };

            // Submit teamData (e.g., send it to the server)
            console.log('Submitting team data:', teamData);
            $.ajaxSetup({
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/save-team',
                type: 'POST',
                data: JSON.stringify(teamData),
                success: function(response) {
                    alert(response.message);
                    window.location.href = "{{ route('dashboard') }}";
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Validation error
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = 'Validation Error:\n';
                        for (var field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                errorMessage += errors[field].join('\n') + '\n';
                            }
                        }
                        alert(errorMessage);
                    } else {
                        alert('Error saving Team data');
                    }
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
</x-app-layout>