@extends('admin.layouts.master')

@section('title', 'Create Incident Request')

@section('content')
    @include('admin.layouts.breadcrumb', ['module_title' => 'Create Incident Request'])

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <form id="incident-form" method="POST" action="{{ route('incidents.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        <div class="form-group">
                            <label for="title" class="col-form-label">Title</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="Enter incident title" value="{{ old('title') }}">
                            @error('title')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description" class="col-form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Describe the incident">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category_id" class="col-form-label">Category</label>
                            <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="priority" class="col-form-label">Priority</label>
                            <select name="priority" class="form-control @error('priority') is-invalid @enderror">
                                <option value="">Select Priority</option>
                                 <option value="0" {{ old('priority') == '0' ? 'selected' : '' }}>High</option>
                                    <option value="1" {{ old('priority') == '1' ? 'selected' : '' }}>Medium</option>
                                    <option value="2" {{ old('priority') == '2' ? 'selected' : '' }}>Low</option>
                            </select>
                            @error('priority')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="date" class="col-form-label">Date</label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}">
                            @error('date')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="file" class="col-form-label">Evidence Upload</label>
                            <input type="file" name="file" class="form-control-file @error('file') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            <small class="form-text text-muted">Accepted formats: jpg, png, pdf, doc. Max: 2MB</small>
                            @error('file')
                                <span class="error invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                        <a href="{{ route('incidents.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script>
        $('#incident-form').validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 255
                },
                description: {
                    required: true
                },
                category_id: {
                    required: true
                },
                priority: {
                    required: true
                },
                date: {
                    required: true,
                    date: true
                },
                file: {
                    required: true,
                    extension: "jpg|jpeg|png|pdf|doc|docx",
                    filesize: 2048000 // 2MB in bytes
                }
            },
            messages: {
                file: {
                    extension: "Only JPG, PNG, PDF, DOC files are allowed",
                    filesize: "File must be less than 2MB"
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
                if (element.attr("type") == "file") {
                    error.insertAfter(element.next('small'));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        // Custom validator for file size
        $.validator.addMethod('filesize', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param);
        }, 'File size must be less than {0}');
    </script>
@endsection
