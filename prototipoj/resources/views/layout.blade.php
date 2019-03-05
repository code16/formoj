<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Prototipoj</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

</head>
<body>
    <div id="app">
        <div class="container">
            <div class="content">
                <h1 class="mt-3">Prototipoj</h1>
                <hr>
                <div class="my-5">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>