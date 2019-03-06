<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body>
    <div class="container">
        <div class="content">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="mt-3">#1 {{ $answer->form->title ?: trans("formoj::sharp.forms.no_title") }}</h1>
                    <h4 class="mt-1">{{ $answer->created_at->formatLocalized("%A %e %B %Y %Hh%M") }}</h4>
                    <hr>
                    <div class="my-5">
                        @foreach($answer->content as $field => $value)
                            <div class="list-group mb-3">
                                <div class="list-group-item">{{ $field }}</div>
                                <div class="list-group-item font-weight-bold">
                                    @if(is_array($value))
                                        @foreach($value as $valueItem)
                                            <div>{{ $valueItem }}</div>
                                        @endforeach
                                    @else
                                        {{ $value }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>