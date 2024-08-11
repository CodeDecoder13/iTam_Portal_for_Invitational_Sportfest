<x-app-layout>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <section class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"></section>

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
                    ['Gallery of Players', 'May 15, 2024', 0],
                    ['Gallery of Coaches', 'July 10, 2024', 0],
                    ['Parental Consent', 'August 1, 2024', 0],
                    ['Photocopy of School ID', 'August 1, 2024', 0],
                    ['Certificate of Registration', 'June 30, 2024', 0],
                    ['Photocopy of Vaccine Card', 'July 20, 2024', 0],
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
                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#documentModal" data-doc="{{ $a[0] }}">
                            <ion-icon name="document"></ion-icon>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="documentModal" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentModalLabel">Parental Consent</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="embed-responsive embed-responsive-1by1 bg-light">
                                    <iframe class="embed-responsive-item" src=""></iframe>
                                </div>
                                <div class="mt-3 d-flex justify-content-between">
                                    <button class="btn btn-danger">Delete</button>
                                    <button class="btn btn-success">Download PDF</button>
                                    <button class="btn btn-secondary">Change</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5>Comments</h5>
                                <div class="mb-2">
                                    <strong>Denielle Karim</strong>
                                    <p>COR is not clear, please scan it again!</p>
                                </div>
                                <form>
                                    <div class="form-group">
                                        <textarea class="form-control" placeholder="Post additional message to the thread"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success">Add Comment</button>
                                </form>
                            </div>
                        </div>
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
        $('#documentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var docTitle = button.data('doc');
            var modal = $(this);
            modal.find('.modal-title').text(docTitle);
            // Additional logic to dynamically load the document content can be added here
        });
    </script>
</x-app-layout>
