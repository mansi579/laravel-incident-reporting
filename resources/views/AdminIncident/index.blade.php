@extends('admin.layouts.master')

@section('title', 'Incident Dashboard')

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
    @include('admin.layouts.breadcrumb', ['module_title' => 'Incident Dashboard'])

    <section class="content">
        <div class="container-fluid">

            <!-- Analytics Widgets -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalIncidents }}</h3>
                            <p>Total Incidents</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <canvas id="statusChart"></canvas>
                </div>

                <div class="col-md-3">
                    <canvas id="categoryChart"></canvas>
                </div>

                <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $averageFormatted ?? 'N/A' }}</h3>
                        <p>Avg. Resolution Time</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            </div>

            <!-- Filters -->
            <form method="GET" class="row mb-3">
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">Filter by Status</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Open</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>In Progress</option>
                        <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="category_id" class="form-control">
                        <option value="">Filter by Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary">Apply</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('adminIncidents.export') }}" class="btn btn-primary mb-3">Export to CSV</a>   
                </div>

            </form>

            <form method="POST" action="{{ route('admin.incidents.bulk-resolve') }}">
                    @csrf

                    <div class="mb-2">
                        <button class="btn btn-success btn-sm" type="submit">Mark Selected as Resolved</button>
                    </div>

                    <table class="table table-bordered" id="incident-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all-checkbox"> Bulk Resolve</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($incidents as $incident)
                                <tr>
                                    <td><input type="checkbox" name="incident_ids[]" value="{{ $incident->id }}"></td>
                                    <td>{{ $incident->title }}</td>
                                    <td>{{ $incident->description }}</td>
                                    <td>{{ $incident->category->name }}</td>
                                    <td>{{ $incident->priority === 0 ? 'High' : ($incident->priority === 1 ? 'Medium' : 'Low') }}</td>
                                    <td>{{ $incident->status === 0 ? 'Open' : ($incident->status === 1 ? 'In Progress' : 'Resolved') }}</td>
                                    <td>{{ $incident->date }}</td>
                                    <td>
                                        <a href="{{ route('adminIncidents.show', $incident->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>


            <!-- Bulk Actions -->
            <!-- <form method="POST" action="{{ route('admin.incidents.bulk-resolve') }}"> -->
                <!-- @csrf -->
                
                <!-- <div class="mb-2">
                    <button class="btn btn-success btn-sm" type="submit">Mark Selected as Resolved</button>
                </div> -->

                <!-- <table class="table table-bordered" id="incident-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>View</th>
                            <th><input type="checkbox" id="select-all-checkbox"> Bulk Resolve</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incidents as $incident)
                            <tr>
                                <td>{{ $incident->title }}</td>
                                <td>{{ $incident->description }}</td>
                                <td>{{ $incident->category->name }}</td>
                                <td>
                                    {{ $incident->priority === 0 ? 'High' : ($incident->priority === 1 ? 'Medium' : 'Low') }}
                                </td>
                                <td>
                                    {{ $incident->status === 0 ? 'Open' : ($incident->status === 1 ? 'In Progress' : 'Resolved') }}
                                </td>
                                <td>{{ $incident->date }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info mt-1" type="button"
                                        onclick="document.getElementById('status-form-{{ $incident->id }}').classList.toggle('d-none')">
                                        View
                                    </button>

                                    

                                </td>
                                                <td><input type="checkbox" name="incident_ids[]" value="{{ $incident->id }}"></td>


                            </tr>
                        @endforeach
                    </tbody>
                </table> -->
            <!-- </form> -->
        </div>
    </section>
@endsection

@section('js')
    <script>
        document.getElementById('select-all-checkbox').addEventListener('change', function () {
            let checked = this.checked;
            document.querySelectorAll('input[name="incident_ids[]"]').forEach(cb => cb.checked = checked);
        });

        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'pie',
            data: {
                labels: ['Open', 'In Progress', 'Resolved'],
                datasets: [{
                    data: [{{ $statusCounts[0] ?? 0 }}, {{ $statusCounts[1] ?? 0 }}, {{ $statusCounts[2] ?? 0 }}],
                    backgroundColor: ['#dc3545', '#ffc107', '#28a745']
                }]
            }
        });

        const ctx = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Incidents by Category',
                    data: {!! json_encode($data) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Most Common Incident Categories' }
                }
            }
        });

    </script>
@endsection
