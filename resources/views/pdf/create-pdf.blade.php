@extends('layouts.pdf-app')

@section('template_title')
    Welcome {{ Auth::user()->name }}
@endsection

@section('head')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                @include('panels.pdf-create-panel')
            </div>
        </div>
    </div>

@endsection
