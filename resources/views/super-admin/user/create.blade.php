@extends('admin.layouts.master')
@section('title', 'users')

@section('content')
    @php
        $title = isset($data) ? 'Edit User' : 'Add New User';
    @endphp

    @include('admin.layouts.breadcrumb', ['module_title' => Str::singular($title)])

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <form id="category-form" method="post"
                    action="{{ isset($data) ? route('user.update', $data->id) : route('user.store') }}">
                    @csrf
                    @if (isset($data))
                        @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" placeholder="Enter category name"
                                value="{{ isset($data) ? $data->name : old('name') }}">
                            @error('name')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-form-label">Email</label>
                            <input type="text" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" placeholder="Enter Email"
                                value="{{ isset($data) ? $data->name : old('email') }}">
                            @error('email')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-form-label">password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Enter Password"
                                value="{{ isset($data) ? $data->name : old('password') }}">
                            @error('password')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        @php
                            $roles = Config::get('constants.roles');
                        @endphp
    
                        <div class="form-group">
                            <label for="role" class="col-form-label">Role</label>
                                <select name="role" class="form-control @error('role') is-invalid @enderror">
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $key => $label)
                                        <option value="{{ $label }}" {{ (isset($data) && $data->hasRole($label)) ? 'selected' : '' }}>
                                            {{ ucfirst($label) }}
                                        </option>
                                    @endforeach
                                </select>
                            @error('role')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        @if(isset($data))
                            @if(auth()->user()->hasRole('super-admin'))
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="is_block" name="is_block" {{ isset($data) && $data->is_block ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_block">Block this user</label>
                                </div>
                            @endif
                        @endif

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($data) ? 'Update' : 'Submit' }}
                        </button>
                        <a href="{{ route('user.index') }}" class="btn btn-secondary">Cancel</a>
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
                },
                email: {
                    required: true,
                },
                password: {
                    required: true,
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
