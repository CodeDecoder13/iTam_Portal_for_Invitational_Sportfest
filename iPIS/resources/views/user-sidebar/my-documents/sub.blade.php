<x-app-layout>
    <section class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">{{ $type }}</h1>
        <h3>Update {{ $type }}</h3>
        <div class="grid grid-cols-1 mt-5">
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="col-span-4">Player Name</div>
                <div class="col-span-4">Last Updated</div>
                <div class="col-span-3">Status</div>
                <div class="col-span-1">Action</div>
            </div>
            @php
                $array = [
                    ['Andrei Joseph O. Garcia', 'April 20,2024', 1],
                    ['Andrei Joseph O. Garcia', 'April 20,2024', 2],
                    ['Andrei Joseph O. Garcia', 'April 20,2024', 0],
                ];
            @endphp
            @foreach ($array as $a)
                <div class="grid grid-cols-12 px-4 py-3 rounded-lg border mt-2">
                    <div class="col-span-4">{{ $a[0] }}</div>
                    <div class="col-span-4">{{ $a[1] }}</div>
                    <div
                        class="col-span-3
                    
                    @switch($a[2])
                            @case(1)
                            text-orange-700
                            @break

                            @case(2)
                            text-green-700
                            @break

                            @case(0)
                            text-slate-700  
                            @break

                            @default
                        @endswitch
                     font-semibold">
                        @switch($a[2])
                            @case(1)
                                For Review
                            @break

                            @case(2)
                                Approved
                            @break

                            @case(0)
                                No File Attached
                            @break

                            @default
                        @endswitch

                    </div>

                    
                    <div class="col-span-1 text-green-700">@switch($a[2])
                        @case(1)
                            <ion-icon name="document"></ion-icon>
                        @break

                        @case(2)
                            <ion-icon name="document"></ion-icon>
                        @break

                        @case(0)
                            <ion-icon name="cloud-upload"></ion-icon>
                        @break

                        @default
                    @endswitch</div>
                </div>
            @endforeach
        </div>
    </section>
</x-app-layout>