<x-app-layout>
    <div class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">Coach Approval</h1>
        <h3>Manage and Organize Coach/Captain/School Representative</h3>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <!-- Search bar -->
        <div class="relative w-full sm:w-64">
            <input 
                type="text" 
                class="pl-10 pr-4 py-2 w-full bg-white rounded-full shadow-sm focus:ring-2 focus:ring-green-300" 
                placeholder="Search coaches..."
                id="searchTerm"
            />
            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l-3-3m0 0l3-3m-3 3h12M13 16l-3-3m0 0l3-3m-3 3h12"></path>
            </svg>
        </div>
    
        <!-- Add New Coach button -->
        <li class="w-full sm:w-auto flex justify-end items-end">
            <button class="btn btn-success h-2/3" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <sup>+</sup>Add New Coach
            </button>
        </li>
    </div>

    <div class="grid grid-cols-12 px-4 py-3 bg-grey-700 text-white rounded-lg border">
        @foreach ($data['users'] as $user) 
            @php
                $userTeams = $data['teams']->where('coach_id', $user->id);
                $sports = $userTeams->pluck('sport_category')->unique();
                $teams = $userTeams->pluck('name')->unique();
            @endphp
            <div class="col-span-4 p-4 relative">
                <div class="bg-white text-black p-4 rounded-lg shadow cursor-pointer relative" 
                onclick="openModal({{ $user->is_active ? 'true' : 'false' }}, '{{ $user->first_name }}', '{{ $user->last_name }}', '{{ $user->school_name ?? 'N/A' }}', '{{ $user->role ?? 'N/A' }}', '{{ $sports->isNotEmpty() ? $sports->implode(', ') : 'N/A' }}', '{{ $teams->isNotEmpty() ? $teams->implode(', ') : 'N/A' }}', '{{ $sports->isNotEmpty() ? $sports->implode(', ') : 'N/A' }}', '{{ $teams->isNotEmpty() ? $teams->implode(', ') : 'N/A' }}', '{{ $user->birth_date }}', '{{ $user->gender }}', {{ $user->id }})">
                    
                    <!-- User Info -->
                    <h2 class="font-bold text-2xl mb-4 inline-flex items-center">
                        {{ $user->first_name }} {{ $user->last_name }} 
                        <!-- Conditional Activation Badge -->
                        <span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full ml-2 {{ $user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                            <span class="w-2 h-2 me-1 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </h2>
                    <p>{{ $user->role }}</p>
                    <p>{{ $user->school_name }}</p>
                    <p>Email: {{ $user->email }}</p>
    
                    <!-- Kebab Menu Button (moved inside the container) -->
                    <button class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-2xl p-2 kebab-menu" id="kebabMenuButton-{{ $user->id }}">&#x22EE;</button>
                    
                </div>

                
    
                <!-- Kebab Menu Dropdown (still inside the relative container) -->
                <div id="kebabMenuDropdown-{{ $user->id }}" class="hidden absolute right-4 top-12 bg-white rounded-md shadow-lg z-20 w-40">
                    <ul class="py-1">
                        <li>
                            <!-- Edit Button -->
                        <button class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editUserModal" 
                                data-user-id="{{ $user->id }}" 
                                data-user-firstname="{{ $user->first_name }}" 
                                data-user-lastname="{{ $user->last_name }}" 
                                data-user-email="{{ $user->email }}" 
                                data-user-role="{{ $user->role }}" 
                                data-user-schoolname="{{ $user->school_name }}">
                            Edit
                        </button>
                        </li>
                        
                    </ul>
                </div>
            </div>  
        @endforeach
    </div>

    <!-- modals area-->
    <div id="userModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="bg-black opacity-50 absolute inset-0"></div>
        <div class="bg-white rounded-lg p-6 z-10 w-11/12 md:w-1/3 relative">
            <button class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-3xl p-2" onclick="closeModal()">&times;</button>
    
          
    
            <!-- Name and Role -->
            <h2 class="font-bold text-2xl mb-4 inline-flex items-center" id="modalUserName"></h2> 
            
            <!-- Activation Badge -->
            <span id="activationBadge" class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full ml-2"></span>
            
            <p class="font-semibold">School Name</p>
            <p id="modalUserSchool" class="mb-3"></p>
            
            <p class="font-semibold">Sport Category</p>
            <p id="modalUserSportCategory" class="mb-2"></p>
            
            <p class="font-semibold">Team Name</p>
            <p id="modalUserTeamName" class="mb-2"></p>
            
            <p class="font-semibold">Birth Date</p>
            <p id="modalUserBirthDate" class="mb-2"></p>
            
            <p class="font-semibold">Gender</p>
            <p id="modalUserGender" class="mb-2"></p>
    
            <div class="flex flex-col">
                <div class="self-end">
                     <!-- Change here: Add userId to the buttons -->
                    <button class="bg-green-700 hover:bg-green-800 text-white px-2 py-1 rounded-lg mr-2" id="activateButton">Activate</button>
                    <button class="bg-red-700 hover:bg-red-800 text-white px-2 py-1 rounded-lg" id="deactivateButton">Deactivate</button>
                </div>
            </div>
            
        </div>
    </div>

     <!-- Bootstrap Modal for Adding Users -->
     <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="accountDetailsModalLabel">Account Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" method="POST">
                @csrf
                <div class="wizard-step" id="step-1">
                <div class="mb-4 flex space-x-4">
                    <!-- First Name -->
                    <div class="w-1/2">
                    <x-input-label for="first_name" :value="__('First Name')" class="block text-gray-700 text-sm font-bold mb-2" />
                    <x-text-input id="first_name" name="first_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" :value="old('first_name')" required autofocus autocomplete="first_name" />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                    </div>
    
                    <!-- Last Name -->
                    <div class="w-1/2">
                    <x-input-label for="last_name" :value="__('Last Name')" class="block text-gray-700 text-sm font-bold mb-2" />
                    <x-text-input id="last_name" name="last_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" :value="old('last_name')" required autofocus autocomplete="last_name" />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                    </div>
                </div>
                
                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email Address')" class="block text-gray-700 text-sm font-bold mb-2" />
                    <x-text-input id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="email" :value="old('email')" required autocomplete="email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
    
                <!-- Birth Date -->
                <div class="mb-4">
                    <x-input-label for="birth_date" :value="__('Birth Date')" class="block text-gray-700 text-sm font-bold mb-2" />
                    <x-text-input id="birth_date" name="birth_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="date" :value="old('birth_date')" required autocomplete="bdate" />
                    <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                </div>
    
                <!-- Gender -->
                <div class="mb-4">
                    <x-input-label for="gender" :value="__('Gender')" class="block text-gray-700 text-sm font-bold mb-2" />
                    <select id="gender" name="gender" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                </div>
    
                <!-- School Name -->
                <div class="mb-4">
                    <x-input-label for="school_name" :value="__('School Name')" class="block text-gray-700 text-sm font-bold mb-2" />
                    <select id="school_name" name="school_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="" disabled selected>Select School</option>
                                <option value="Ilaya Barangka Elementary School" {{ old('school_name') == 'Ilaya Barangka Elementary School' ? 'selected' : '' }}>Ilaya Barangka Elementary School</option>
                                <option value="Aquinas School" {{ old('school_name') == 'Aquinas School' ? 'selected' : '' }}>Aquinas School</option>
                                <option value="Assemblywoman Felicita G. Berdino Memorial Trade School" {{ old('school_name') == 'Assemblywoman Felicita G. Berdino Memorial Trade School' ? 'selected' : '' }}>Assemblywoman Felicita G. Berdino Memorial Trade School</option>
                                <option value="Batasan National High School" {{ old('school_name') == 'Batasan National High School' ? 'selected' : '' }}>Batasan National High School</option>
                                <option value="Canossa Academy Lipa" {{ old('school_name') == 'Canossa Academy Lipa' ? 'selected' : '' }}>Canossa Academy Lipa</option>
                                <option value="Catmon Integrated School" {{ old('school_name') == 'Catmon Integrated School' ? 'selected' : '' }}>Catmon Integrated School</option>
                                <option value="Chiang Kai Shek College" {{ old('school_name') == 'Chiang Kai Shek College' ? 'selected' : '' }}>Chiang Kai Shek College</option>
                                <option value="College of St. Catherine, Quezon City" {{ old('school_name') == 'College of St. Catherine, Quezon City' ? 'selected' : '' }}>College of St. Catherine, Quezon City</option>
                                <option value="Community Learning Academy of San Jose" {{ old('school_name') == 'Community Learning Academy of San Jose' ? 'selected' : '' }}>Community Learning Academy of San Jose</option>
                                <option value="De La Salle Araneta University" {{ old('school_name') == 'De La Salle Araneta University' ? 'selected' : '' }}>De La Salle Araneta University</option>
                                <option value="Dominican School, Manila" {{ old('school_name') == 'Dominican School, Manila' ? 'selected' : '' }}>Dominican School, Manila</option>
                                <option value="Domuschola International School" {{ old('school_name') == 'Domuschola International School' ? 'selected' : '' }}>Domuschola International School</option>
                                <option value="Don Antonio De Zuzuarregui Sr Memorial Academy" {{ old('school_name') == 'Don Antonio De Zuzuarregui Sr Memorial Academy' ? 'selected' : '' }}>Don Antonio De Zuzuarregui Sr Memorial Academy</option>
                                <option value="Emilio Aguinaldo College, Manila" {{ old('school_name') == 'Emilio Aguinaldo College, Manila' ? 'selected' : '' }}>Emilio Aguinaldo College, Manila</option>
                                <option value="Ernesto Rondon High School" {{ old('school_name') == 'Ernesto Rondon High School' ? 'selected' : '' }}>Ernesto Rondon High School</option>
                                <option value="Escuela De Sophia School of Caloocan Inc." {{ old('school_name') == 'Escuela De Sophia School of Caloocan Inc.' ? 'selected' : '' }}>Escuela De Sophia School of Caloocan Inc.</option>
                                <option value="FEU Diliman" {{ old('school_name') == 'FEU Diliman' ? 'selected' : '' }}>FEU Diliman</option>
                                <option value="FEU Roosevelt Marikina" {{ old('school_name') == 'FEU Roosevelt Marikina' ? 'selected' : '' }}>FEU Roosevelt Marikina</option>
                                <option value="FEU Roosevelt Rodriguez" {{ old('school_name') == 'FEU Roosevelt Rodriguez' ? 'selected' : '' }}>FEU Roosevelt Rodriguez</option>
                                <option value="Gracel Christian College Foundation" {{ old('school_name') == 'Gracel Christian College Foundation' ? 'selected' : '' }}>Gracel Christian College Foundation</option>
                                <option value="Grant Cecilia Integrated School" {{ old('school_name') == 'Grant Cecilia Integrated School' ? 'selected' : '' }}>Grant Cecilia Integrated School</option>
                                <option value="Holy Angel School of Caloocan Inc." {{ old('school_name') == 'Holy Angel School of Caloocan Inc.' ? 'selected' : '' }}>Holy Angel School of Caloocan Inc.</option>
                                <option value="Holy Infant Montessori Center" {{ old('school_name') == 'Holy Infant Montessori Center' ? 'selected' : '' }}>Holy Infant Montessori Center</option>
                                <option value="Holy Trinity Academy" {{ old('school_name') == 'Holy Trinity Academy' ? 'selected' : '' }}>Holy Trinity Academy</option>
                                <option value="HSL-Braille College Inc." {{ old('school_name') == 'HSL-Braille College Inc.' ? 'selected' : '' }}>HSL-Braille College Inc.</option>
                                <option value="Integrated School of Science/AIMS" {{ old('school_name') == 'Integrated School of Science/AIMS' ? 'selected' : '' }}>Integrated School of Science/AIMS</option>
                                <option value="Jaime Cardinal Sin Learning Center" {{ old('school_name') == 'Jaime Cardinal Sin Learning Center' ? 'selected' : '' }}>Jaime Cardinal Sin Learning Center</option>
                                <option value="Jesus Christ Saves Global Outreach Christian Academy" {{ old('school_name') == 'Jesus Christ Saves Global Outreach Christian Academy' ? 'selected' : '' }}>Jesus Christ Saves Global Outreach Christian Academy</option>
                                <option value="Jesus is Lord College Foundation" {{ old('school_name') == 'Jesus is Lord College Foundation' ? 'selected' : '' }}>Jesus is Lord College Foundation</option>
                                <option value="Jesus Reigns Christian Academy" {{ old('school_name') == 'Jesus Reigns Christian Academy' ? 'selected' : '' }}>Jesus Reigns Christian Academy</option>
                                <option value="Juan R. Liwag Memorial High School" {{ old('school_name') == 'Juan R. Liwag Memorial High School' ? 'selected' : '' }}>Juan R. Liwag Memorial High School</option>
                                <option value="Justice Cecilia Munoz Palma High School" {{ old('school_name') == 'Justice Cecilia Munoz Palma High School' ? 'selected' : '' }}>Justice Cecilia Munoz Palma High School</option>
                                <option value="Kings Montessori School" {{ old('school_name') == 'Kings Montessori School' ? 'selected' : '' }}>Kings Montessori School</option>
                                <option value="Lakandula High School" {{ old('school_name') == 'Lakandula High School' ? 'selected' : '' }}>Lakandula High School</option>
                                <option value="Leandro Locsin Integrated School" {{ old('school_name') == 'Leandro Locsin Integrated School' ? 'selected' : '' }}>Leandro Locsin Integrated School</option>
                                <option value="Malabon National High School" {{ old('school_name') == 'Malabon National High School' ? 'selected' : '' }}>Malabon National High School</option>
                                <option value="Manggahan High School" {{ old('school_name') == 'Manggahan High School' ? 'selected' : '' }}>Manggahan High School</option>
                                <option value="Manila Cathedral School" {{ old('school_name') == 'Manila Cathedral School' ? 'selected' : '' }}>Manila Cathedral School</option>
                                <option value="Manuel G. Araullo High School" {{ old('school_name') == 'Manuel G. Araullo High School' ? 'selected' : '' }}>Manuel G. Araullo High School</option>
                                <option value="Milestone Innovative Academy" {{ old('school_name') == 'Milestone Innovative Academy' ? 'selected' : '' }}>Milestone Innovative Academy</option>
                                <option value="Moreh Academy Inc." {{ old('school_name') == 'Moreh Academy Inc.' ? 'selected' : '' }}>Moreh Academy Inc.</option>
                                <option value="Mother of Perpetual Help School, Inc." {{ old('school_name') == 'Mother of Perpetual Help School, Inc.' ? 'selected' : '' }}>Mother of Perpetual Help School, Inc.</option>
                                <option value="Mystical Rose School of Bulacan, Inc." {{ old('school_name') == 'Mystical Rose School of Bulacan, Inc.' ? 'selected' : '' }}>Mystical Rose School of Bulacan, Inc.</option>                                                
                                <option value="Mystical Rose School of Caloocan, Inc." {{ old('school_name') == 'Mystical Rose School of Caloocan, Inc.' ? 'selected' : '' }}>Mystical Rose School of Caloocan, Inc.</option>
                                <option value="Nazarene Catholic School" {{ old('school_name') == 'Nazarene Catholic School' ? 'selected' : '' }}>Nazarene Catholic School</option>
                                <option value="New Era High School" {{ old('school_name') == 'New Era High School' ? 'selected' : '' }}>New Era High School</option>
                                <option value="New Prodon Academy of Valenzuela" {{ old('school_name') == 'New Prodon Academy of Valenzuela' ? 'selected' : '' }}>New Prodon Academy of Valenzuela</option>
                                <option value="Northern Rizal Yorklin School" {{ old('school_name') == 'Northern Rizal Yorklin School' ? 'selected' : '' }}>Northern Rizal Yorklin School</option>
                                <option value="Nuestra Senora De Guia Academy" {{ old('school_name') == 'Nuestra Senora De Guia Academy' ? 'selected' : '' }}>Nuestra Senora De Guia Academy</option>
                                <option value="Nuestra Senora Del Carmen Institute" {{ old('school_name') == 'Nuestra Senora Del Carmen Institute' ? 'selected' : '' }}>Nuestra Senora Del Carmen Institute</option>
                                <option value="Our Lady of Fatima Catholic School, Bacood" {{ old('school_name') == 'Our Lady of Fatima Catholic School, Bacood' ? 'selected' : '' }}>Our Lady of Fatima Catholic School, Bacood</option>
                                <option value="Our Lady of Fatima University Quezon City" {{ old('school_name') == 'Our Lady of Fatima University Quezon City' ? 'selected' : '' }}>Our Lady of Fatima University Quezon City</option>
                                <option value="Our Lady of Fatima University Valenzuela" {{ old('school_name') == 'Our Lady of Fatima University Valenzuela' ? 'selected' : '' }}>Our Lady of Fatima University Valenzuela</option>
                                <option value="Our Lady of Peace School" {{ old('school_name') == 'Our Lady of Peace School' ? 'selected' : '' }}>Our Lady of Peace School</option>
                                <option value="PAREF Rosehill" {{ old('school_name') == 'PAREF Rosehill' ? 'selected' : '' }}>PAREF Rosehill</option>
                                <option value="Philippine Academy of Sakya" {{ old('school_name') == 'Philippine Academy of Sakya' ? 'selected' : '' }}>Philippine Academy of Sakya</option>
                                <option value="Riveridge School Inc." {{ old('school_name') == 'Riveridge School Inc.' ? 'selected' : '' }}>Riveridge School Inc.</option>
                                <option value="Rizal High School" {{ old('school_name') == 'Rizal High School' ? 'selected' : '' }}>Rizal High School</option>
                                <option value="Sacred Heart Academy of Novaliches" {{ old('school_name') == 'Sacred Heart Academy of Novaliches' ? 'selected' : '' }}>Sacred Heart Academy of Novaliches</option>
                                <option value="Sacred Heart of Jesus Catholic School" {{ old('school_name') == 'Sacred Heart of Jesus Catholic School' ? 'selected' : '' }}>Sacred Heart of Jesus Catholic School</option>
                                <option value="Sampaguita High School" {{ old('school_name') == 'Sampaguita High School' ? 'selected' : '' }}>Sampaguita High School</option>
                                <option value="San Felipe Neri Catholic School" {{ old('school_name') == 'San Felipe Neri Catholic School' ? 'selected' : '' }}>San Felipe Neri Catholic School</option>
                                <option value="St. Benedict School of Novaliches" {{ old('school_name') == 'St. Benedict School of Novaliches' ? 'selected' : '' }}>St. Benedict School of Novaliches</option>
                                <option value="St. John's Wort Montessori School" {{ old('school_name') == 'St. John Wort Montessori School' ? 'selected' : '' }}>St. John's Wort Montessori School</option>
                                <option value="St. Louis College Valenzuela" {{ old('school_name') == 'St. Louis College Valenzuela' ? 'selected' : '' }}>St. Louis College Valenzuela</option>
                                <option value="St. Mary's Angel College - Valenzuela" {{ old('school_name') == 'St. Marys Angel College - Valenzuela' ? 'selected' : '' }}>St. Mary's Angel College - Valenzuela</option>
                                <option value="St. Patrick School of Quezon City" {{ old('school_name') == 'St. Patrick School of Quezon City' ? 'selected' : '' }}>St. Patrick School of Quezon City</option>
                                <option value="St. Stephen High School" {{ old('school_name') == 'St. Stephen High School' ? 'selected' : '' }}>St. Stephen High School</option>
                                <option value="St. Theresa's College, Quezon City" {{ old('school_name') == 'St. Theresas College, Quezon City' ? 'selected' : '' }}>St. Theresa's College, Quezon City</option>
                                <option value="System Plus Computer College - Caloocan Campus" {{ old('school_name') == 'System Plus Computer College - Caloocan Campus' ? 'selected' : '' }}>System Plus Computer College - Caloocan Campus</option>
                                <option value="The Cardinal Academy Inc." {{ old('school_name') == 'The Cardinal Academy Inc.' ? 'selected' : '' }}>The Cardinal Academy Inc.</option>
                                <option value="Trinitas College" {{ old('school_name') == 'Trinitas College' ? 'selected' : '' }}>Trinitas College</option>
                                <option value="Trinitas School of Sta Maria" {{ old('school_name') == 'Trinitas School of Sta Maria' ? 'selected' : '' }}>Trinitas School of Sta Maria</option>
                                <option value="UST Angelicum College" {{ old('school_name') == 'UST Angelicum College' ? 'selected' : '' }}>UST Angelicum College</option>
                                <option value="Villagers Montessori College" {{ old('school_name') == 'Villagers Montessori College' ? 'selected' : '' }}>Villagers Montessori College</option>
                                <option value="Young Achievers School of Caloocan Inc." {{ old('school_name') == 'Young Achievers School of Caloocan Inc.' ? 'selected' : '' }}>Young Achievers School of Caloocan Inc.</option>
                    </select>
                    <x-input-error :messages="$errors->get('school_name')" class="mt-2" />
                </div>

                    <!-- Role -->
                    <div class="mb-4">
                        <x-input-label for="role" :value="__('Role')" class="block text-gray-700 text-sm font-bold mb-2" />
                        <select id="role" name="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="" disabled selected>Select Role</option>
                            <option value="Captain" {{ old('role') == 'Captain' ? 'selected' : '' }}>Captain</option>
                            <option value="Coach" {{ old('role') == 'Coach' ? 'selected' : '' }}>Coach</option>
                            <option value="School Representative" {{ old('role') == 'School Representative' ? 'selected' : '' }}>School Representative</option>
                        </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>
                    <!-- Password -->
                    <div class="mb-4">
                        <x-input-label for="password" :value="__('Password')" class="block text-gray-700 text-sm font-bold mb-2" />
                        <x-text-input id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="block text-gray-700 text-sm font-bold mb-2" />
                        <x-text-input id="password_confirmation" name="password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Data Privacy Agreement -->
                    <div class="mb-4">
                        <input type="checkbox" id="agree" name="agree" required>
                        <label for="agree" class="text-gray-700 text-sm font-bold mb-2"> I have read and accept
                            <button type="button" id="privacy-button" class="text-blue-500 underline" data-bs-toggle="modal" data-bs-target="#privacyModal">Data Privacy Policy</button> In Behalf of the User
                        </label>
                        <x-input-error :messages="$errors->get('agree')" class="mt-2" />
                    </div>
            
                        </div>
                    </form>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="saveUser" class="btn btn-primary">Save User</button>
                    </div>
                </div>
                </div>
            </div>  

        </div>

    <!-- edit modal area -->
    <!-- Bootstrap Modal for Editing Users -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    @csrf
                    <input type="hidden" id="edituserid" name="userid" value="">
                    
                    <!-- First Name -->
                    <div class="mb-4">
                        <label for="editFirstName" class="form-label">First Name</label>
                        <input type="text" id="editFirstName" name="first_name" class="form-control">
                    </div>

                    <!-- Last Name -->
                    <div class="mb-4">
                        <label for="editLastName" class="form-label">Last Name</label>
                        <input type="text" id="editLastName" name="last_name" class="form-control">
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="text" id="editEmail" name="email" class="form-control">
                    </div>

                    <!-- Role -->
                    <div class="mb-4">
                        <label for="editRole" class="form-label">Role</label>
                        <select id="editRole" name="role" class="form-control" required>
                            <option value="" disabled selected>Select Role</option>
                                    <option value="Captain" {{ old('role') == 'Captain' ? 'selected' : '' }}> Captain</option>
                                    <option value="Coach" {{ old('role') == 'Coach' ? 'selected' : '' }}>Coach</option>
                                    <option value="School Representative" {{ old('role') == 'School Representative' ? 'selected' : '' }}> School Representative</option>
                                    <option value="Guest Account" {{ old('role') == 'Guest Account' ? 'selected' : '' }}>Guest Account</option>
                        </select>
                    </div>

                    <!-- School Name -->
                    <div class="mb-4">
                        <label for="editSchoolName" class="form-label">School Name</label>
                        <select id="editSchoolName" name="school_name" class="form-control" required>
                            <option value="" disabled selected>School Name</option>

                                            <option value="Ilaya Barangka Elementary School" {{ old('school_name') == 'Ilaya Barangka Elementary School' ? 'selected' : '' }}>Ilaya Barangka Elementary School</option>
                                            <option value="Aquinas School" {{ old('school_name') == 'Aquinas School' ? 'selected' : '' }}>Aquinas School</option>
                                            <option value="Assemblywoman Felicita G. Berdino Memorial Trade School" {{ old('school_name') == 'Assemblywoman Felicita G. Berdino Memorial Trade School' ? 'selected' : '' }}>Assemblywoman Felicita G. Berdino Memorial Trade School</option>
                                            <option value="Batasan National High School" {{ old('school_name') == 'Batasan National High School' ? 'selected' : '' }}>Batasan National High School</option>
                                            <option value="Canossa Academy Lipa" {{ old('school_name') == 'Canossa Academy Lipa' ? 'selected' : '' }}>Canossa Academy Lipa</option>
                                            <option value="Catmon Integrated School" {{ old('school_name') == 'Catmon Integrated School' ? 'selected' : '' }}>Catmon Integrated School</option>
                                            <option value="Chiang Kai Shek College" {{ old('school_name') == 'Chiang Kai Shek College' ? 'selected' : '' }}>Chiang Kai Shek College</option>
                                            <option value="College of St. Catherine, Quezon City" {{ old('school_name') == 'College of St. Catherine, Quezon City' ? 'selected' : '' }}>College of St. Catherine, Quezon City</option>
                                            <option value="Community Learning Academy of San Jose" {{ old('school_name') == 'Community Learning Academy of San Jose' ? 'selected' : '' }}>Community Learning Academy of San Jose</option>
                                            <option value="De La Salle Araneta University" {{ old('school_name') == 'De La Salle Araneta University' ? 'selected' : '' }}>De La Salle Araneta University</option>
                                            <option value="Dominican School, Manila" {{ old('school_name') == 'Dominican School, Manila' ? 'selected' : '' }}>Dominican School, Manila</option>
                                            <option value="Domuschola International School" {{ old('school_name') == 'Domuschola International School' ? 'selected' : '' }}>Domuschola International School</option>
                                            <option value="Don Antonio De Zuzuarregui Sr Memorial Academy" {{ old('school_name') == 'Don Antonio De Zuzuarregui Sr Memorial Academy' ? 'selected' : '' }}>Don Antonio De Zuzuarregui Sr Memorial Academy</option>
                                            <option value="Emilio Aguinaldo College, Manila" {{ old('school_name') == 'Emilio Aguinaldo College, Manila' ? 'selected' : '' }}>Emilio Aguinaldo College, Manila</option>
                                            <option value="Ernesto Rondon High School" {{ old('school_name') == 'Ernesto Rondon High School' ? 'selected' : '' }}>Ernesto Rondon High School</option>
                                            <option value="Escuela De Sophia School of Caloocan Inc." {{ old('school_name') == 'Escuela De Sophia School of Caloocan Inc.' ? 'selected' : '' }}>Escuela De Sophia School of Caloocan Inc.</option>
                                            <option value="FEU Diliman" {{ old('school_name') == 'FEU Diliman' ? 'selected' : '' }}>FEU Diliman</option>
                                            <option value="FEU Roosevelt Marikina" {{ old('school_name') == 'FEU Roosevelt Marikina' ? 'selected' : '' }}>FEU Roosevelt Marikina</option>
                                            <option value="FEU Roosevelt Rodriguez" {{ old('school_name') == 'FEU Roosevelt Rodriguez' ? 'selected' : '' }}>FEU Roosevelt Rodriguez</option>
                                            <option value="Gracel Christian College Foundation" {{ old('school_name') == 'Gracel Christian College Foundation' ? 'selected' : '' }}>Gracel Christian College Foundation</option>
                                            <option value="Grant Cecilia Integrated School" {{ old('school_name') == 'Grant Cecilia Integrated School' ? 'selected' : '' }}>Grant Cecilia Integrated School</option>
                                            <option value="Holy Angel School of Caloocan Inc." {{ old('school_name') == 'Holy Angel School of Caloocan Inc.' ? 'selected' : '' }}>Holy Angel School of Caloocan Inc.</option>
                                            <option value="Holy Infant Montessori Center" {{ old('school_name') == 'Holy Infant Montessori Center' ? 'selected' : '' }}>Holy Infant Montessori Center</option>
                                            <option value="Holy Trinity Academy" {{ old('school_name') == 'Holy Trinity Academy' ? 'selected' : '' }}>Holy Trinity Academy</option>
                                            <option value="HSL-Braille College Inc." {{ old('school_name') == 'HSL-Braille College Inc.' ? 'selected' : '' }}>HSL-Braille College Inc.</option>
                                            <option value="Integrated School of Science/AIMS" {{ old('school_name') == 'Integrated School of Science/AIMS' ? 'selected' : '' }}>Integrated School of Science/AIMS</option>
                                            <option value="Jaime Cardinal Sin Learning Center" {{ old('school_name') == 'Jaime Cardinal Sin Learning Center' ? 'selected' : '' }}>Jaime Cardinal Sin Learning Center</option>
                                            <option value="Jesus Christ Saves Global Outreach Christian Academy" {{ old('school_name') == 'Jesus Christ Saves Global Outreach Christian Academy' ? 'selected' : '' }}>Jesus Christ Saves Global Outreach Christian Academy</option>
                                            <option value="Jesus is Lord College Foundation" {{ old('school_name') == 'Jesus is Lord College Foundation' ? 'selected' : '' }}>Jesus is Lord College Foundation</option>
                                            <option value="Jesus Reigns Christian Academy" {{ old('school_name') == 'Jesus Reigns Christian Academy' ? 'selected' : '' }}>Jesus Reigns Christian Academy</option>
                                            <option value="Juan R. Liwag Memorial High School" {{ old('school_name') == 'Juan R. Liwag Memorial High School' ? 'selected' : '' }}>Juan R. Liwag Memorial High School</option>
                                            <option value="Justice Cecilia Munoz Palma High School" {{ old('school_name') == 'Justice Cecilia Munoz Palma High School' ? 'selected' : '' }}>Justice Cecilia Munoz Palma High School</option>
                                            <option value="Kings Montessori School" {{ old('school_name') == 'Kings Montessori School' ? 'selected' : '' }}>Kings Montessori School</option>
                                            <option value="Lakandula High School" {{ old('school_name') == 'Lakandula High School' ? 'selected' : '' }}>Lakandula High School</option>
                                            <option value="Leandro Locsin Integrated School" {{ old('school_name') == 'Leandro Locsin Integrated School' ? 'selected' : '' }}>Leandro Locsin Integrated School</option>
                                            <option value="Malabon National High School" {{ old('school_name') == 'Malabon National High School' ? 'selected' : '' }}>Malabon National High School</option>
                                            <option value="Manggahan High School" {{ old('school_name') == 'Manggahan High School' ? 'selected' : '' }}>Manggahan High School</option>
                                            <option value="Manila Cathedral School" {{ old('school_name') == 'Manila Cathedral School' ? 'selected' : '' }}>Manila Cathedral School</option>
                                            <option value="Manuel G. Araullo High School" {{ old('school_name') == 'Manuel G. Araullo High School' ? 'selected' : '' }}>Manuel G. Araullo High School</option>
                                            <option value="Milestone Innovative Academy" {{ old('school_name') == 'Milestone Innovative Academy' ? 'selected' : '' }}>Milestone Innovative Academy</option>
                                            <option value="Moreh Academy Inc." {{ old('school_name') == 'Moreh Academy Inc.' ? 'selected' : '' }}>Moreh Academy Inc.</option>
                                            <option value="Mother of Perpetual Help School, Inc." {{ old('school_name') == 'Mother of Perpetual Help School, Inc.' ? 'selected' : '' }}>Mother of Perpetual Help School, Inc.</option>
                                            <option value="Mystical Rose School of Bulacan, Inc." {{ old('school_name') == 'Mystical Rose School of Bulacan, Inc.' ? 'selected' : '' }}>Mystical Rose School of Bulacan, Inc.</option>                                                
                                            <option value="Mystical Rose School of Caloocan, Inc." {{ old('school_name') == 'Mystical Rose School of Caloocan, Inc.' ? 'selected' : '' }}>Mystical Rose School of Caloocan, Inc.</option>
                                            <option value="Nazarene Catholic School" {{ old('school_name') == 'Nazarene Catholic School' ? 'selected' : '' }}>Nazarene Catholic School</option>
                                            <option value="New Era High School" {{ old('school_name') == 'New Era High School' ? 'selected' : '' }}>New Era High School</option>
                                            <option value="New Prodon Academy of Valenzuela" {{ old('school_name') == 'New Prodon Academy of Valenzuela' ? 'selected' : '' }}>New Prodon Academy of Valenzuela</option>
                                            <option value="Northern Rizal Yorklin School" {{ old('school_name') == 'Northern Rizal Yorklin School' ? 'selected' : '' }}>Northern Rizal Yorklin School</option>
                                            <option value="Nuestra Senora De Guia Academy" {{ old('school_name') == 'Nuestra Senora De Guia Academy' ? 'selected' : '' }}>Nuestra Senora De Guia Academy</option>
                                            <option value="Nuestra Senora Del Carmen Institute" {{ old('school_name') == 'Nuestra Senora Del Carmen Institute' ? 'selected' : '' }}>Nuestra Senora Del Carmen Institute</option>
                                            <option value="Our Lady of Fatima Catholic School, Bacood" {{ old('school_name') == 'Our Lady of Fatima Catholic School, Bacood' ? 'selected' : '' }}>Our Lady of Fatima Catholic School, Bacood</option>
                                            <option value="Our Lady of Fatima University Quezon City" {{ old('school_name') == 'Our Lady of Fatima University Quezon City' ? 'selected' : '' }}>Our Lady of Fatima University Quezon City</option>
                                            <option value="Our Lady of Fatima University Valenzuela" {{ old('school_name') == 'Our Lady of Fatima University Valenzuela' ? 'selected' : '' }}>Our Lady of Fatima University Valenzuela</option>
                                            <option value="Our Lady of Peace School" {{ old('school_name') == 'Our Lady of Peace School' ? 'selected' : '' }}>Our Lady of Peace School</option>
                                            <option value="PAREF Rosehill" {{ old('school_name') == 'PAREF Rosehill' ? 'selected' : '' }}>PAREF Rosehill</option>
                                            <option value="Philippine Academy of Sakya" {{ old('school_name') == 'Philippine Academy of Sakya' ? 'selected' : '' }}>Philippine Academy of Sakya</option>
                                            <option value="Riveridge School Inc." {{ old('school_name') == 'Riveridge School Inc.' ? 'selected' : '' }}>Riveridge School Inc.</option>
                                            <option value="Rizal High School" {{ old('school_name') == 'Rizal High School' ? 'selected' : '' }}>Rizal High School</option>
                                            <option value="Sacred Heart Academy of Novaliches" {{ old('school_name') == 'Sacred Heart Academy of Novaliches' ? 'selected' : '' }}>Sacred Heart Academy of Novaliches</option>
                                            <option value="Sacred Heart of Jesus Catholic School" {{ old('school_name') == 'Sacred Heart of Jesus Catholic School' ? 'selected' : '' }}>Sacred Heart of Jesus Catholic School</option>
                                            <option value="Sampaguita High School" {{ old('school_name') == 'Sampaguita High School' ? 'selected' : '' }}>Sampaguita High School</option>
                                            <option value="San Felipe Neri Catholic School" {{ old('school_name') == 'San Felipe Neri Catholic School' ? 'selected' : '' }}>San Felipe Neri Catholic School</option>
                                            <option value="St. Benedict School of Novaliches" {{ old('school_name') == 'St. Benedict School of Novaliches' ? 'selected' : '' }}>St. Benedict School of Novaliches</option>
                                            <option value="St. John's Wort Montessori School" {{ old('school_name') == 'St. John Wort Montessori School' ? 'selected' : '' }}>St. John's Wort Montessori School</option>
                                            <option value="St. Louis College Valenzuela" {{ old('school_name') == 'St. Louis College Valenzuela' ? 'selected' : '' }}>St. Louis College Valenzuela</option>
                                            <option value="St. Mary's Angel College - Valenzuela" {{ old('school_name') == 'St. Marys Angel College - Valenzuela' ? 'selected' : '' }}>St. Mary's Angel College - Valenzuela</option>
                                            <option value="St. Patrick School of Quezon City" {{ old('school_name') == 'St. Patrick School of Quezon City' ? 'selected' : '' }}>St. Patrick School of Quezon City</option>
                                            <option value="St. Stephen High School" {{ old('school_name') == 'St. Stephen High School' ? 'selected' : '' }}>St. Stephen High School</option>
                                            <option value="St. Theresa's College, Quezon City" {{ old('school_name') == 'St. Theresas College, Quezon City' ? 'selected' : '' }}>St. Theresa's College, Quezon City</option>
                                            <option value="System Plus Computer College - Caloocan Campus" {{ old('school_name') == 'System Plus Computer College - Caloocan Campus' ? 'selected' : '' }}>System Plus Computer College - Caloocan Campus</option>
                                            <option value="The Cardinal Academy Inc." {{ old('school_name') == 'The Cardinal Academy Inc.' ? 'selected' : '' }}>The Cardinal Academy Inc.</option>
                                            <option value="Trinitas College" {{ old('school_name') == 'Trinitas College' ? 'selected' : '' }}>Trinitas College</option>
                                            <option value="Trinitas School of Sta Maria" {{ old('school_name') == 'Trinitas School of Sta Maria' ? 'selected' : '' }}>Trinitas School of Sta Maria</option>
                                            <option value="UST Angelicum College" {{ old('school_name') == 'UST Angelicum College' ? 'selected' : '' }}>UST Angelicum College</option>
                                            <option value="Villagers Montessori College" {{ old('school_name') == 'Villagers Montessori College' ? 'selected' : '' }}>Villagers Montessori College</option>
                                            <option value="Young Achievers School of Caloocan Inc." {{ old('school_name') == 'Young Achievers School of Caloocan Inc.' ? 'selected' : '' }}>Young Achievers School of Caloocan Inc.</option>
                                            
                                        </select>
                            </div>

                                <!-- Password -->
                            <div class="mb-4">
                                <label for="editPassword" class="form-label">Password</label>
                                <input type="password" id="editPassword" name="password" class="form-control">
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label for="editConfirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" id="editConfirmPassword" name="password_confirmation" class="form-control">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="EditCoach" class="btn btn-primary">Save User</button>
                        <button type="button" class="btn btn-danger delete-btn" data-id="{{ $user->id }}">Delete User</button>
                    </div>
                </div>
            </div>
        </div>


    <!-- script area -->
    <script type="module" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.esm.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- ajax area -->
    <script>
         function updateStatus(userId, action) {
            console.log(`Updating User ID: ${userId}, Action: ${action}`);
            
            fetch(`/admin/update-status/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ action: action })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const statusElement = document.getElementById('userStatus');
                    if (statusElement) {
                        statusElement.textContent = action === 'activate' ? 'Active' : 'Inactive';
                    }
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Failed to update status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating status');
            });
        }
    </script>

    <script>
          function closeModal() {
                document.getElementById('userModal').classList.add('hidden');
            }

            function openModal(is_active, firstName, lastName, schoolName, role, sports, teams, sport_category, name, birth_date, gender, userId) {
            // Update Name with Role
            document.getElementById('modalUserName').innerText = `${firstName} ${lastName} (${role})`;
            
            // School, Sport, Team Details
            document.getElementById('modalUserSchool').innerText = schoolName;
            document.getElementById('modalUserSportCategory').innerText = sport_category;
            document.getElementById('modalUserTeamName').innerText = name;
            
            // Birth Date and Gender
            document.getElementById('modalUserBirthDate').innerText = new Date(birth_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            console.log('Birth date:', birth_date);
            document.getElementById('modalUserGender').innerText = `${gender}`;

            // Activation Badge Logic
            let activationBadge = document.getElementById('activationBadge');
            if (is_active) {
                activationBadge.classList.add('bg-green-100', 'text-green-800', 'dark:bg-green-900', 'dark:text-green-300');
                activationBadge.classList.remove('bg-red-100', 'text-red-800', 'dark:bg-red-900', 'dark:text-red-300');
                activationBadge.innerHTML = `<span class="w-2 h-2 me-1 bg-green-500 rounded-full"></span> Active`;
            } else {
                activationBadge.classList.add('bg-red-100', 'text-red-800', 'dark:bg-red-900', 'dark:text-red-300');
                activationBadge.classList.remove('bg-green-100', 'text-green-800', 'dark:bg-green-900', 'dark:text-green-300');
                activationBadge.innerHTML = `<span class="w-2 h-2 me-1 bg-red-500 rounded-full"></span> Unactivate`;
            }

            // Set button actions with the correct user ID
            document.getElementById('activateButton').onclick = function() { updateStatus(userId, 'activate'); };
            document.getElementById('deactivateButton').onclick = function() { updateStatus(userId, 'deactivate'); };

            // Show the modal
            document.getElementById('userModal').classList.remove('hidden');

        }
     </script>

     <script>

$(document).ready(function() {
        // Toggle Kebab Menu Dropdown
        $('[id^=kebabMenuButton]').click(function(event) {
            event.stopPropagation(); // Prevent triggering modal when clicking the kebab
            const id = $(this).attr('id').split('-')[1];
            $(`#kebabMenuDropdown-${id}`).toggleClass('hidden');
        });

        // Close dropdown when clicked outside
        $(document).click(function(e) {
            if (!$(e.target).closest('.kebab-menu').length && !$(e.target).closest('[id^=kebabMenuDropdown]').length) {
                $('[id^=kebabMenuDropdown]').addClass('hidden');
            }
        });
    });

    // Edit User modal
    document.querySelectorAll('[data-bs-target="#editUserModal"]').forEach(button => {
    button.addEventListener('click', function () {
        const userId = this.getAttribute('data-user-id');
        const userFirstName = this.getAttribute('data-user-firstname');
        const userLastName = this.getAttribute('data-user-lastname');
        const userEmail = this.getAttribute('data-user-email');
        const userRole = this.getAttribute('data-user-role');
        const userSchoolName = this.getAttribute('data-user-schoolname');
        
        // Populate the modal form
        document.getElementById('edituserid').value = userId;
        document.getElementById('editFirstName').value = userFirstName;
        document.getElementById('editLastName').value = userLastName;
        document.getElementById('editEmail').value = userEmail;
        document.getElementById('editRole').value = userRole;
        document.getElementById('editSchoolName').value = userSchoolName;

        // Set the delete button's data-id attribute
        document.querySelector('#editUserModal .delete-btn').setAttribute('data-id', userId);

        // Clear password fields
        document.getElementById('editPassword').value = '';
        document.getElementById('editConfirmPassword').value = '';
    });
});

        // Update user
        document.getElementById('EditCoach').addEventListener('click', function () {
            var formData = new FormData(document.getElementById('editUserForm')); // Collect form data

            // Append the user ID from the hidden input field in the form to FormData
            var userid = document.getElementById('edituserid').value; // User ID
            formData.append('id', userid); // Append the user ID to formData

            // Debug: Check form data
            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            $.ajax({
                type: 'POST',
                url: '{{ route("admin.users.update") }}',
                data: formData, // Send FormData directly
                contentType: false, // Required for FormData
                processData: false, // Required for FormData
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message); // Show success message
                    window.location.reload(); // Reload page after success
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = 'Validation Error:\n';
                        for (var field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                errorMessage += errors[field].join('\n') + '\n';
                            }
                        }
                        alert(errorMessage); // Show validation error message
                    } else {
                        console.error('Error updating user data:', xhr);
                        alert('Error updating user data'); // Show error message
                    }
                }
            });
        });

        //delete ajax
        $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id'); // Get the game ID from the button's data-id attribute
        console.log('Attempting to delete game with ID:', id);

        if (confirm('Are you sure you want to delete this match?')) {
            $.ajax({
                url: '{{ route('admin.delete.game') }}', // Use the correct named route for game deletion
                type: 'DELETE', // Use DELETE request
                data: { id: id }, // Pass the game ID
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                },
                success: function(response) {
                    console.log('Delete response:', response);
                    if (response.status === 200) {
                        alert(response.message);
                        // Remove the game element from the DOM
                        $('div[data-game-id="' + id + '"]').remove();
                        // Optionally reload the page to reflect changes
                        window.location.reload(); // Reload the page after success
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Delete error:', xhr.responseText);
                    alert('Error: ' + error);
                }
            });
        }
    });
    </script>

    <script>
        //added for storing user
        document.getElementById('saveUser').addEventListener('click', function() {
        var userForm = document.getElementById('addUserForm');
        var formData = new FormData(userForm);

        var userData = {
            first_name: formData.get('first_name'),
            last_name: formData.get('last_name'),
            email: formData.get('email'),
            password: formData.get('password'),
            password_confirmation: formData.get('password_confirmation'),
            birth_date: formData.get('birth_date'),
            gender: formData.get('gender'),
            school_name: formData.get('school_name'),
            role: formData.get('role'),
            is_active: formData.get('is_active')
        };

        $.ajaxSetup({
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/admin/store-user-accounts',
            type: 'POST',
            data: JSON.stringify(userData),
            success: function(response) {
                alert(response.message);
                window.location.href = "{{ route('admin.coach-approval') }}";
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = 'Validation Error:\n';
                    for (var field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            errorMessage += errors[field].join('\n') + '\n';
                        }
                    }
                    alert(errorMessage);
                } else {
                    alert('Error saving user data');
                }
            }
        });
        });
        </script>

</x-app-layout>