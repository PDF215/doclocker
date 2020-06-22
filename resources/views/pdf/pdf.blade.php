@extends('layouts.pdf-app')

@section('template_title')
    Welcome {{ Auth::user()->name }}
@endsection

@section('head')
@endsection

@section('content')
    <div class="col-12">
        @include('panels.pdf-panel')
    </div>
@endsection
