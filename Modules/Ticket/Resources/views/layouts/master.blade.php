<head>
    <base href="{{url('/')}}" target="_top">
    <link rel="stylesheet" href="{{{ asset('assets/css/bootstrap.min.css')}}}" />
    </head>
    <body class="no-skin">
        @yield('content')

        {{-- Laravel Mix - JS File --}}
        {{-- <script src="{{ mix('js/address.js') }}"></script> --}}
    </body>
</html>
