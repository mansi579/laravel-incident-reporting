@extends('admin.layouts.master')
@section('title', 'Categories')

@section('content')
    @php
        $title = isset($data) ? 'Edit Category' : 'Add New Category';
    @endphp

    @include('admin.layouts.breadcrumb', ['module_title' => Str::singular($title)])

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <form id="category-form" method="post"
                    action="{{ isset($data) ? route('categories.update', $data->id) : route('categories.store') }}">
                    @csrf
                    @if (isset($data))
                        @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Category Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" placeholder="Enter category name"
                                value="{{ isset($data) ? $data->name : old('name') }}">
                            @error('name')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($data) ? 'Update' : 'Submit' }}
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script>
        $('#category-form').validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 255
                }
            },
            errorElement: 'span',
            errorClass: 'invalid-feedback',
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
            errorPlacement: function(error, element) {
                $(element).closest('.form-group').append(error);
            }
        });
    </script>
@endsection
