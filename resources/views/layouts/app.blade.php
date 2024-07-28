<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('APP_NAME', 'PT. Wira Griya') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel= "stylesheet"
        href= "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <script src="https://kit.fontawesome.com/ccf5b15bef.js" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.1.0/datatables.min.css" rel="stylesheet">
    <link href="{{ assets('css/app.css') }}" rel="stylesheet">
    @stack('styles')
    {{-- <style>
        .title-sidebar.active{
            background-image: url('{{ assets("img/logo-1.png") }}');
            background-size: 20px;
        }
    </style> --}}
</head>

<body class="p-0 m-0">
    <aside class="p-0 m-0">
        @include('admin.sidebar')
    </aside>
    <main class="main-content bg-gray-100">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.1.0/datatables.min.js"></script>
    @stack('scripts')
    <script>
        $(document).ready(function() {
            var sidebarActive = false;

            $('.toggle-btn').click(function() {
                var el = $(this).find('i');

                if (el.hasClass('active')) {
                    el.removeClass('active');
                    sidebarActive = false;
                } else {
                    el.addClass('active');
                    sidebarActive = true;
                }
                activeSidebar();
            });

            function activeSidebar() {
                var sidebar = $('.container-sidebar');

                if (sidebar.hasClass('active')) {
                    sidebar.removeClass('active');
                    $('.sidebar a span').removeClass('active');
                    $('.title-sidebar span').removeClass('active');
                    $('.main-content').removeClass('active');
                    sidebarActive = false;
                } else {
                    sidebar.addClass('active');
                    $('.sidebar a span').addClass('active');
                    $('.title-sidebar span').addClass('active');
                    $('.main-content').addClass('active');
                    sidebarActive = true;
                }
            }

            

            $(window).resize(function(){
                 if($(window).width() < 991 && sidebarActive === false){
                     activeSidebar();
                 }
            })

            // activeSidebar();
            $('#datatable').DataTable();
        });
    </script>
</body>

</html>
