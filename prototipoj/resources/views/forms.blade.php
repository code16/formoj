@extends('layout')

@section('content')
    <ul>
        @foreach($forms as $form)
            <li>
                <a href="/forms/{{ $form->id }}">
                    {{ $form->title }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection
