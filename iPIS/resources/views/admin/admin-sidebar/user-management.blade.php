<x-app-layout>
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- Your content here -->
    </section>
    
    <li class="w-full flex justify-end items-end">
       
        <button class="btn btn-success h-2/3" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <sup>+</sup>Add New User
        </button>
    </li>
    <div class="w-full flex justify-end items-end">
        
        <button class="btn btn-success h-2/3" data-bs-toggle="modal" data-bs-target="#addAdminModal">
            <sup>+</sup>Add New Admin
        </button>
    </div>
    
    <!-- User and Admin Management Section -->
    <section class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">User Management</h1>
        <h3>Manage and Organize Users/Admins</h3>


        <div class="grid grid-cols-1 mt-5">
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="col-span-3">Date Created</div>
                <div class="col-span-3">Name</div>
                <div class="col-span-3">Email</div>
                <div class="col-span-1">Role</div>
                <div class="col-span-1">Status</div>
                <div class="col-span-1">Action</div>
            </div>

            <!-- Loop through Users -->
            @foreach ($data['users'] as $user)
                <div class="grid grid-cols-12 px-4 py-3 rounded-lg border mt-2">
                    <div class="col-span-3">{{ $user->created_at->format('F d, Y') }}</div>
                    <div class="col-span-3">{{ $user->first_name }} {{ $user->last_name }}</div>
                    <div class="col-span-3">{{ $user->email }}</div>
                    <div class="col-span-1">{{ $user->role }}</div>
                    <div class="col-span-1">{{ $user->is_active ? 'Active' : 'Inactive' }}</div>
                    <div class="col-span-1">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal">
                            Edit
                        </button>
                    </div>
                </div>
            @endforeach

            <!-- Loop through Admins -->
            @foreach ($data['admins'] as $admin)
                <div class="grid grid-cols-12 px-4 py-3 rounded-lg border mt-2">
                    <div class="col-span-3">{{ $admin->created_at->format('F d, Y') }}</div>
                    <div class="col-span-3">{{ $admin->name }}</div>
                    <div class="col-span-3">{{ $admin->email }}</div>
                    <div class="col-span-1">{{ $admin->role }}</div>
                    <div class="col-span-1">{{ $admin->is_active ? 'Active' : 'Inactive' }}</div>
                    <div class="col-span-1">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editAdminModal">
                            Edit
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

     <!-- Bootstrap Modal for Adding Users -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="accountDetailsModalLabel">Account Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="wizard-form" method="POST" action="{{ route('register') }}">
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
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
                </div>
            </div>  
            
            <!-- Edit a User Modal -->
            <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-green-700">
                            <h5 class="modal-title text-white" id="editUserModalLabel">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editUserForm">
                                

                            </form>
                        </div>



            <!-- Add Admin Modal -->
            <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addAdminModalLabel">Admin Register Page</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h3 class="text-center mb-3 text-xl font-bold">Admin Register Page</h3>

                            <form method="POST" action="{{ route('admin.register') }}">
                                @csrf

                                <!-- Name -->
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <div class="mt-1">
                                        <input id="name" name="name" type="text" required autofocus autocomplete="name" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('name') }}">
                                    </div>
                                </div>

                                <!-- Email Address -->
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <div class="mt-1">
                                        <input id="admin-email" name="email" type="email" required autocomplete="username" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('email') }}">
                                    </div>
                                </div>

                                <!-- Role -->
                                <div class="mb-4">
                                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                                    <div class="mt-1">
                                        <select id="admin_role" name="role" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="" disabled selected>Select Role</option>
                                            <option value="SysAdmin" {{ old('role') == 'SysAdmin' ? 'selected' : '' }}>SysAdmin</option>
                                            <option value="SADO" {{ old('role') == 'SADO' ? 'selected' : '' }}>SADO</option>
                                            <option value="RAC OFFICER" {{ old('role') == 'RAC OFFICER' ? 'selected' : '' }}>RAC OFFICER</option>
                                            <option value="Guest Admin" {{ old('role') == 'Guest Admin' ? 'selected' : '' }}>Guest Admin</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                    <div class="mt-1">
                                        <input id="admin_password" name="password" type="password" required autocomplete="new-password" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                    <div class="mt-1">
                                        <input id="admin_password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Register Admin</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Edit a Admin Modal -->
            <div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-green-700">
                            <h5 class="modal-title text-white" id="editAdminModalLabel">Edit Admin</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editAdminForm">

                            </form>
                        </div>
  
  
  
  

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.x.x/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons/ionicons.esm.js"></script>

    
</x-app-layout>
