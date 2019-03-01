@extends('layout')

@section('content')
    <h2>{{ $form->title }}</h2>
    <formoj form-id="{{ $form->id }}"></formoj>
@endsection
