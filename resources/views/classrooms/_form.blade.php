<x-form.floating-control name="name" palceholder="Classroom Name">
    {{-- first way to show error  --}}
    {{-- <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Class Name"> --}}
    {{-- second way to show error  --}}
    {{-- <input type="text" value="{{ old('name', $classroom->name) }}" name="name" @class(['form-control', 'is-invalid' => $errors->has('name')])
        id="name" placeholder="Class Name"> --}}
    <x-form.input name="name" value="{{$classroom->name}}" placeholder="Classroom Name"/>
    {{-- @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror --}}
</x-form.floating-control>

<x-form.floating-control name="section" palceholder="Classroom Section">
    <x-form.input name="section" value="{{$classroom->section}}" placeholder="Classroom Section"/>
    {{-- <input type="text" value="{{ old('section', $classroom->section) }}" name="section" @class(['form-control', 'is-invalid' => $errors->has('section')])
        id="section" placeholder="Section"> --}}
    {{-- @error('section')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror --}}
</x-form.floating-control>
<x-form.floating-control name="subject" palceholder="Classroom Subject">
    <x-form.input name="subject" value="{{$classroom->subject}}" placeholder="Classroom Subject"/>
    {{-- <input type="text" value="{{ old('subject', $classroom->subject) }}" name="subject" @class(['form-control', 'is-invalid' => $errors->has('subject')])
        id="subject" placeholder="Subject"> --}}
    {{-- @error('subject')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror --}}
</x-form.floating-control>
<x-form.floating-control name="room" palceholder="Classroom Room">
    <x-form.input name="room" value="{{$classroom->room}}" placeholder="Classroom Room"/>
    {{-- <input type="text" value="{{ old('room', $classroom->room) }}" name="room" @class(['form-control', 'is-invalid' => $errors->has('room')])
        id="room" placeholder="Room"> --}}
    {{-- @error('room')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror --}}
</x-form.floating-control>
@if ($classroom->cover_image_path)
<br>
    <div>
        {{-- <img  src="/storage/{{$classroom->cover_image_path}}" height="250px"> --}}
        <img src="{{ asset('storage/' . $classroom->cover_image_path) }}" height="250px">
    </div>
<br>
@endif
<x-form.floating-control name="cover_image" palceholder="Cover Image">
    <x-form.input name="cover_image" type="file" value="{{$classroom->cover_image}}" placeholder="Classroom Image"/>
    {{-- <input type="file" name="cover_image" @class(['form-control', 'is-invalid' => $errors->has('cover_image')]) id="cover_image" placeholder="Cover Image"> --}}
    {{-- @error('cover_image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror --}}
</x-form.floating-control>
<br>
<button type="submit" class="btn btn-outline-primary">{{ $button_label }}</button>
