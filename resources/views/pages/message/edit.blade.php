@extends('layouts.app')

@section('title')
    {{ __('Edit Message') }}
@endsection

@section('content')
    <div class="header">
        <h2>{{ __('Edit Message') }}</h2>
    </div>
    <div class="body">
        <form action="{{ route('message.update', $message) }}" method="POST">
            @csrf
            @method('PUT')
            @include('pages.message._form')
        </form>
    </div>
@endsection
