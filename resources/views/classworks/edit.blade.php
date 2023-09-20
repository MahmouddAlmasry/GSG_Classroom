<x-main-layout title="create Classwork">
    <div class="container">
        <h1>{{$classroom->name}}(#{{$classroom->id}})</h1>
        <h3>Update Classworks</h3>
        <hr>

        <x-alert name="success" class="alert-success" id="success"/>
            <x-alert name="error" class="alert-danger" id="error"/>

        <form action="{{route('classrooms.classworks.update', [$classroom->id, $classwork->id,  'type' => $type])}}" method="POST">
            @csrf
            @method('put')
            @include('classworks._classworkform');
            <button type="submit" class="btn btn-primary">Update</button>

    </form>
</div>
</x-main-layout>
