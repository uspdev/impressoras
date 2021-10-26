@extends('laravel-usp-theme::master')

@section('styles')
    @parent
    <link rel="stylesheet" href="/css/sites.css">
@endsection

@section('flash')
    @include('messages.flash')
    @include('messages.errors')
@endsection

