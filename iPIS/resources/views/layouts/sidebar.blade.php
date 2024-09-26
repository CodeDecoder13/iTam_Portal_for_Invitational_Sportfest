<style>
    #sidebar {
        background-image: url(''), linear-gradient(),
            rgba(0, 0, 0, 0.5);
        background-image: linear-gradient(rgba(30, 126, 65, 1),
                rgba(15, 98, 45, 0.3), rgba(30, 126, 65, .9)), url("{{ asset('images/Players1.png') }}");
        background-size: cover;
        /* Cover the entire container */
        background-position: center;
        /* Center the image */
        #1E7E41,
        #0F622D background-repeat: no-repeat;
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

    .nav-link.active {
    background-color: #007bff; /* This is the blue background color */
    color: white; /* This ensures the text color is readable */
    }

    .nav-link {
        color: #ffffff;
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 10px;
        transition: background-color 0.3s ease;
    }

    .nav-link:hover {
        background-color: rgba(0, 0, 0, 0.1); /* This is for the hover effect */
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
    <div class="flex items-center mb-6">
        <img width="70" height="70" class="mr-4" src="{{ asset('images/userlogo.png') }}" alt="ITAM Logo" />
        <div class="flex flex-col">
            <div class="text-xl font-bold leading-tight">ITAM</div>
            <div class="text-xl font-bold leading-tight">INVITATIONAL</div>
            <div class="text-xl font-bold leading-tight">SPORTSFEST</div>
            <div class="text-sm mt-1">COMPILER</div>
        </div>
    </div>

    @if (Auth::user()->is_active)
    <div class="mb-6">
        <label for="team" class="block text-sm font-medium leading-6 text-white mb-1">Select Team</label>
        <select id="team" name="team" autocomplete="team-name"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            <option value="" selected>Select Team</option>
            @foreach ($teams as $team)
                <option value="{{ $team->id }}"
                    {{ isset($newTeam) && $newTeam->id === $team->id ? 'selected' : '' }}>
                    {{ $team->name }} - {{ $team->sport_category }}</option>
            @endforeach
            <option value="add-new-team">Add New Team</option>
        </select>
    </div>
    @endif

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <ion-icon name="home"></ion-icon>
                Home
            </a>
        </li>
        @if (Auth::user()->is_active)
        <li class="nav-item">
            <a href="{{ route('my-documents') }}" class="nav-link {{ request()->routeIs('my-documents') ? 'active' : '' }}">
                <ion-icon name="folder"></ion-icon>
                My Documents
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('my-calendar') }}" class="nav-link {{ request()->routeIs('my-calendar') ? 'active' : '' }}">
                <ion-icon name="calendar"></ion-icon>
                My Calendar
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('my-players') }}" class="nav-link {{ request()->routeIs('my-players') ? 'active' : '' }}">
                <ion-icon name="people"></ion-icon>                    
                My Players
            </a>
        </li>
    </ul>
    @endif
    <hr class="my-6" />
    @if (Auth::user()->is_active)
    <ul class="nav nav-pills flex-column mt-auto">
        <li>
            <a href="#" class="nav-link text-white">
                <ion-icon name="settings"></ion-icon>
                Settings
            </a>
        </li>
        
        
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link text-white w-full text-left"
                    style="background: none; border: none; padding: 0; cursor: pointer;">
                    <ion-icon name="log-out"></ion-icon>Logout
                </button>
            </form>
        </li>
    </ul>
    @endif
</div>


<script>
    const teamSelect = document.getElementById('team');
    teamSelect.addEventListener('change', (e) => {
        if (e.target.value === 'add-new-team') {
            window.location.href = '/add-teams';
        }
    });
</script>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
