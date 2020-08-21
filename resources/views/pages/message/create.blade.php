@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="header">
            <h2>{{ __('Create message') }}</h2>
        </div>
        <div class="body">
            <form action="{{ route('message.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="subject_input">Subject</label>
                    <input type="text"
                           name="subject"
                           class="form-control"
                           id="subject_input"
                           placeholder="Message subject"
                           required>
                </div>
                <div class="form-group">
                    <label for="body_input">Body</label>
                    <textarea name="body" class="form-control" id="body_input" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="recipient_teacher">Teachers:</label>
                    <select class="form-control" name="teachers[]" id="recipient_teacher" multiple>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->getKey() }}">{{ $teacher->full_name }} ({{ $teacher->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="recipient_student">Students:</label>
                    <select class="form-control" name="students[]" id="recipient_student" multiple>
                        @foreach($students as $student)
                            <option value="{{ $student->getKey() }}">{{ $student->full_name }} ({{ $student->email }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </form>
        </div>
    </div>
@endsection
