<x-main-layout title="Join Classrooms">
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="border p-5 text-center">
            <h2>{{$classroom->name}}</h2>
            <form class="mb-4" action="{{route('classrooms.join', $classroom->id)}}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">{{__('join')}}</button>
            </form>
        </div>
    </div>
</x-main-layout>
