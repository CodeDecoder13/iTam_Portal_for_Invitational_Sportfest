<x-app-layout>
    <section class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">Document Requirements</h1>
        <h3>Manage and Organize Team Documents</h3>
        <div class="grid grid-cols-1 mt-5">
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="col-span-4">Document</div>
                <div class="col-span-4">Last Updated</div>
                <div class="col-span-3">Status</div>
                <div class="col-span-1">Action</div>
            </div>
            @php
                $array = [['Summary of Players', 'August 1,2024', 1], ['Gallery of Players', 'April 20,2024', 0]];
            @endphp
            @foreach ($array as $a)
                <div class="grid grid-cols-12 px-4 py-3 rounded-lg border mt-2">
                    <div class="col-span-4">{{ $a[0] }}</div>
                    <div class="col-span-4">{{ $a[1] }}</div>
                    <div class="col-span-3 @switch($a[2])
                            @case(1)
                                text-green-700
                            @break

                            @case(0)
                                text-red-700
                            @break
                        @endswitch font-semibold">

                        @switch($a[2])
                            @case(1)
                                Complete
                            @break

                            @case(0)
                                Incomplete
                            @break
                        @endswitch
                    </div>
                    <div class="col-span-1 text-green-700"><a href="/my-documents"></a><ion-icon
                            name="document"></ion-icon>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-app-layout>
