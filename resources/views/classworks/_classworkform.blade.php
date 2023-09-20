
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $message)
                <li>{{$message}}</li>
            @endforeach
        </ul>
    </div>
@endif
<x-alert name="success" class="alert-success" id="success"/>
<x-alert name="error" class="alert-danger" id="error"/>
<div class="row">
    <div class="col-md-8">
        <x-form.floating-control name="title" palceholder="Title">
            <x-form.input name="title" :value="$classwork->title" placeholder="Title"/>
        </x-form.floating-control>

        <x-form.floating-control name="description" :value="$classwork->description">
            <x-form.textarea name="description" style="height: 150px"/>
        </x-form.floating-control>
    </div>


    <div class="col-md-4">
        <x-form.floating-control name="published_at" palceholder="published_at">
            <x-form.input name="published_at" :value="$classwork->published_date" type="date"/>
            </x-form.floating-control>



        @foreach ($classroom->students as $student)
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="students[]" value="{{$student->id}}" id="std-{{$student->id}}" @checked( !isset($assigned) || in_array($student->id, $assigned ?? []))>
            <label class="form-check-label" for="std-{{$student->id}}">
                {{$student->name}}
            </label>
          </div>
        @endforeach

        @if ($type == 'assignment')

        <x-form.floating-control name="options.grade" palceholder="Grade">
        <x-form.input name="options[grade]" :value="$classwork->options['grade'] ?? ''" type="number" min="0"/>
        </x-form.floating-control>

        <x-form.floating-control name="options.due" palceholder="Due">
        <x-form.input name="options[due]" :value="$classwork->options['due'] ?? ''" type="date"/>
        </x-form.floating-control>
        @endif

        <x-form.floating-control name="topic_id" palceholder="Topic (Optional)">
            <select class="form-select" name="topic_id" id="topic_id">
                <option value="">No Topic</option>
                @foreach ($classroom->topics as $topic)
                    <option @selected($topic->id == $classwork->topic_id) value="{{$topic->id}}">{{$topic->name}}</option>
                @endforeach
            </select>
         <x-single-error name="topic_id"/>
        </x-form.floating-control>
    </div>
 </div>
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endpush
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#description').summernote();
        });
    </script>
@endpush
