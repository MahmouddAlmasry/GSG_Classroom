<x-main-layout :title="$classroom->name">
    <section class="container">
        <h2>{{ $classroom->name }} => #{{ $classroom->id }}</h2>
        <h3>Classworks
            @if (Gate::allows('classworks.create', [$classroom]))
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Create
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{route('classrooms.classworks.create', [$classroom->id, 'type' => 'assignment'])}}">Assignment</a></li>
                    <li><a class="dropdown-item" href="{{route('classrooms.classworks.create', [$classroom->id, 'type' => 'material'])}}">Material</a></li>
                    <li><a class="dropdown-item" href="{{route('classrooms.classworks.create', [$classroom->id, 'type' => 'question'])}}">Questions</a></li>
                </ul>
            </div>
            @endif
        </h3>
        <hr>


        <form class="row row-cols-lg-auto g-3 align-items-center" method="get" action="{{ URL::current() }}">
            <div class="col-12">
                <input type="text" placeholder="Search" name="search" class="form-control" >
            </div>
            <div class="col-12">
                <button type="submit" class="ms-2 btn btn-primary">Find</button>
            </div>
        </form>

        {{-- <h3>{{$group->first()->topic->name}}</h3> --}}
        <div class="accordion accordion-flush" id="accordionFlushExample">
            @foreach ($classworks as $classwork)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapse{{ $classwork->id }}" aria-expanded="false"
                            aria-controls="flush-collapseThree">
                            {{ $classwork->title }}
                        </button>
                    </h2>
                    <div id="flush-collapse{{ $classwork->id }}" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">{{ $classwork->description }}</div>
                        <div>
                            <a class="btn btn-sm btn-outline-success" href="{{route('classrooms.classworks.edit', [$classwork->classroom_id, $classwork->id])}}">Edit</a>
                            <a class="btn btn-sm btn-outline-dark" href="{{route('classrooms.classworks.show', [$classwork->classroom_id, $classwork->id])}}">View Instruction</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            {{$classworks->appends(['v' => Str::random(8),])->withQueryString()->links('vendor.pagination.bootstrap-5')}}
    </section>
@push('scripts')
    <script>
        classroomId = "{{ $classwork->classroom_id }}";
    </script>
@endpush
</x-main-layout>
