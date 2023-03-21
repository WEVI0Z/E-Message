@extends('layouts.layout')

@section('content')
    @include('layouts.header')
    <main>
        @include('side-panel.main')
        @include('messanger.main')
    </main>
@endsection