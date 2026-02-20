<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background:
                @yield('bg-color', '#33cc99')
            ;
            color: #fff;
            font-family: 'Open Sans', sans-serif;
            height: 100vh;
            overflow: hidden;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        #clouds {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1;
        }

        .container {
            text-align: center;
            position: relative;
            z-index: 10;
            width: 90%;
            max-width: 800px;
        }

        ._code {
            font-size: clamp(120px, 20vw, 220px);
            font-weight: 800;
            position: relative;
            display: inline-block;
            line-height: 1;
            letter-spacing: 10px;
            margin-bottom: 20px;
            text-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        hr {
            padding: 0;
            border: none;
            border-top: 5px solid #fff;
            color: #fff;
            text-align: center;
            margin: 0 auto 30px;
            width: 60%;
            max-width: 420px;
            position: relative;
            overflow: visible;
        }

        hr:after {
            content: "\2022";
            display: inline-block;
            position: relative;
            top: -0.75em;
            font-size: 2em;
            padding: 0 0.3em;
            background:
                @yield('bg-color', '#33cc99')
            ;
        }

        ._msg-1 {
            display: block;
            letter-spacing: 8px;
            font-size: clamp(24px, 5vw, 4em);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        ._msg-2 {
            display: block;
            font-size: clamp(16px, 2vw, 25px);
            opacity: 0.9;
            margin-bottom: 40px;
            font-weight: 500;
        }

        .btn-back {
            background-color: #fff;
            display: inline-block;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 20px;
            font-weight: 800;
            color:
                @yield('bg-color', '#33cc99')
            ;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .btn-back:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            background-color: #f8f8f8;
        }

        /* ── Cloud styling ── */
        .cloud {
            width: 350px;
            height: 120px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 100px;
            position: absolute;
            z-index: 1;
        }

        .cloud:after,
        .cloud:before {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.9);
            z-index: -1;
        }

        .cloud:after {
            width: 100px;
            height: 100px;
            top: -50px;
            left: 50px;
            border-radius: 100px;
        }

        .cloud:before {
            width: 180px;
            height: 180px;
            top: -90px;
            right: 50px;
            border-radius: 200px;
        }

        .x1 {
            left: 10%;
            top: 10%;
            transform: scale(0.3);
            opacity: 0.8;
            animation: moveclouds 15s linear infinite;
        }

        .x1_5 {
            left: 30%;
            top: 5%;
            transform: scale(0.4);
            opacity: 0.5;
            animation: moveclouds 12s linear infinite;
        }

        .x2 {
            left: 45%;
            top: 20%;
            transform: scale(0.6);
            opacity: 0.6;
            animation: moveclouds 25s linear infinite;
        }

        .x3 {
            left: 50%;
            bottom: 10%;
            transform: scale(0.5);
            opacity: 0.7;
            animation: moveclouds 20s linear infinite;
        }

        .x4 {
            left: 70%;
            top: 15%;
            transform: scale(0.75);
            opacity: 0.4;
            animation: moveclouds 18s linear infinite;
        }

        .x5 {
            left: 80%;
            bottom: 20%;
            transform: scale(0.5);
            opacity: 0.8;
            animation: moveclouds 22s linear infinite;
        }

        @keyframes moveclouds {
            0% {
                margin-left: 100vw;
            }

            100% {
                margin-left: -100vw;
            }
        }

        @media (max-width: 480px) {
            ._code {
                letter-spacing: 5px;
            }

            hr {
                width: 80%;
            }
        }
    </style>
</head>

<body>
    <div id="clouds">
        <div class="cloud x1"></div>
        <div class="cloud x1_5"></div>
        <div class="cloud x2"></div>
        <div class="cloud x3"></div>
        <div class="cloud x4"></div>
        <div class="cloud x5"></div>
    </div>

    <div class="container">
        <div class="_code">@yield('code')</div>
        <hr>
        <div class="_msg-1">@yield('msg-1')</div>
        <div class="_msg-2">@yield('msg-2')</div>
        <a class="btn-back" href="javascript:history.back()">Go Back</a>
    </div>
</body>

</html>