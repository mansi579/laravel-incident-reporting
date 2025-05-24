@extends('admin.layouts.master')

@section('title', 'Category')

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link href="{{ asset('css/jquery-impromptu.css') }}" rel="stylesheet">

@endsection

@section('content')
    @include('admin.layouts.breadcrumb', ['module_title' => 'Incidents'])

    <section class="content">
        <div class="container-fluid">

        <div class="row mb-3">
            <div class="col-md-3">
                <select id="filter-category" class="form-control">
                    <option value="">All Categories</option>
                         @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select id="filter-status" class="form-control">
                    <option value="">All Priorities</option>
                    <option value="0">High</option>
                    <option value="1">Medium</option>
                    <option value="2">Low</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="date" id="filter-date" class="form-control" placeholder="Filter by Date">
            </div>

            <div class="col-md-3">
                <button class="btn btn-secondary" id="reset-filters">Reset Filters</button>
            </div>
        </div>

            <div class="row mt-3">
                <div class="col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            @role(Config::get('constants.roles.user'))
                                <div class="card-tools">
                                    <a href="{{ route('incidents.create') }}"class="btn btn-primary">create Request </a>
                                </div>
                            @endrole
                        </div>
                        <div class="card-body">
                                <table class="table table-bordered" id="incident-table">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Category</th>
                                            <th>Priority</th>
                                            <th>Date</th>
                                            <!-- <th>File</th> -->
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
             ajax: {
                url: "{{ route('incident.ajax') }}",
                data: function (d) {
                    d.category_id = $('#filter-category').val();
                    d.priority = $('#filter-status').val();
                    d.date = $('#filter-date').val();
                }
            },
             columns: [
                { data: 'title', name: 'title' },
                { data: 'description', name: 'description' },
                { data: 'category', name: 'category.name' },
                { data: 'priority', name: 'priority' },
                { data: 'date', name: 'date' },
                    // { data: 'file', name: 'file', orderable: false, searchable: false }, // Add this

                // { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });


        $('#filter-category, #filter-status, #filter-date').on('change', function () {
            dataTable.ajax.reload();
        });

        // Reset filters
        $('#reset-filters').on('click', function () {
            $('#filter-category').val('');
            $('#filter-status').val('');
            $('#filter-date').val('');
            dataTable.ajax.reload();
        });
    </script>
@endsection

