<x-app-layout>
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
    </section>
    <section class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">School Management</h1>
        <h3>Manage and Organize School Players</h3>
        <div class="w-full flex justify-end items-end mb-4">
            <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <span class="text-lg mr-1">+</span>Add New Coach
            </button>
        </div>
        <form method="GET" action="{{ route('admin.school-management') }}" class="grid grid-cols-1 mt-5">
            <div class="grid grid-cols-12 gap-4 px-4 py-3 rounded-lg bg-gray-100">
                <div class="col-span-5">
                    <input type="text" name="search" placeholder="Search" value="{{ request('search') }}"
                        class="w-8/12 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-span-1 flex items-center">Filtered By:</div>
                <div class="col-span-2">
                    <select name="sport"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sports</option>
                        <!-- Add options dynamically or statically here -->
                    </select>
                </div>
                <div class="col-span-2">
                    <select name="team"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Team</option>
                        <!-- Add options dynamically or statically here -->
                    </select>
                </div>
                <div class="col-span-2">
                    <select name="status"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Status</option>
                        <!-- Add options dynamically or statically here -->
                    </select>
                </div>
            </div>
            <button type="submit" class="hidden"></button>
        </form>
        
        <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
            <div class="col-span-3">School Name</div>
            <div class="col-span-3">Coach Name</div>
            <div class="col-span-3">Status</div>
            <div class="col-span-3">Action</div>
        </div>
        @foreach ($users as $user)
            <div class="grid grid-cols-12 px-4 py-3 rounded-lg border mt-2">
                <div class="col-span-3">{{ $user->school_name ?? 'N/A' }}</div>
                <div class="col-span-3">{{ $user->first_name . ' ' . $user->last_name }}</div>
                <div class="col-span-3">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </div>
                <div class="col-span-3">
                    <a href="{{ route('admin.card-school-management', $user->id) }}" class="btn btn-primary">View</a>
                    
                </div>
            </div>
        @endforeach
        
        <!-- Pagination links -->
        <div class="mt-4">
            {{ $users->links() }}
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
            







   

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

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
                window.location.href = "{{ route('admin.school-management') }}";
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
