<x-app-layout>
    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        
    </section>


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
                $array = [
                    ['Summary of Players', 'August 1, 2024', 0],
                    ['Official Gallery of Athletes', 'May 15, 2024', 0],
                    ['Official Gallery of Coaches', 'July 10, 2024', 0],
                    ['Parental Consent', 'August 1, 2024', 0],
                    ['Photocopy of School ID', 'August 1, 2024', 0],
                    ['Athletes Certificate of Enrolment', 'June 30, 2024', 0],
                    ['PSA Birth Certificate', 'July 20, 2024', 0],
                ];
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
                    <div class="col-span-1 text-green-700">
                        @if ($a[0] == 'Summary of Players')
                            <a href="{{ route('my-documents_sub', 'SummaryOfPlayers') }}">
                                <ion-icon name="document"></ion-icon>
                            </a>
                        @elseif ($a[0] == 'Gallery of Players')
                            <a href="{{ route('my-documents_sub', 'GalleryOfPlayers') }}">
                                <ion-icon name="document"></ion-icon>
                            </a>
                        @elseif ($a[0] == 'Gallery of Coaches')
                            <a href="{{ route('my-documents_sub', 'GalleryOfCoaches') }}">
                                <ion-icon name="document"></ion-icon>
                            </a>
                        @elseif ($a[0] == 'Parental Consent')
                            <a href="{{ route('my-documents_sub', 'ParentalConsent') }}">
                                <ion-icon name="document"></ion-icon>
                            </a>
                        @elseif ($a[0] == 'Photocopy of School ID')
                            <a href="{{ route('my-documents_sub', 'PhotocopyOfSchoolID') }}">
                                <ion-icon name="document"></ion-icon>
                            </a>
                        @elseif ($a[0] == 'Certificate of Registration')
                            <a href="{{ route('my-documents_sub', 'CertificateOfRegistration') }}">
                                <ion-icon name="document"></ion-icon>
                            </a>
                        @elseif ($a[0] == 'BirthCertificate')
                            <a href="{{ route('my-documents_sub', 'BirthCertificate') }}">
                                <ion-icon name="document"></ion-icon>
                            </a>
                        @else
                            <a href="{{ route('my-documents_sub', str_replace(' ', '', $a[0])) }}">
                                <ion-icon name="document"></ion-icon>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-app-layout>