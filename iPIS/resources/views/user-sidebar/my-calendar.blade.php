<x-app-layout>

               
                        <div class="container mx-auto p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h1 class="text-2xl font-bold">Calendar</h1>
                
                            </div>
                            <p class="mb-6">Manage and schedule games</p>
                            
                            <div class="flex justify-between items-center mb-6">
                                <div class="relative">
                                    <input type="text" placeholder="Search..." class="border rounded-md py-2 px-4 pr-10">
                                    <svg class="w-5 h-5 text-gray-500 absolute right-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center">
                                    <span class="mr-2">View Filter</span>
                                    <select class="border rounded-md py-2 px-4">
                                        <option>Monthly</option>
                                    </select>
                                </div>
                            </div>

                            <div class="border rounded-lg overflow-hidden">
                                <table class="w-full">
                                    <thead>
                                        <tr class="bg-green-800 text-white">
                                            <th class="py-2 px-4 border-r">Sunday</th>
                                            <th class="py-2 px-4 border-r">Monday</th>
                                            <th class="py-2 px-4 border-r">Tuesday</th>
                                            <th class="py-2 px-4 border-r">Wednesday</th>
                                            <th class="py-2 px-4 border-r">Thursday</th>
                                            <th class="py-2 px-4 border-r">Friday</th>
                                            <th class="py-2 px-4">Saturday</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="h-32">
                                            <td class="border p-2 align-top">7</td>
                                            <td class="border p-2 align-top">8</td>
                                            <td class="border p-2 align-top">9</td>
                                            <td class="border p-2 align-top">10</td>
                                            <td class="border p-2 align-top">
                                                11
                                                <div class="bg-green-700 text-white text-xs p-1 mt-1 rounded">FITGC - DLSU</div>
                                            </td>
                                            <td class="border p-2 align-top">
                                                12
                                                <div class="bg-green-700 text-white text-xs p-1 mt-1 rounded">USTET - DLSUT</div>
                                                <div class="bg-green-700 text-white text-xs p-1 mt-1 rounded">FITGC - USTET</div>
                                            </td>
                                            <td class="border p-2 align-top">13</td>
                                        </tr>
                                        <tr class="h-32">
                                            <td class="border p-2 align-top">14</td>
                                            <td class="border p-2 align-top">15</td>
                                            <td class="border p-2 align-top">16</td>
                                            <td class="border p-2 align-top">17</td>
                                            <td class="border p-2 align-top">18</td>
                                            <td class="border p-2 align-top">
                                                19
                                                <div class="bg-green-700 text-white text-xs p-1 mt-1 rounded">USTET - DLSUT</div>
                                            </td>
                                            <td class="border p-2 align-top">20</td>
                                        </tr>
                                        <tr class="h-32">
                                            <td class="border p-2 align-top">21</td>
                                            <td class="border p-2 align-top">22</td>
                                            <td class="border p-2 align-top">23</td>
                                            <td class="border p-2 align-top">24</td>
                                            <td class="border p-2 align-top">25</td>
                                            <td class="border p-2 align-top">26</td>
                                            <td class="border p-2 align-top">27</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                
</x-app-layout>