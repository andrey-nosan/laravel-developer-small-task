@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="header">
            <div class="mb-3 float-sm-right">
                <a href="{{  route('message.create')}}" class="btn btn-primary btn-sm active" role="button">Create message</a>
            </div>
        </div>
        <div class="body">
            <table class="table table-sm table-bordered">
                <thead class="thead-light">
                <tr>
                    <th scope="col">&nbsp;</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Body</th>
                    <th scope="col">Sent</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($messages as $message)
                    <tr>
                        <th scope="row">{{ $loop->iteration + $messages->perPage() * ($messages->currentPage() - 1) }}</th>
                        <td>{{ $message->subject }}</td>
                        <td>{{ $message->body_content }}</td>
                        <td>{{ $message->sent ? __('Yes') : __('No') }}</td>
                        <td>
                            <form action="{{ route('message.destroy', $message) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <a class="btn btn-primary" href="{{ route('message.edit',$message) }}" title="Edit message">Edit</a>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
    {{--                        <a href="{{  route('message.send', $message)}}" title="Send message">--}}
    {{--                            <i class="material-icons">send</i>--}}
    {{--                        </a>--}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">{{__('Messages not found')}}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="list-unstyled row clearfix paginate-wrapper-row">
                <div class="col-xs-12">
                    {{ $messages->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
