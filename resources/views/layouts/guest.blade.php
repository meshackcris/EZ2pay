<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
    <link href="./css/style.css" rel="stylesheet">
 <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js' ])
    </head>
</head>


<body class="vh-100">        
                {{ $slot }}
    </body>
</html>
