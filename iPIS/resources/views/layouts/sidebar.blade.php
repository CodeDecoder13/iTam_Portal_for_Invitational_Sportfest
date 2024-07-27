<style>
    #sidebar {
        background-image: url('{{ asset('images/Players1.png') }}'), linear-gradient(#1E7E41, #0F622D);
        background-size: cover;
        /* Cover the entire container */
        background-position: center;
        /* Center the image */
        background-repeat: no-repeat;
        /* Prevent repeating */
        height: 100vh;
        width: 100%;
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
                <label for="country" class="block text-sm font-medium leading-6 text-white">Select team</label>
                <div class="mt-2">
                    <select id="country" name="country" autocomplete="country-name"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                        <option>FTIC</option>
                        <option>Dunno</option>
                    </select>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link active" aria-current="page">
                <ion-icon name="home"></ion-icon>
                Home
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
                <ion-icon name="document"></ion-icon>
                My Documents
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
                <ion-icon name="people"></ion-icon>
                My Calendar
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
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
