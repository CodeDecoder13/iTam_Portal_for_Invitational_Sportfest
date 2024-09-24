<x-app-layout>
    <section class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">Summary Of Players</h1>
        <h3>Fill in player's summary to complete your requirements.</h3>
        <div class="grid grid-cols-1 mt-5">
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="font-bold col-span-2">Jersey No.</div>
                <div class="font-bold col-span-2">Given Name</div>
                <div class="font-bold col-span-2">PSA Birth Certificate</div>
                <div class="font-bold col-span-3">Parental Consent</div>
                <div class="font-bold col-span-3">Status</div>
            </div>
            
            @php
                // Sort the players based on status priority
                $sortedPlayers = $players->sortByDesc(function($player) {
                    $statusPriority = 0; // Default priority

                    if ($player->birth_certificate_status == 3 || $player->parental_consent_status == 3) {
                        $statusPriority = 3; // Rejected
                    } elseif ($player->birth_certificate_status == 2 && $player->parental_consent_status == 2) {
                        $statusPriority = 2; // Approved
                    } elseif ($player->birth_certificate_status == 1 || $player->parental_consent_status == 1) {
                        $statusPriority = 1; // For Review
                    }

                    return $statusPriority;
                });
            @endphp

            @foreach ($sortedPlayers as $player)
                <div class="grid grid-cols-12 px-4 py-3 bg-white rounded-lg border mt-3">
                    <div class="col-span-2">{{ $player->jersey_no }}</div>
                    <div class="col-span-2">{{ $player->first_name }} {{ $player->last_name }}</div>
                    <div class="col-span-2 text-green-700">
                        @if ($player->birth_certificate_status != 0)
                            <a href="#" data-bs-toggle="modal"
                               data-bs-target="#viewBirthCertificateModal-{{ $player->id }}">
                                <ion-icon name="eye"></ion-icon> View Birth Certificate
                            </a>
                        @else
                            <a href="#" data-bs-toggle="modal"
                               data-bs-target="#uploadBirthCertificateModal-{{ $player->id }}">
                                <ion-icon name="cloud-upload"></ion-icon> Upload Birth Certificate
                            </a>
                        @endif
                    </div>
                    <div class="col-span-3 text-green-700">
                        @if ($player->parental_consent_status != 0)
                            <a href="#" data-bs-toggle="modal"
                               data-bs-target="#viewParentalConsentModal-{{ $player->id }}">
                                <ion-icon name="eye"></ion-icon> View Parental Consent
                            </a>
                        @else
                            <a href="#" data-bs-toggle="modal"
                               data-bs-target="#uploadParentalConsentModal-{{ $player->id }}">
                                <ion-icon name="cloud-upload"></ion-icon> Upload Parental Consent
                            </a>
                        @endif
                    </div>
                    <div class="col-span-3">
                        @php
                            $status = 'No File Attached'; // Default status

                            if ($player->birth_certificate_status == 3 || $player->parental_consent_status == 3) {
                                $status = 'Rejected';
                            } elseif ($player->birth_certificate_status == 2 && $player->parental_consent_status == 2) {
                                $status = 'Approved';
                            } elseif ($player->birth_certificate_status == 1 || $player->parental_consent_status == 1) {
                                $status = 'For Review';
                            }
                        @endphp

                        @switch($status)
                            @case('Approved')
                                <span class="text-green-500">Approved</span>
                            @break

                            @case('For Review')
                                <span class="text-yellow-500">For Review</span>
                            @break

                            @case('Rejected')
                                <span class="text-red-500">Rejected</span>
                            @break

                            @case('No File Attached')
                                <span class="text-gray-500">No File Attached</span>
                            @break
                        @endswitch
                    </div>
                </div>

               <!-- Modal for Uploading Birth Certificate -->
                <div class="modal fade" id="uploadBirthCertificateModal-{{ $player->id }}" tabindex="-1"
                    aria-labelledby="uploadBirthCertificateModalLabel-{{ $player->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <h5 class="modal-title mb-3" id="uploadBirthCertificateModalLabel-{{ $player->id }}">
                                    No Attached File
                                </h5>
                                <p>No files are currently attached. Please upload the necessary documents to proceed.</p>
                                <form action="{{ route('upload.player.birth_certificate', $player->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="flex justify-center items-center space-x-2">
                                        <input class="hidden" type="file" id="birthCertificate-{{ $player->id }}" name="birth_certificate" onchange="toggleUploadButton({{ $player->id }}, 'birthCertificate')">
                                        <button type="button" class="bg-green-600 text-white py-2 px-4 rounded-full" onclick="document.getElementById('birthCertificate-{{ $player->id }}').click();">Attach File</button>
                                        <button type="submit" id="uploadButton-birthCertificate-{{ $player->id }}" class="bg-green-600 text-white py-2 px-4 rounded-full hidden">Upload</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal for Uploading Parental Consent -->
                <div class="modal fade" id="uploadParentalConsentModal-{{ $player->id }}" tabindex="-1"
                    aria-labelledby="uploadParentalConsentModalLabel-{{ $player->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <h5 class="modal-title mb-3" id="uploadParentalConsentModalLabel-{{ $player->id }}">
                                    No Attached File
                                </h5>
                                <p>No files are currently attached. Please upload the necessary documents to proceed.</p>
                                <form action="{{ route('upload.player.parental_consent', $player->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="flex justify-center items-center space-x-2">
                                    <input class="hidden" type="file" id="parentalConsent-{{ $player->id }}" name="parental_consent" onchange="toggleUploadButton({{ $player->id }}, 'parentalConsent')">
                                        <button type="button" class="bg-green-600 text-white py-2 px-4 rounded-full" onclick="document.getElementById('parentalConsent-{{ $player->id }}').click();">Attach File</button>
                                        <button type="submit" id="uploadButton-parentalConsent-{{ $player->id }}" class="bg-green-600 text-white py-2 px-4 rounded-full hidden">Upload</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Default to View Birth Certificate Modal if uploaded -->
                @if ($player->birth_certificate_status != 0)
                    <div class="modal fade" id="viewBirthCertificateModal-{{ $player->id }}" tabindex="-1"
                        aria-labelledby="viewBirthCertificateModalLabel-{{ $player->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title" id="viewBirthCertificateModalLabel-{{ $player->id }}">
                                        View PSA Birth Certificate</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <p class="mb-1">Status:
                                        @if ($player->birth_certificate_status == '1')
                                            <span class="text-primary">Submitted</span>
                                        @elseif ($player->birth_certificate_status == '2')
                                            <span class="text-green-500">Approved</span>
                                        @elseif ($player->birth_certificate_status == '3')
                                            <span class="text-red-500">Rejected</span>
                                        @else
                                            <span class="text-muted">Not Submitted</span>
                                        @endif
                                    </p>
                                    <div class="scrollable-content mx-auto my-3">
                                        <div class="mb-4">
                                            <iframe id="iframecontent" class="w-full min-h-96"
                                                src="{{ asset('storage/' . $player->user->school_name . '/' . $player->team->sport_category . '/' . $player->team_id . '/' . $player->id . '/' . $player->birth_certificate) }}"></iframe>

                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 justify-content-center">
                                   <!--  <form action="{{ route('delete.player.birth_certificate', $player->id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger">Delete</button>
                                    </form>
                                    -->

                                    <button type="button" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out" data-bs-toggle="modal"
                                        data-bs-target="#uploadBirthCertificateModal-{{ $player->id }}">
                                        Change
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Default to View Parental Consent Modal if uploaded -->
                @if ($player->parental_consent_status != 0)
                    <div class="modal fade" id="viewParentalConsentModal-{{ $player->id }}" tabindex="-1"
                        aria-labelledby="viewParentalConsentModalLabel-{{ $player->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title" id="viewParentalConsentModalLabel-{{ $player->id }}">
                                        Parental Consent</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <p class="mb-1">Status:
                                        @if ($player->parental_consent_status == '1')
                                            <span class="text-primary">Submitted</span>
                                        @elseif ($player->parental_consent_status == '2')
                                            <span class="text-green-500">Approved</span>
                                        @elseif ($player->parental_consent_status == '3')
                                            <span class="text-red-500">Rejected</span>
                                        @else
                                            <span class="text-muted">Not Submitted</span>
                                        @endif
                                    </p>
                                    <div class="scrollable-content mx-auto my-3">
                                        <div class="mb-4">
                                            <iframe id="iframecontent" class="w-full min-h-96"
                                                src="{{ asset('storage/' . $player->user->school_name . '/' . $player->team->sport_category . '/' . $player->team_id . '/' . $player->id . '/' . $player->parental_consent) }}"></iframe>

                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 justify-content-center">
                                   <!--  <form action="{{ route('delete.player.parental_consent', $player->id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger">Delete</button>
                                    </form> -->
                                    <button type="button" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out" data-bs-toggle="modal"
                                        data-bs-target="#uploadParentalConsentModal-{{ $player->id }}">
                                        Change
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        
    </section>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS (requires Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
    function toggleUploadButton(playerId, type) {
    const fileInput = document.getElementById(`${type}-${playerId}`);
    const uploadButton = document.getElementById(`uploadButton-${type}-${playerId}`);
    if (fileInput.files.length > 0) {
        uploadButton.classList.remove('hidden');
    } else {
        uploadButton.classList.add('hidden');
    }
}
</script>

   

</x-app-layout>
