@extends('admin.layouts.master')

@section('title', 'User')

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link href="{{ asset('css/jquery-impromptu.css') }}" rel="stylesheet">

@endsection

@section('content')
    @include('admin.layouts.breadcrumb', ['module_title' => 'Users'])

    <section class="content">
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <a href="{{ route('user.create') }}"class="btn btn-primary">Add User</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="users-table" class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
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
       const dataTable = $('#users-table').DataTable({
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
            ajax: "{{ route('user.ajax') }}",
            columns: [
                { data: 'name' },
                { data: 'email' },
                { data: 'roles' },
                { data: 'action', className: 'all', searchable: false, orderable: false }
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

