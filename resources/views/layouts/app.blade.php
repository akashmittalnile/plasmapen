<!DOCTYPE html>

<html>



<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/x-icon" href="{{ assets('assets/images/logo.svg') }}">

    <title>@yield('title',config('constant.siteTitle'))</title>



    <!-- css -->

    <link rel="stylesheet" type="text/css" href="{{ assets('assets/css/header-footer.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ assets('assets/plugins/apexcharts/apexcharts.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ assets('assets/css/notify.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/plugins/OwlCarousel/assets/owl.carousel.min.css') }}">

    @stack('css')

    <!-- end css -->



    <!-- script js -->

    <script src="{{ assets('assets/js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>

    <script src="{{ assets('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>

    <script src="{{ assets('assets/plugins/apexcharts/apexcharts.min.js') }}" type="text/javascript"></script>

    <script src="{{ assets('assets/js/function.js') }}" type="text/javascript"></script>

    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.12.0/moment.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5.9.2/dist/min/dropzone.min.css">
    <script src="{{ assets('assets/plugins/OwlCarousel/owl.carousel.min.js') }}" type="text/javascript"></script>
    <!-- end script js -->

</head>



<body class="main-site">



    <!-- loader -->

    <div class="loader" id="loader-4">

        <div class="text">

            Please Wait

        </div>

        <div>

            <span></span>

            <span></span>

            <span></span>

        </div>

    </div>

    <!-- end loader -->



    <div class="page-body-wrapper">





        <!-- sidebar -->

        @include('layouts.sidebar')

        <!-- end sidebar -->



        <div class="body-wrapper">



            <!-- header -->

            @include('layouts.header')

            <!-- end header -->



            <!-- content -->

            @yield('content')

            <!-- end content -->



        </div>



    </div>



    <!-- toast -->

    <div id="toast">

        <div id="desc" class="toastdesc">A notification message..</div>

    </div>

    @if(Session::has('success'))

    <script>

        $(".toastdesc").text("{{ Session::get('success') }}").addClass('text-success');

        launch_toast();

    </script>

    @elseif(Session::has('error'))

    <script>

        $(".toastdesc").text("{{ Session::get('error') }}").addClass('text-danger');

        launch_toast();

    </script>

    @endif

    <!-- end toast -->



    <!-- script js -->

    <script src="{{ assets('assets/plugins/jquery-validation/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.0/dropzone.js"></script>
    <script type="text/javascript">

        window.addEventListener('load', function() {

            $("#loader-4").delay(500).fadeOut("slow");

        });

        let baseUrl = "{{ url('/') }}";

    </script>

    @stack('js')

    <!-- end script js -->



</body>



</html>