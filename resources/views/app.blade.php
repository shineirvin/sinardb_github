<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <title> {!! strtoupper(explode('/',trim(Route::current()->uri))[0]) . ' |' !!} Sinar Printing</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- <link rel="icon" href="{!! url('images/favicon.jpg') !!}" type="image/jpg" sizes="16x16"> --}}

        <link rel="stylesheet" href="{!! url('css/app.css') !!}">
        <link rel="stylesheet" href="{!! url('css/all.css') !!}">
        
        <script src="{!! url('js/app.js') !!}"></script>
        <script src="{!! url('vendors/moment/min/moment.min.js') !!}"></script>
        <script src="{!! url('vendors/moment/locale/id.js') !!}"></script>
        <script src="{!! url('vendors/googlechart/loader.js') !!}"></script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    </head>

    <body class="nav-md">
        <div class="container body" id="app">
            <div class="main_container">

                @include('layouts/sidebar')

                @include('layouts/topnavbar')
        
                <div class="right_col" role="main">
                    @yield('content')
                </div>
                
                @include('layouts/footer')

            </div>
        </div>
    </body>
    @include('partials/_flash')
    @push('combinedScript')
        <script src="{!! url('js/all.js') !!}"></script>
    @endpush
    @stack('combinedScript')
    @stack('scripts')
    
</html>
