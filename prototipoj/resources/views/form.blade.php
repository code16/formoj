<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Prototipoj</title>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-weight: 200;
                margin: 0;
            }
        </style>
    </head>
    <body>
        <h1>Prototipoj : {{ $form->title }}</h1>
        <formoj form-id="{{ $form->id }}"></formoj>
    </body>
</html>
