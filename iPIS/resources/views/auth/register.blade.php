<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

<body class="bg-gray-100 flex items-center justify-center h-full">

    <div class="container mx-auto flex mt-10"> <!-- Added margin-top here -->
        <!-- Login Form Section -->
        <div class="flex items-center justify-center w-full ">
            <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md flex flex-col justify-center login-container ">

                <h3 class="text-center mb-3 text-xl font-bold">Account Details</h3>

                <form id="wizard-form" method="POST"  action="{{ route('register') }}">
                    @csrf
                    <div class="wizard-step" id="step-1">
                        <div class="mb-4 flex space-x-4">
                            <!-- first_name -->
                            <div class="w-1/2">
                                <x-input-label for="first_name" :value="__('First Name')"
                                    class="block text-gray-700 text-sm font-bold mb-2" />
                                <x-text-input id="first_name" name="first_name"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    type="text" :value="old('first_name')" required autofocus autocomplete="first_name" />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                            </div>

                            <!-- last_name -->
                            <div class="w-1/2">
                                <x-input-label for="last_name" :value="__('Last Name')"
                                    class="block text-gray-700 text-sm font-bold mb-2" />
                                <x-text-input id="last_name" name="last_name"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    type="text" :value="old('last_name')" required autofocus autocomplete="last_name" />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                            </div>
                        </div>
                        <!-- Email Address -->
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email Address')"
                                class="block text-gray-700 text-sm font-bold mb-2" />
                            <x-text-input id="email" name="email"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                type="email" :value="old('email')" required autocomplete="email" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Birth_date-->
                        <div class="mb-4">
                            <x-input-label for="birth_date" :value="__('Birth Date')"
                                class="block text-gray-700 text-sm font-bold mb-2" />
                            <x-text-input id="birth_date" name="birth_date"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                type="date" :value="old('birth_date')" required autocomplete="bdate" />
                            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                        </div>
                        <!--Gender-->
                        <div class="mb-4">
                            <x-input-label for="gender" :value="__('Gender')"
                                class="block text-gray-700 text-sm font-bold mb-2" />
                            <select id="gender" name="gender"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>
                        <!-- School Name -->
                        <div class="mb-4">
                            <x-input-label for="school_name" :value="__('School Name')"
                                class="block text-gray-700 text-sm font-bold mb-2" />
                            <select id="school_name" name="school_name"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="" disabled selected>Select School</option>
                                <option value="School A" {{ old('school_name') == 'School A' ? 'selected' : '' }}>School A</option>
                                <option value="School B" {{ old('school_name') == 'School B' ? 'selected' : '' }}>School B</option>
                                <option value="School C" {{ old('school_name') == 'School C' ? 'selected' : '' }}>School C</option>
                            </select>
                            <x-input-error :messages="$errors->get('school_name')" class="mt-2" />
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <x-input-label for="role" :value="__('Role')"
                                class="block text-gray-700 text-sm font-bold mb-2" />
                            <select id="role" name="role"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="" disabled selected>Select Role</option>
                                <option value="Captain" {{ old('role') == 'Captain' ? 'selected' : '' }}>Captain</option>
                                <option value="Coach" {{ old('role') == 'Coach' ? 'selected' : '' }}>Coach</option>
                                <option value="School Representative" {{ old('role') == 'School Representative' ? 'selected' : '' }}>School Representative</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>


                        <!-- Password -->
                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Password')"
                                class="block text-gray-700 text-sm font-bold mb-2" />
                            <x-text-input id="password" name="password"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                type="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')"
                                class="block text-gray-700 text-sm font-bold mb-2" />
                            <x-text-input id="password_confirmation" name="password_confirmation"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                type="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Data Privacy Agreement -->
                        <div class="mb-4">
                            <input type="checkbox" id="agree" name="agree" required>
                            <label for="agree" class="text-gray-700 text-sm font-bold mb-2"> I have read and accept
                                <button type="button" id="privacy-button" class="text-blue-500 underline"
                                    data-bs-toggle="modal" data-bs-target="#privacyModal">Data Privacy
                                    Policy</button></label>
                            <x-input-error :messages="$errors->get('agree')" class="mt-2" />
                        </div>

                        <!-- Register -->
                        <x-primary-button
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Register') }}
                        </x-primary-button>

                        <!-- Redirect to Login -->
                        <div class="text-center mt-4">
                            <a href="/login" class="text-blue-500 hover:underline">Already have an account? Login
                                here</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Bootstrap Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">Data Privacy Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body h-[500px] overflow-scroll">
                    <img src="{{ asset('/images/dataprivacy.png') }}" alt="Data Privacy Policy" class="w-full h-full object-cover">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Left Side Text -->
    <div class="absolute inset-y-0 left-0  items-center hidden lg:flex h-full overflow-hidden "
        style="pointer-events:none">
        <div
            class="sticky rotate-[270deg] translate-y-[50px] transform translate-x-[-50%] text-white font-bold text-5xl xl:text-7xl tracking-wider ml-7 hidden lg:block skew-x-12 transition duration-300">
            <span class="p-3 bg-green-500 ">INVITATIONALS</span><span class="p-3 bg-yellow-500"> INVITATIONALS</span>
        </div>
    </div>

    <!-- Right Side Text -->
    <div class="absolute inset-y-0 right-0   items-center hidden lg:flex h-full overflow-hidden"
        style="pointer-events:none">
        <div
            class="sticky rotate-90 transform translate-x-1/2 text-white font-bold text-5xl xl:text-7xl tracking-wider mr-7 skew-x-12">
            <span class="p-3 bg-green-500">INVITATIONALS</span><span class="p-3 bg-yellow-500"> INVITATIONALS</span>
        </div>
    </div>
</body>

</html>