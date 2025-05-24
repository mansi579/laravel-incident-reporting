@extends('admin.layouts.master')

@section('title', 'Incidents')

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link href="{{ asset('css/jquery-impromptu.css') }}" rel="stylesheet">

@endsection

@section('content')
    @include('admin.layouts.breadcrumb', ['module_title' => 'Incidents'])

    <section class="content">
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                                <table class="table table-bordered" id="incident-table">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Category</th>
                                            <th>Priority</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script>
        const dataTable = $('#incident-table').DataTable({
            lengthChange: false,
            language: {
                search: '',
                searchPlaceholder: "Search..."
            },
            responsive: window.screen.width < 1024,
            aaSorting: [],
            aoColumnDefs: [{
                bSortable: false,
                aTargets: [-1]
            }],
            serverSide: true,
            ajax: "{{ route('request.ajax') }}",
             columns: [
                { data: 'title', name: 'title' },
                { data: 'description', name: 'description' },
                { data: 'category', name: 'category.name' },
                { data: 'priority', name: 'priority' },
                { data: 'date', name: 'date' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });

         $('body').on('click', '.delete', function() {
            var id = $(this).data("id");
            var token = $('input[name="_token"]').val();
            let url = $(this).attr('target-url');

            swal({
                title: "Are you sure you want to delete this record?",
                text: "Are you sure?",
                icon: "warning",
                type: "warning",
                buttons: ["Cancel", "Yes!"],
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',

            }).then((result) => {
                if (result) {
                    $.ajax({
                        headers: {
                            'X-CSRF-Token': token
                        },
                        type: "DELETE",
                        url: url,
                        success: function(data) {
                            if (data.status) {
                                location.reload();
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection

