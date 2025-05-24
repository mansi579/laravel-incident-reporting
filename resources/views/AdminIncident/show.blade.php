@extends('admin.layouts.master')

@section('title', 'Incident Details')

@section('content')
    @include('admin.layouts.breadcrumb', ['module_title' => 'Incident Detail'])

    <div class="container">
        <h4>{{ $incident->title }}</h4>
        <p>{{ $incident->description }}</p>
        <p><strong>Category:</strong> {{ $incident->category->name ?? 'No category' }}</p>

        @php
            $priorities = [0 => 'High', 1 => 'Medium', 2 => 'Low'];
            $priorityLabel = $priorities[$incident->priority] ?? 'Unknown';
        @endphp
        <p><strong>Priority:</strong> {{ $priorityLabel }}</p>

        <form method="POST" action="{{ route('adminIncidents.update', ['incident' => $incident->id]) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="0" {{ $incident->status == 0 ? 'selected' : '' }}>Open</option>
                    <option value="1" {{ $incident->status == 1 ? 'selected' : '' }}>In Progress</option>
                    <option value="2" {{ $incident->status == 2 ? 'selected' : '' }}>Resolved</option>
                </select>
            </div>

                        <button type="submit" class="btn btn-primary mt-2">Save</button>
        </form>


    </div>
@endsection
