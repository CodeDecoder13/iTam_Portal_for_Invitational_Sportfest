<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            height: 100vh;
            overflow: hidden;
        }
        .container {
            max-width: 450px;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen overflow-hidden">

    <div class="container mx-auto flex h-full">
        <!-- Verification Section -->
        <div class="flex items-center justify-center w-full md:w-1/2 lg:w-1/2">
            <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md flex flex-col justify-center container">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/userlogo.png') }}" alt="Logo" width="120px">
                </div>

                <h3 class="text-center mb-3 text-xl font-bold">Verify Your Email</h3>

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif

                <div class="mt-4 flex items-center justify-between">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf

                        <div>
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" style="background-color: #0F622D;">
                                {{ __('Resend Verification Email') }}
                            </button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Background Image Section -->
        <div class="relative w-1/2 h-full md:w-1/2 items-center justify-center hidden lg:flex">
            <img src="{{ asset('images/Players1.png') }}" class="object-cover w-full h-full">
        </div>
    </div>


    <!-- Right Side Text -->
    <div class="absolute inset-y-0 right-0 items-center hidden lg:flex" style="pointer-events:none">
        <div class="rotate-90 transform translate-x-1/2 text-white font-bold text-5xl xl:text-7xl tracking-wider mr-7 skew-x-12">
            <span class="p-3 bg-green-500">INVITATIONALS</span><span class="p-3 bg-yellow-500"> INVITATIONALS</span>
        </div>
    </div>

</body>
</html>
