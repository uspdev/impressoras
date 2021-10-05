@extends('laravel-usp-theme::master')

@section('styles')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
    <link rel="stylesheet" href="/css/sites.css">
@endsection

@section('javascripts_head')
    @parent
    <script src="/js/sites.js"></script>
    <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
@endsection

@section('content')
    @include('messages.flash')
    @include('messages.errors')
@endsection

@section('flash')
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{!! $error !!}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
        <p class="alert alert-{{ $msg }}">{!! Session::get('alert-' . $msg) !!}
            <a href="#" class="close" data-dismiss="alert" aria-label="fechar">&times;</a>
        </p>
        @endif
    @endforeach
    </div>
@endsection

