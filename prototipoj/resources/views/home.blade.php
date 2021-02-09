@extends('layout')

@section('content')
    <h1 class="h4">Formulaires</h1>
    <ul>
        @foreach($forms as $form)
            <li>
                <a href="/forms/{{ $form->id }}">
                    {{ $form->title }}
                </a>

                <ul>
                    @foreach($form->answers as $answer)
                        <li>
                            <a href="/answers/{{ $answer->id }}">
                                Answer {{ $answer->created_at->format('Y-m-d H:i:s') }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
@endsection
