@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="header">
            <h2>{{ __('Edit Message') }}</h2>
        </div>
        <div class="body">
            <form action="{{ route('message.update', $message->getKey()) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="subject_input">Subject</label>
                    <input type="text"
                           name="subject"
                           value="{{old('subject', $message->subject)}}"
                           class="form-control @error('subject') is-invalid @enderror"
                           id="subject_input"
                           placeholder="Message subject">
                    @error('subject')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="body_input">Body</label>
                    <textarea name="body"
                              class="form-control @error('body') is-invalid @enderror"
                              id="body_input"
                              rows="3">{{ old('body', $message->body_content) }}</textarea>
                    @error('body')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="recipient_teacher">Teachers:</label>
                    <select class="form-control" name="teachers[]" id="recipient_teacher" multiple>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->getKey() }}"
                                {{$message->teachers->contains($teacher) ? 'selected' : ''}}
                            >{{ $teacher->full_name }} ({{ $teacher->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="recipient_student">Students:</label>
                    <select class="form-control" name="students[]" id="recipient_student" multiple>
                        @foreach($students as $student)
                            <option value="{{ $student->getKey() }}"
                                {{$message->students->contains($student) ? 'selected' : ''}}
                            >{{ $student->full_name }} ({{ $student->email }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>

            </form>
        </div>
    </div>
@endsection
