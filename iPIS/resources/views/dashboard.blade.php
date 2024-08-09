<x-app-layout>
    <div class="flex-grow px-4 py-6">
        <h1 class="text-2xl">Dashboard</h1>
    <!-- Fetch Current User Login -->
    @if (Auth::check())
        <h3 class="text-lg mb-8">Welcome, Coach <span class="underline">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span></h3>
    @endif

    
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-lg shadow-md">
            <div class="bg-green-800 text-white px-4 py-2 rounded-t-lg">
                <h3 class="text-xl font-bold mb-2">Recent Activities</h3>
            </div>
            <div class="rounded-b-lg p-4 py-2">
                <ul>
                    <li class="border text-xs p-2 flex">
                        <div class="text-green-700 text-xl">
                            <ion-icon name="document"></ion-icon>
                        </div>
                        <div>
                            <span class="font-bold">Document Comment:</span> "Full name and address doesn’t match ID
                            details” - RAC Representative"
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="rounded-lg shadow-md">
            <div class="bg-green-800 text-white px-4 py-2 rounded-t-lg">
                <h3 class="text-xl font-bold mb-2">Recent Activities</h3>
            </div>
            <div class="rounded-b-lg p-4 py-2">
                <ul>
                    <li class="border text-xs p-2 flex">
                        <div class="w-8/12 border-e-2 flex-grow flex">
                            <div><img width="15" class="img-fluid" src="/images/userlogo.png" /></div>
                            <div class="text-center">
                                <div><span>FITGC</span> VS <span>MPTGC</span></div>
                                <div class="text-xs">Men’s Basketball (D)</div>
                            </div>
                            <div><img width="15" class="img-fluid" src="/images/userlogo.png" /></div>
                        </div>
                        <div class="w-4/12 text-end">
                            <p class="font-bold">Sept. 11, 2024</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="rounded-lg shadow-md">
            <div class="bg-green-800 text-white px-4 py-2 rounded-t-lg">
                <h3 class="text-xl font-bold mb-2">Standing</h3>
            </div>
            <div class="rounded-b-lg p-4 py-2">
                <ol type="1">
                    <li type="1" class="border text-xs flex w-full">
                        <div class="bg-yellow-100 p-2 w-1/12">1</div>
                        <div class="my-2 ps-2 w-6/12 border-e-2">FTICGC</div>
                        <div class="p-2 w-3/12">Wins</div>
                        <div class="p-2 w-2/12 font-bold">06</div>
                    </li>
                    <li type="1" class="border text-xs flex w-full">
                        <div class="bg-slate-300 p-2 w-1/12">1</div>
                        <div class="my-2 ps-2 w-6/12 border-e-2">FTICGC</div>
                        <div class="p-2 w-3/12">Wins</div>
                        <div class="p-2 w-2/12 font-bold">06</div>
                    </li>
                    <li type="1" class="border text-xs flex w-full">
                        <div class="bg-red-100 p-2 w-1/12">1</div>
                        <div class="my-2 ps-2 w-6/12 border-e-2">FTICGC</div>
                        <div class="p-2 w-3/12">Wins</div>
                        <div class="p-2 w-2/12 font-bold">06</div>
                    </li>
                </ol>
            </div>
        </div>
        <div class="bg-green-800 text-white rounded-lg shadow-md px-4 py-2">
            <h3 class="text-xl font-bold mb-2">Document Requirements</h3>
        </div>
        
    </section>


    </div>
</x-app-layout>