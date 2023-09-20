<x-main-layout title="create Classwork">
    <div class="container">
        <h1>{{$classroom->name}}(#{{$classroom->id}})</h1>
        <h3>Create Classworks</h3>
        <hr>

        <form action="{{route('classrooms.classworks.store', [$classroom->id, 'type' => $type])}}" method="POST">
            @csrf
            @include('classworks._classworkform')
        <button type="submit" class="btn btn-primary">Create</button>

    </form>
</div>
</x-main-layout>
