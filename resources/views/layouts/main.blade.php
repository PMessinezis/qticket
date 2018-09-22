<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="Refresh" content="42222">
    <meta name="Pragma" content="no-cache;">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ title_case(config('app.name', 'qticket')) }}</title>

    <!-- Styles -->
    <link href={{ myURL("/css/app.css") }}  rel="stylesheet">
    <link href={{ myURL("/css/select2.css")  }} rel="stylesheet" />
    <link href={{ myURL("/css/fontawesome-all.min.css" ) }} rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token()]); ?>
    </script>
</head>



<body>
    <div id="app">

@if( Auth::User()->isAdmin() )
        <div id=cssMode >
            <h1 class="visible-xxs">Extra-Extra SMALL screen -xxs-</h1>
            <h1 class="visible-xs">EXTRA SMALL screen -xs-</h1>
            <h1 class="visible-sm">SMALL screen -sm- </h1>
            <h1 class="visible-md">MEDIUM screen -md-</h1>
            <h1 class="visible-lg">LARGE screen -lg- </h1>
        </div>
@endif

        @include('partials.topPart')
        {{-- <div id='homepageTab'> --}}
            @include('partials.home')
        {{-- </div> --}}
        @include('partials.bottomPart')

    </div> <!-- app -->

    <!-- Scripts -->
    <script src={{ myURL("/js/manifest.js") }} ></script>
    <script src={{ myURL("/js/app.js") }} ></script>
    <script type="text/javascript">
        jQuery=window.JQuery
    </script>
    <script src={{ myURL("/js/jsutils.js") }} ></script>
    <!-- <script src={{ myURL("/js/select2.js") }} ></script> -->
    <script type="text/javascript">
    @if(session('preload_ticket_id'))

    App.preload_ticket_id= {{ session('preload_ticket_id') }} ;

    @endif

    </script>

    @stack('scripts')
</body>
</html>