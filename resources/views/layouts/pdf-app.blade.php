<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('template_title')@yield('template_title')
        | @endif {{ config('app.name', Lang::get('titles.app')) }}</title>
    <meta name="description" content="">
    <meta name="author" content="Jeremy Kenedy">
    <link rel="icon" href="{{ URL::asset('/images/logo.png') }}" type="image/x-icon"/>
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.jqueryui.min.js"></script>
    <script src="https://cdn.datatables.net/scroller/2.0.2/js/dataTables.scroller.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.dataTables.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    {{-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --}}
        <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    {{-- Fonts --}}
    @yield('template_linked_fonts')

    {{-- Styles --}}
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

    @yield('template_linked_css')

    <style type="text/css">
        @yield('template_fastload_css')

            @if (Auth::User() && (Auth::User()->profile) && (Auth::User()->profile->avatar_status == 0))
                .user-avatar-nav {
            background: url({{ Gravatar::get(Auth::user()->email) }}) 50% 50% no-repeat;
            background-size: auto 100%;
        }

        @endif
        .uploader {
            display: block;
            clear: both;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
        }

        .uploader label {
            float: left;
            clear: both;
            width: 100%;
            padding: 2rem 1.5rem;
            text-align: center;
            background: #fff;
            border-radius: 7px;
            border: 3px solid #eee;
            transition: all 0.2s ease;
            user-select: none;
        }

        .uploader label:hover {
            border-color: #454cad;
        }

        .uploader label.hover {
            border: 3px solid #454cad;
            box-shadow: inset 0 0 0 6px #eee;
        }

        .uploader label.hover #start i.fa {
            transform: scale(0.8);
            opacity: 0.3;
        }

        .uploader #start {
            float: left;
            clear: both;
            width: 100%;
        }

        .uploader #start.hidden {
            display: none;
        }

        .uploader #start i.fa {
            font-size: 50px;
            margin-bottom: 1rem;
            transition: all 0.2s ease-in-out;
        }

        .uploader #response {
            float: left;
            clear: both;
            width: 100%;
        }

        .uploader #response.hidden {
            display: none;
        }

        .uploader #response #messages {
            margin-bottom: 0.5rem;
        }

        .uploader #file-image {
            display: inline;
            margin: 0 auto 0.5rem auto;
            width: auto;
            height: auto;
            max-width: 180px;
        }

        .uploader #file-image.hidden {
            display: none;
        }

        .uploader #notimage {
            display: block;
            float: left;
            clear: both;
            width: 100%;
        }

        .uploader #notimage.hidden {
            display: none;
        }

        .uploader progress, .uploader .progress {
            display: inline;
            clear: both;
            margin: 0 auto;
            width: 100%;
            max-width: 180px;
            height: 8px;
            border: 0;
            border-radius: 4px;
            background-color: #eee;
            overflow: hidden;
        }

        .uploader .progress[value]::-webkit-progress-bar {
            border-radius: 4px;
            background-color: #eee;
        }

        .uploader .progress[value]::-webkit-progress-value {
            background: linear-gradient(to right, #393f90 0%, #454cad 50%);
            border-radius: 4px;
        }

        .uploader .progress[value]::-moz-progress-bar {
            background: linear-gradient(to right, #393f90 0%, #454cad 50%);
            border-radius: 4px;
        }

        .uploader input[type="file"] {
            display: none;
        }

        .uploader div {
            margin: 0 0 0.5rem 0;
            color: #5f6982;
        }

        .uploader .btn {
            display: inline-block;
            margin: 0.5rem 0.5rem 1rem 0.5rem;
            clear: both;
            font-family: inherit;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            text-transform: initial;
            border: none;
            border-radius: 0.2rem;
            outline: none;
            padding: 0 1rem;
            height: 36px;
            line-height: 36px;
            color: #fff;
            transition: all 0.2s ease-in-out;
            box-sizing: border-box;
            background: #454cad;
            border-color: #454cad;
            cursor: pointer;
        }

        #email_input {
            width: 100%;
            padding: 5px 10px;
        }

    </style>

    {{-- Scripts --}}
    <script>
        window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
    </script>
    {{--
        @if (Auth::User() && (Auth::User()->profile) && $theme->link != null && $theme->link != 'null')
            <link rel="stylesheet" type="text/css" href="{{ $theme->link }}">
        @endif--}}

    @yield('head')
    @include('scripts.ga-analytics')
</head>
<body>
<div id="app">

    @include('partials.nav')

    <main class="py-4">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    @include('partials.form-status')
                </div>
            </div>
        </div>

        @yield('content')

    </main>

</div>

{{-- Scripts --}}
{{--<script src="{{ mix('/js/app.js') }}"></script>--}}

@if(config('settings.googleMapsAPIStatus'))
    {!! HTML::script('//maps.googleapis.com/maps/api/js?key='.config("settings.googleMapsAPIKey").'&libraries=places&dummy=.js', array('type' => 'text/javascript')) !!}
@endif

@yield('footer_scripts')

</body>
</html>
