@extends('layouts.app')

@section('content')
    <div class="panel-body table-responsive">
        <router-view name="messageIndex"></router-view>
        <router-view></router-view>
    </div>
@endsection
