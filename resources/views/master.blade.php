@extends('laravel-usp-theme::master')

@section('styles')
    @parent
    <!--<link rel="stylesheet" href="css/sites.css">-->
@endsection

@section('flash')
    @include('messages.flash')
    @include('messages.errors')
@endsection

@section('javascripts_bottom')
@parent
<script>
$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>
@endsection
