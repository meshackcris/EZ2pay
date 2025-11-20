<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <meta name="robots" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Orion Pay" />
    <meta property="og:title" content="Orion Pay" />
    <meta property="og:description" content="Orion Pay" />
    <meta property="og:image" content="https://invome.dexignlab.com/xhtml/social-image.png" />
    <meta name="format-detection" content="telephone=no">
    
    <!-- PAGE TITLE HERE -->
<title>{{ $title ?? 'Default Title' }} - Orion Pay</title>
    
    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="images/favicon.png" />
   <link href="{{ asset('vendor/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/nouislider/nouislider.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@livewireStyles

    <!-- Style css -->
            @vite(['resources/css/app.css', 
            'resources/js/app.js',
            ])
</head>

    <body >
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
            @include('layouts.navigation')

          

            <!-- Page Content -->
        <div class="content-body">
                {{ $slot }}
            </div>
        </div>

<!-- Load jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Load Core -->

<!-- ✅ Load jQuery plugins BEFORE custom.min.js -->
<script src="https://cdn.jsdelivr.net/npm/metismenu/dist/metisMenu.min.js"></script>
<script src="{{ asset('vendor/jquery-nice-select/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('vendor/global/global.min.js') }}"></script>

<!-- Load charts & other vendors -->
<script src="{{ asset('vendor/chart.js/Chart.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/apexchart/apexchart.js') }}"></script>
<script src="{{ asset('vendor/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('vendor/nouislider/nouislider.min.js') }}"></script>
<script src="{{ asset('vendor/wnumb/wNumb.js') }}"></script>
<script src="{{ asset('vendor/owl-carousel/owl.carousel.js') }}"></script>

<!-- ✅ Custom scripts (after all jQuery plugins) -->
<script src="{{ asset('js/custom.min.js') }}"></script>
<script src="{{ asset('js/dlabnav-init.js') }}"></script>
<script src="{{ asset('js/demo.js') }}"></script>
<script src="{{ asset('js/styleSwitcher.js') }}"></script>
<script src="{{ asset('js/plugins-init/chartjs-init.js') }}"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>
        jQuery(document).ready(function(){
            setTimeout(function(){
                dlabSettingsOptions.version = 'dark';
                new dlabSettings(dlabSettingsOptions);
            },1500)
        });
    </script>
@livewireScripts
@stack('scripts')
    </body>
</html>
