@extends('layouts.app')

@section('title')
    {{ __('Create message') }}
@endsection

@section('content')
    <div class="header">
        <h2>{{ __('Create message') }}</h2>
    </div>
    <div class="body">
        <form action="{{ route('message.store') }}" method="POST">
            @csrf
            @include('pages.message._form')
        </form>
    </div>
@endsection
