@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section(section: 'css')
@endsection

@section('content')
    @include('admin.layouts.breadcrumb', ['module_title' => 'Dashboard'])
@endsection

@section('js')
@endsection
