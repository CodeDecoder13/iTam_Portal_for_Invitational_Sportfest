<x-app-layout>
    <section class="grid grid-cols-1">
        <h1 class="font-bold mb-2 text-3xl">Summary Of Players</h1>
        <h3>Fill in player's summary to complete your requirements.</h3>
        <div class="grid grid-cols-1 mt-5">
            <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                <div class="font-bold col-span-2">Jersey No.</div>
                <div class="font-bold col-span-2">Given Name</div>
                <div class="font-bold col-span-2">Sport Category</div>
                <div class="font-bold col-span-2">PSA Birth Certificate</div>
                <div class="font-bold col-span-2">Parental Consent</div>
                <div class="font-bold col-span-2">Status</div>
            </div>
            @foreach ($players as $player)
                <div class="grid grid-cols-12 px-4 py-3 bg-white rounded-lg border mt-3">
                    <div class="col-span-2">{{ $player->jersey_no }}</div>
                    <div class="col-span-2">{{ $player->first_name }} {{ $player->last_name }}</div>
                    <div class="col-span-2">{{ $player->sport_category }}</div>
                    <div class="col-span-2 text-green-700">
                        @if($player->has_birth_certificate)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#viewDocumentModal-{{ $player->id }}"> 
                                <ion-icon name="eye"></ion-icon> View Birth Certificate
                            </a>
                        @else
                            <a href="#" data-bs-toggle="modal" data-bs-target="#uploadBirthCertificateModal-{{ $player->id }}"> 
                                <ion-icon name="cloud-upload"></ion-icon> Upload Birth Certificate
                            </a>
                        @endif
                    </div>
                    <div class="col-span-2 text-green-700">
                        @if($player->has_parental_consent)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#viewDocumentModal-{{ $player->id }}"> 
                                <ion-icon name="eye"></ion-icon> View Parental Consent
                            </a>
                        @else
                            <a href="#" data-bs-toggle="modal" data-bs-target="#uploadParentalConsentModal-{{ $player->id }}"> 
                                <ion-icon name="cloud-upload"></ion-icon> Upload Parental Consent
                            </a>
                        @endif
                    </div>  
                    <div class="col-span-2">
                        @switch($player->status)
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
                <div class="modal fade" id="uploadBirthCertificateModal-{{ $player->id }}" tabindex="-1" aria-labelledby="uploadBirthCertificateModalLabel-{{ $player->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="uploadBirthCertificateModalLabel-{{ $player->id }}">Upload PSA Birth Certificate</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <p>No PSA Birth Certificate is currently attached. Please upload the necessary document to proceed.</p>
                                <form action="{{ route('upload.player.birth_certificate', $player->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="birthCertificate-{{ $player->id }}" class="form-label">PSA Birth Certificate</label>
                                        <input class="form-control" type="file" id="birthCertificate-{{ $player->id }}" name="birth_certificate">
                                    </div>
                                    <button type="submit" class="btn btn-green">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal for Uploading Parental Consent -->
                <div class="modal fade" id="uploadParentalConsentModal-{{ $player->id }}" tabindex="-1" aria-labelledby="uploadParentalConsentModalLabel-{{ $player->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="uploadParentalConsentModalLabel-{{ $player->id }}">Upload Parental Consent</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <p>No Parental Consent is currently attached. Please upload the necessary document to proceed.</p>
                                <form action="{{ route('upload.player.parental_consent', $player->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="parentalConsent-{{ $player->id }}" class="form-label">Parental Consent</label>
                                        <input class="form-control" type="file" id="parentalConsent-{{ $player->id }}" name="parental_consent">
                                    </div>
                                    <button type="submit" class="btn btn-green">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- View Document Modal -->
                <div class="modal fade" id="viewDocumentModal-{{ $player->id }}" tabindex="-1" aria-labelledby="viewDocumentModalLabel-{{ $player->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewDocumentModalLabel-{{ $player->id }}">View Documents</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <div class="scrollable-content mx-auto my-3">
                                    @if($player->has_birth_certificate)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/birth_certificates/' . $player->birth_certificate) }}" alt="Birth Certificate">
                                        </div>
                                    @endif
                                    @if($player->has_parental_consent)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/parental_consents/' . $player->parental_consent) }}" alt="Parental Consent">
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-around mt-3">
                                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Delete</button>
                                    <button type="button" class="btn btn-green">Download PDF</button>
                                    <button type="button" class="btn btn-link">Change</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-app-layout>
