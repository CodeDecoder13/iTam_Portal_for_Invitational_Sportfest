<script src="https://cdn.tailwindcss.com"></script>
<style>
    #sidebar {
        background-image: url(''), linear-gradient(),
        rgba(0, 0, 0, 0.5);
        background-image: linear-gradient(rgba(30, 126, 65, 1),
                       rgba(15, 98, 45, 0.3),rgba(30, 126, 65, .9)), url("{{ asset('images/Players1.png') }}");
        background-size: cover;
        /* Cover the entire container */
        background-position: center;
        /* Center the image */
        #1E7E41, #0F622D
        background-repeat: no-repeat;
        /* Prevent repeating */
        height: 100vh;
        /* Full width for smaller screens */
        /* Maximum width for larger screens */
        position: ;
        /* Fixed position for sidebar */
        top: 0;
        left: 0;
        z-index: 1000;
        /* Ensure it's on top */
        overflow-y: auto;
        /* Allow scrolling if content overflows */
    }

    .nav-link {
        width: auto;
        /* Auto width for better responsiveness */
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        font-size: 13px;
        line-height: 24px;
        color: #FFFFFF;
        text-decoration: none;
        /* Remove underline */
        display: flex;
        /* Flex display for better alignment */
        align-items: center;
        gap: 8px;
        /* Gap between icon and text */
        padding: 8px 16px;
        /* Padding for better touch target */
    }

    .side-head {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        /* Space between image and text */
        align-items: center;
        padding: 15px;
        gap: 15px;
        flex-shrink: 0;
        /* Prevent shrinking */
    }

    .side-head img {
        width: 50px;
        height: 32px;
    }

    .side-head span {
        font-size: 1.25rem;
    }

    ul {
        list-style: none;
        /* Remove default list styling */
        padding: 0;
        /* Remove default padding */
    }

    li {
        margin-bottom: 10px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #sidebar {
            width: 100%;
            /* Full width on smaller screens */
            max-width: none;
            /* Remove max-width */
            height: auto;
            /* Auto height to fit content */
        }

        .side-head {
            flex-direction: column;
            /* Stack image and text vertically */
            align-items: flex-start;
            /* Align items to start */
        }

        .side-head span {
            font-size: 1rem;
            /* Adjust font size for smaller screens */
        }

        .nav-link {
            font-size: 12px;
            /* Adjust font size for smaller screens */
        }
    }
</style>

<div id="sidebar" class="flex flex-col flex-grow min-h-screen p-3 text-white sticky">
    <a href="/" class="d-flex side-head align-items-center mb-3 mb-md-3 me-md-auto text-white text-decoration-none">
        <img width="50" height="32" class="img-fluid" src="/images/userlogo.png" />
        <span class="fs-4">ITAM INVITATIONAL SPORTFEST COMPILER</span>
    </a>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <div class="sm:col-span-3" style="margin-bottom: 30px">
                <label for="team" class="block text-sm font-medium leading-6 text-white">Select team</label>
                <div class="mt-2 relative">
                    <button id="dropdownButton" class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 bg-white">
                        Select a team
                        <ion-icon name="chevron-down-outline" class="float-right"></ion-icon>
                    </button>
                    <div id="dropdownMenu" class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg hidden max-h-60 overflow-auto">
                        <!-- Teams will be dynamically inserted here -->
                    </div>
                </div>
            </div>
        </li>
        
        <li class="nav-item">
            <a href="/dashboard" class="nav-link active" aria-current="page">
                <ion-icon name="home"></ion-icon>
                Home
            </a>
        </li>
        <li>
            <a href="{{ route('my-documents') }}" class="nav-link text-white">
                <ion-icon name="document"></ion-icon>
                My Documents
            </a>
        </li>
        <li>
            <a href="{{ route('my-calendar') }}" class="nav-link text-white">
                <ion-icon name="people"></ion-icon>
                My Calendar
            </a>
        </li>
        <li>
            <a href="{{ route('my-players') }}" class="nav-link text-white">
                <ion-icon name="people"></ion-icon>
                My Players
            </a>
        </li>
    </ul>
    <hr />
    <div class="m">
        <ul class="nav nav-pills flex-column">
            <li>
                <a href="#" class="nav-link text-white">
                    <ion-icon name="settings"></ion-icon>
                    Settings
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link text-white"
                        style="background: none; border: none; padding: 0; cursor: pointer;">
                        <ion-icon name="log-out"></ion-icon>Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
<script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        fetchTeams();

        const dropdownButton = document.getElementById('dropdownButton');
        const dropdownMenu = document.getElementById('dropdownMenu');

        dropdownButton.addEventListener('click', function () {
            dropdownMenu.classList.toggle('hidden');
        });
    });

    function fetchTeams() {
        fetch('{{ route('get-teams') }}')
            .then(response => response.json())
            .then(data => {
                const dropdownMenu = document.getElementById('dropdownMenu');
                dropdownMenu.innerHTML = '';

                data.forEach(team => {
                    const teamOption = document.createElement('div');
                    teamOption.classList.add('team-option', 'flex', 'items-center', 'justify-between', 'p-2', 'hover:bg-gray-100', 'cursor-pointer');

                    const teamInfo = `
                        <div class="flex items-center">
                            <img src="${team.logo}" alt="${team.acronym} logo" class="w-10 h-10 rounded-full mr-3">
                            <div>
                                <div class="font-bold">${team.acronym}</div>
                                <div class="text-sm text-gray-500">${team.sport_category}</div>
                            </div>
                        </div>
                    `;

                    teamOption.innerHTML = teamInfo;
                    teamOption.addEventListener('click', function () {
                        dropdownButton.innerHTML = `
                            <div class="flex items-center">
                                <img src="${team.logo}" alt="${team.acronym} logo" class="w-10 h-10 rounded-full mr-3">
                                <div>
                                    <div class="font-bold">${team.acronym}</div>
                                    <div class="text-sm text-gray-500">${team.sport_category}</div>
                                </div>
                                <ion-icon name="chevron-down-outline" class="ml-auto"></ion-icon>
                            </div>
                        `;
                        dropdownMenu.classList.add('hidden');
                    });

                    dropdownMenu.appendChild(teamOption);
                });
            })
            .catch(error => console.error('Error fetching teams:', error));
    }
</script>

