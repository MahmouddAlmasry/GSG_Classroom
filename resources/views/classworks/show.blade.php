<x-main-layout title="create Classwork">
    <div class="container">
        <h1>{{ $classroom->name }}(#{{ $classroom->id }})</h1>
        <h3>{{ $classwork->title }}</h3>
        <hr>
        <div class="row">
            <div class="col-md-8">
                <x-alert name="success" class="alert-success" id="success" />
                <x-alert name="error" class="alert-danger" id="error" />

                <div>
                    <p>{{ $classwork->description }}</p>
                </div>
                <h4>Comments</h4>
                <form action="{{ route('comments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $classwork->id }}">
                    <input type="hidden" name="type" value="classwork">
                    <div class="d-flex">
                        <div class="col-12">
                            <x-form.floating-control name="content" palceholder="Comment">
                                <x-form.textarea name="content" style="height: 100px" placeholder="Comment" />
                            </x-form.floating-control>
                        </div>
                    </div>
                    <div class="ms-1">
                        <button type="submit" class="btn btn-primary">Comment</button>
                    </div>
                </form>
                <div class="mt-4">
                    @foreach ($classwork->comments()->with('user')->get() as $comment)
                        <div class="row">
                            <div class="col-md-2">
                                <img src="https://ui-avatars.com/api/?name={{ $comment->user->name }}" alt="">
                            </div>
                            <div class="col-md-10">
                                <p>By: {{ $comment->user->name }}. Time:
                                    {{ $comment->created_at->diffForHumans(null, true) }}</p>
                                <p>{{ $comment->content }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                @can('submissions.create', [$classwork])
                <div class="bordered rounded p-3 bg-light">
                    <h4>Submissions</h4>
                    @if ($submissions->count())
                        <ul>
                            @foreach ($submissions as $i => $submission)
                                <li><a href="{{route('submissions.file', $submission->id)}}">File #{{$i+1}}</a></li>
                            @endforeach
                        </ul>
                    @else
                    <form method="POST" action="{{route('submissions.store', $classwork->id)}}" enctype="multipart/form-data">
                        @csrf
                        <x-form.floating-control name="files" palceholder="Upload">
                            <x-form.input type="file" name="files[]" multiple placeholder="Select Files" />
                        </x-form.floating-control>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                    @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
