<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
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
        <div class="flex items-center justify-center w-full">
            <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md flex flex-col justify-center login-container">

                <h3 class="text-center mb-3 text-xl font-bold">Account Details</h3>

                <form id="wizard-form" method="POST" action="">
                    @csrf
                    <div class="wizard-step" id="step-1">
                        <div class="mb-4 flex space-x-4">
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="first_name">First Name</label>
                                <input type="text" id="first_name" name="first_name"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    required>
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="last_name"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email Address</label>
                            <input type="email" id="email" name="email"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="birth_date">Birth Date</label>
                            <input type="date" id="birth_date" name="birth_date"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="gender">Gender</label>
                            <select id="gender" name="gender"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                            <input type="password" id="password" name="password"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Left Side Text -->
    <div class="absolute inset-y-0 left-0  items-center hidden lg:flex" style="pointer-events:none">
        <div class="rotate-[270deg] translate-y-[50px] transform translate-x-[-50%] text-white font-bold text-5xl tracking-wider ml-7 hidden lg:block skew-x-12 ">
            <span class="p-3 bg-green-500">INVITATIONALS</span><span class="p-3 bg-yellow-500"> INVITATIONALS</span>
        </div>
    </div>

    <!-- Right Side Text -->
    <div class="absolute inset-y-0  right-0 translate-y-[-50px]  items-center hidden lg:flex"
        style="pointer-events:none">
        <div class="rotate-90 transform translate-x-1/2 text-white font-bold text-5xl tracking-wider mr-7 skew-x-12">
            <span class="p-3 bg-green-500">INVITATIONALS</span><span class="p-3 bg-yellow-500"> INVITATIONALS</span>
        </div>
    </div>

</body>

</html>
