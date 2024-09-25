<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">School Management Details</h1>
        
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-xl font-semibold mb-2">{{ $team->coach->school_name ?? 'No School Assigned' }}</h2>
            
            <div class="mb-4">
                <p><strong>Coach:</strong> {{ $team->coach ? $team->coach->first_name . ' ' . $team->coach->last_name : 'No Coach Assigned' }}</p>
                <p><strong>Status:</strong> 
                    @if($team->coach)
                        {{ $team->coach->is_active ? 'Active' : 'Inactive' }}
                    @else
                        N/A
                    @endif
                </p>
                <p><strong>Players:</strong> {{ $team->players->count() }}</p>
            </div>
            
            <a href="{{ route('admin.school-management') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Back to School Management
            </a>
        </div>

          <!-- New cards section -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-8">

            <!-- Player Management card -->
            <div class="bg-orange-500 rounded-lg overflow-hidden shadow-lg">
                <div class="flex flex-col h-full">
                    <div class="h-1/2 bg-cover bg-center" style="background-image: url('{{ asset('images/player-management.png') }}'); height: 300%;"></div>
                    <div class="p-5 flex-grow">
                        <h3 class="text-white font-bold mb-2">Player Management</h3>
                        <p class="text-white text-sm mb-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                        <button class="bg-white text-orange-500 font-bold py-2 px-4 rounded">
                            View Players
                        </button>
                    </div>
                </div>
            </div>

            <!-- Team Management card -->
            <div class="bg-purple-500 rounded-lg overflow-hidden shadow-lg">
                <div class="p-5">
                    <div class="text-white text-4xl mb-2">CSS3</div>
                    <h3 class="text-white font-bold mb-2">Team Management</h3>
                    <p class="text-white text-sm mb-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    <button class="bg-white text-purple-500 font-bold py-2 px-4 rounded">
                        View Team
                    </button>
                </div>
            </div>

            <!-- Document Management card -->
            <div class="bg-blue-500 rounded-lg overflow-hidden shadow-lg">
                <div class="p-5">
                    <div class="text-white text-4xl mb-2">HTML5</div>
                    <h3 class="text-white font-bold mb-2">Document Management</h3>
                    <p class="text-white text-sm mb-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    <button class="bg-white text-blue-500 font-bold py-2 px-4 rounded">
                        View Document
                    </button>
                </div>
            </div>

            <!-- Logs card -->
            <div class="bg-green-500 rounded-lg overflow-hidden shadow-lg">
                <div class="p-5">
                    <div class="text-white text-4xl mb-2">JS</div>
                    <h3 class="text-white font-bold mb-2">Logs</h3>
                    <p class="text-white text-sm mb-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    <button class="bg-white text-green-500 f    ont-bold py-2 px-4 rounded">
                        View Logs
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>