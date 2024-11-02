@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
@yield('content-header')
@stop

@section('content')
@yield('content')
@stop

@section('css')
<link rel="stylesheet" href="{{ URL::asset('css/admin_custom.css') }}">
@yield('css')
@stop
