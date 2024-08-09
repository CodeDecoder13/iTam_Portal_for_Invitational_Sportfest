<x-app-layout>
    <div class="flex-grow px-4 py-6">
        <h1 class="text-3xl font-bold mb-4">Dashboard</h1>

        <!-- Fetch Current User Login -->
        @if (Auth::check())
            <h3 class="text-xl mb-8">Welcome, Coach <span class="underlin">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span></h3>
        @else
            <h3 class="text-xl mb-8">Welcome, Coach</h3>
        @endif

        <div class="flex flex-col items-center justify-center min-h-screen">
            <div class="text-center">
                <!-- Image Icon -->
                <div class="mb-6">
                    <img src="{{ asset('images/search-magnifying.png') }}" alt="Approval Icon" class="w-24 h-24 mx-auto">
                </div>
                <!-- Message -->
                <h1 class="text-xl font-semibold mb-4">Your account is temporarily on hold for approval by our admins.</h1>
                <p class="text-gray-600 mb-8">Please allow 24-48 hours for the approval process. We appreciate your patience.</p>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-full hover:bg-green-800 focus:outline-none">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
>