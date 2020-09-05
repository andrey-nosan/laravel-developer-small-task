<div class="form-group">
    <div class="form-line">
        <label for="subject_input">Subject</label>
        <input type="text"
               name="subject"
               value="{{old('subject', $message->subject ?? '')}}"
               class="form-control @if ($errors->has('subject'))is-invalid @endif"
               id="subject_input"
               placeholder="Message subject">
    </div>
    @if ($errors->has('subject'))
        <span id="subject-error" class="error text-danger">{{ $errors->first('subject') }}</span>
    @endif
</div>
<div class="form-group">
    <div class="form-line">
        <label for="body_input">Body</label>
        <textarea name="body" class="form-control @if ($errors->has('body'))is-invalid @endif"
                  id="body_input" rows="3">{{ old('body', $message->body_content ?? '') }}</textarea>
    </div>
    @if ($errors->has('body'))
        <span id="body-error" class="error text-danger">{{ $errors->first('body') }}</span>
    @endif
</div>
<div class="form-group">
    <label for="recipient_teacher">Teachers:</label>
    <select class="form-control" name="teachers[]" id="recipient_teacher" multiple>
        @foreach($teachers as $teacher)
            <option value="{{ $teacher->getKey() }}" @if (isset($message) && $message->teachers->contains($teacher))
            selected
                @endif>{{ $teacher->full_name }} ({{ $teacher->email }})</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="recipient_student">Students:</label>
    <select class="form-control" name="students[]" id="recipient_student" multiple>
        @foreach($students as $student)
            <option value="{{ $student->getKey() }}" @if (isset($message) && $message->students->contains($student))
            selected
                @endif>{{ $student->full_name }} ({{ $student->email }})</option>
        @endforeach
    </select>
</div>
<a href="{{ route('message.index') }}" class="btn btn-outline-primary">{{ __('Cancel') }}</a>
<button type="submit" class="btn btn-primary">{{ isset($message) ? __('Update') : __('Save') }}</button>
