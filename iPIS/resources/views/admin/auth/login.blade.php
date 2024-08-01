<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sado Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            height: 100vh;
            overflow: hidden;
        }
        .login-container {
            max-width: 450px;
        }
        .form-container {
            max-width: 400px;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen overflow-hidden">

    <div class="container mx-auto flex h-full">
        <!-- Login Form Section -->
        <div class="flex items-center justify-center w-full md:w-1/2 lg:w-1/2">
            <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md flex flex-col justify-center login-container">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/userlogo.png') }}" alt="Logo" width="120px">
                </div>
                
                <h3 class="text-center mb-3 text-xl font-bold">Login to your account</h3>
                <p class="text-center mb-6">To continue, please sign in</p>
                
                <form method="POST" action="{{ route('admin.login') }}" class="form-container mx-auto">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-4 flex items-center">
                        <svg class="h-6 w-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="mt-1 flex">
                            <input id="email" name="email" type="email" required autofocus class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter Your Email">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-4 flex">
                        <svg class="h-6 w-6 mr-2 text-green-600" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z"/>
                            <rect x="5" y="11" width="14" height="10" rx="2"/>
                            <circle cx="12" cy="16" r="1"/>
                            <path d="M8 11v-4a4 4 0 0 1 8 0v4"/>
                        </svg>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" required class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter Your Password">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="w-full flex justify-center">
                        <button type="submit" class="w-1/2 flex justify-center py-2 px-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" style="background-color: #0F622D;">
                            Sign-In
                        </button>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('password.request') }}" class=" text-sm text-gray-600 hover:text-gray-900">Forgot your password?</a>
                    </div>

                </form>
            </div>
        </div>

        <!-- Background Image Section -->
        <div class="relative w-1/2 h-full md:w-1/2  items-center justify-center hidden lg:flex" >
            <img src="{{ asset('images/Players1.png') }}" class="object-cover w-full h-full">
        </div>
    </div>

    <!-- Left Side Text -->
    <div class="absolute inset-y-0 left-0  items-center hidden lg:flex"  style="pointer-events:none">
        <div class="rotate-[270deg] translate-y-[50px] transform translate-x-[-50%] text-white font-bold text-5xl tracking-wider ml-7 hidden lg:block skew-x-12 ">
            <span class="p-3 bg-green-500">INVITATIONALS</span><span class="p-3 bg-yellow-500"> INVITATIONALS</span>
        </div>
    </div>

    <!-- Right Side Text -->
    <div class="absolute inset-y-0 right-0 translate-y-[-50px]  items-center hidden lg:flex"  style="pointer-events:none">
        <div class="rotate-90 transform translate-x-1/2 text-white font-bold text-5xl tracking-wider mr-7 skew-x-12" >
            <span class="p-3 bg-green-500">INVITATIONALS</span><span class="p-3 bg-yellow-500"> INVITATIONALS</span>
        </div>
    </div>

</body>
</html>
