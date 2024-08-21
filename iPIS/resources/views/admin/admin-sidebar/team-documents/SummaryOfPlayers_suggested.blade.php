    <x-app-layout>
        <section class="grid grid-cols-1">
            <h1 class="font-bold mb-2 text-3xl">Summary Of Players</h1>
            <h3>Fill in player's summary to complete your requirements.</h3>

            <form method="GET" action="{{ route('admin.SummaryOfPlayers') }}" class="grid grid-cols-1 mt-5">
                <div class="grid grid-cols-12 gap-4 px-4 py-3 rounded-lg bg-gray-100">
                    <div class="col-span-5">
                        <input type="text" name="search" placeholder="Search" value="{{ request('search') }}"
                            class="w-8/12 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="col-span-1 flex items-center">Filtered By:</div>
                    <div class="col-span-2">
                        <select name="sport"
                            class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Sports Category</option>
                            <!-- Add options dynamically or statically here -->
                        </select>
                    </div>
                    <div class="col-span-2">
                        <select name="team"
                            class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Team Name</option>
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
            <div class="grid grid-cols-1 mt-5">
                <div class="grid grid-cols-12 px-4 py-3 bg-green-700 text-white rounded-lg border">
                    <div class="font-bold col-span-2">Given Name</div>
                    <div class="font-bold col-span-2">School name</div>
                    <div class="font-bold col-span-2">Sport Category</div>
                    <div class="font-bold col-span-2">Team Name</div>
                    <div class="font-bold col-span-2">Status</div>
                    <div class="font-bold col-span-2">Action</div>
                </div>
                @foreach ($players as $player)
                    <div class="grid grid-cols-12 px-4 py-3 bg-white rounded-lg border mt-3">
                        <div class="col-span-2">{{ $player->first_name }} {{ $player->last_name }}</div>
                        <div class="col-span-2">
                            @if ($player->user)
                                {{ $player->user->school_name }}
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="col-span-2">{{ $player->team->sport_category }}</div>
                        <div class="col-span-2">{{ $player->team->name }}</div>
                        <div class="col-span-2">{{ $player->status }}</div>
                        <div class="col-span-2 text-white-700">
                            <button
                                class="bg-green-500 hover:bg-green-400 mb-2 w-full text-white font-bold py-2 px-4 rounded"
                                data-toggle="modal" data-target="#documentModal" data-doc="Parental Consent"
                                data-team_id="{{ $player->team_id }}" data-player_id="{{ $player->id }}">
                                View Parental Consent
                            </button>
                            <button class="bg-blue-500 hover:bg-blue-400 w-full text-white font-bold py-2 px-4 rounded"
                                data-toggle="modal" data-target="#documentModal" data-doc="Birth Certificate"
                                data-team_id="{{ $player->team_id }}" data-player_id="{{ $player->id }}">
                                View Birth Certificate
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>
        </section>



        <!-- Bootstrap Modal -->
        <div class="modal fade" id="documentModal" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="documentModalLabel">Document Approval</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body min-h-96">
                        <div class="container min-h-fit">
                            <!-- Document 1 Section -->
                            <div id="documentContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Include Bootstrap JS and jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <script>
            function checkUrl(url, callback) {
                var xhr = new XMLHttpRequest();
                xhr.open('HEAD', url, true);
                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 400) {
                        callback(true);
                    } else {
                        callback(false);
                    }
                };
                xhr.onerror = function() {
                    callback(false);
                };
                xhr.send();
            }


            $('#documentModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var docType = button.data('doc');
                var playerId = button.data('player_id');
                var teamId = button.data('team_id');
                var modal = $(this);
                var location = `/storage/${teamId}/${playerId}/`;
                var fileName;
                var iframeSrc;

                modal.find('.modal-title').text(docType);
                var content = '';

                if (docType === 'Parental Consent') {
                    fileName = 'parental_consent';
                } else if (docType === 'Birth Certificate') {
                    fileName = 'birth_certificate';
                }

                iframeSrc = location + fileName+'.png';

                checkUrl(iframeSrc, function(exists) {
                    if (exists) {
                        content = `
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h5>Document: ${docType}</h5>
                        <iframe id="iframecontent" class="w-full min-h-96" src="${iframeSrc}"></iframe>
                    </div>
                    <div class="col-md-4 d-flex flex-column justify-content-center">
                        <form method="POST" action="/document/update/${playerId}/${fileName}/2">
                            @csrf
                            <button class="btn btn-success mb-2 w-full">Approve</button>
                        </form>
                        <form method="POST" action="/document/update/${playerId}/${fileName}/3">
                            @csrf
                            <button class="btn btn-danger mb-2 w-full">Reject</button>
                        </form>
                        <a href="/document/update/${playerId}/${fileName}/4" class="btn btn-secondary mb-2">Download PDF</a>
                        <form method="POST" action="/document/update/${playerId}/${fileName}/0">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-warning w-full">Delete</button>
                        </form>
                    </div>
                </div>`;
                    } else {
                        content = `
                <div class="row mb-4">
                    <div class="col-md-12">
                        <p>There is a problem with the file or the file does not exist.</p>
                    </div>
                </div>`;
                    }
                    modal.find('#documentContent').html(content);
                });
            });
        </script>


    </x-app-layout>
