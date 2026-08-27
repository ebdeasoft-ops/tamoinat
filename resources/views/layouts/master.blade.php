<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <script src="https://kit.fontawesome.com/164ea36700.js" crossorigin="anonymous"></script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="Keywords"
        content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4" />
    @include('layouts.head')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&family=Jost:wght@100;300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&family=Shantell+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&family=Jost:wght@100;300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&family=Shantell+Sans:wght@300;400;500;600;700&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">

</head>
{{-- style="direction:{{App::getLocale()=='ar'?'rtl':'ltr'}}" --}}
<body class="main-body app sidebar-mini  master" style="direction:{{App::getLocale()=='ar'?'rtl':'ltr'}}"
 >
    <!-- Loader -->
    <div id="global-loader">
        <img src="{{ URL::asset('assets/img/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    <!-- /Loader -->
    <div class="main-content app-content">
        @include('layouts.main-sidebar')
        <!-- main-content -->
        @include('layouts.main-header')
        <!-- container -->
        <div class="container-fluid">
            @yield('page-header')
            @yield('content')
            @include('layouts.sidebar')
            @include('layouts.models')
            @include('layouts.footer')
            @include('layouts.footer-scripts')
        </div>
    </div>        
</body>

</html>

<script>
    setInterval(function() {
        $("#notifications_count").load(window.location.href + " #notifications_count");
        $("#unreadNotifications").load(window.location.href + " #unreadNotifications");
    }, 5000);

</script>

