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
        <img width="70" height="70" class="mr-4" src="{{ asset('images/logorac.png') }}" alt="ITAM Logo" />
        <div class="flex flex-col">
            <div class="text-xl font-bold leading-tight">ITAM</div>
            <div class="text-xl font-bold leading-tight">INVITATIONAL</div>
            <div class="text-xl font-bold leading-tight">SPORTSFEST</div>
            <div class="text-sm mt-1">COMPILER</div>
        </div>
    </div>
    <ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
            <ion-icon name="home"></ion-icon>
            Home
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('admin.documents') }}" class="nav-link {{ request()->is('admin/documents*') ? 'active' : '' }}">
            <ion-icon name="folder"></ion-icon>
            Documents
        </a>
    </li>
        
        <li class="nav-item">
            <a href="{{ route('admin.calendar') }}" class="nav-link {{ request()->routeIs('admin.calendar') ? 'active' : '' }}">
                <ion-icon name="calendar"></ion-icon>
                Calendar
            </a>
        </li>
        
        @if (Auth::guard('admin')->check() && (Auth::guard('admin')->user()->role === 'SysAdmin' || Auth::guard('admin')->user()->role === 'SADO'))
        <li class="nav-item">
            <a href="{{ route('admin.school-management') }}" class="nav-link {{ request()->is('admin/school-management*') ? 'active' : '' }}">
                <ion-icon name="school"></ion-icon>
                School Management
            </a>
        </li>
        @endif
        @if (Auth::guard('admin')->check() && (Auth::guard('admin')->user()->role === 'SysAdmin' || Auth::guard('admin')->user()->role === 'SADO'))
    <li class="nav-item">
        <a href="{{ route('admin.user-management') }}" class="nav-link {{ request()->is('admin/user-management*') ? 'active' : '' }}">
            <ion-icon name="people"></ion-icon>
            User Management
        </a>
    </li>
    @endif
    <li class="nav-item">
        <a href="{{ route('admin.coach-approval') }}" class="nav-link {{ request()->is('admin/coach-approval*') ? 'active' : '' }}">
            <ion-icon name="walk"></ion-icon>
            Coach Approval
        </a>
    </li>
    @if (Auth::guard('admin')->check() && (Auth::guard('admin')->user()->role === 'SysAdmin'))
    <li class="nav-item">
        <a href="{{ route('admin.logs-system') }}" class="nav-link {{ request()->is('admin/logs-system*') ? 'active' : '' }}">
            <ion-icon name="cloud"></ion-icon>
            Logs Management
        </a>
    </li>
    @endif
</ul>
        
    </ul>
    <div class="m">
        <ul class="nav nav-pills flex-column">
            <li>
                <a href="#" class="nav-link text-white">
                    <ion-icon name="settings"></ion-icon>
                    Settings
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('admin.logout') }}">
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


