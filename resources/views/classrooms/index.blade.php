    {{-- @extends('layouts.master')
    @section('title', 'Classroom Website')
    @section('content') --}}
    <x-main-layout title="{{__('Classrooms')}}">

        <section class="container">
            <h1>{{__('Classrooms')}}</h1>

            <x-alert name="success" class="alert-success" id="success"/>
            <x-alert name="error" class="alert-danger" id="error"/>
            {{-- //this code change to <x-alert/> --}}
            {{-- @if (session()->has('success'))
                <div class="alert alert-success">
                    From Component: {{ session('success') }}
                </div>
            @endif --}}

            <div>
                <a href="{{ route('classrooms.create') }}" class="btn btn-outline-primary">{{__('Create')}}</a>
                <a href="{{ route('classrooms.trashed') }}" class="btn btn-outline-dark">{{__('Trashed')}}</a>
            </div>
            <br>
            <div class="row">
                @foreach ($classrooms as $classroom)
                    <div class="col-md-3">
                        <div class="card">
                            @if ($classroom->cover_image_path)
                                <img src="/storage/{{ $classroom->cover_image_path }}" class="card-img-top" alt="Card image cap">
                            @else
                            <img src="https://placehold.co/600x400" class="card-img-top" alt="Card image cap">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $classroom->name }}</h5>
                                <p class="card-text">{{ $classroom->section }} - {{ $classroom->room }}</p>
                                {{-- // use it with model binig --}}
                                {{-- //if you want use original way put $id as parameter and active the comment --}}
                                <div class="d-flex justify-content-between">
                                <a href="{{ route('classrooms.show', $classroom->id) }}"
                                    class="btn btn-sm btn-outline-primary">{{__('View')}}</a>
                                {{-- <a href="{{ route('classrooms.show', $classroom->code) }}" class="btn btn-sm btn-outline-primary">View</a> --}}
                                <a href="{{ route('classrooms.edit', $classroom->id) }}"
                                    class="btn btn-sm btn-outline-success">{{__('Edit')}}</a>
                                <form action="{{ route('classrooms.destroy', $classroom->id) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-sm btn-outline-danger">{{__('Delete')}}</button>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </x-main-layout>
    {{-- @endsection --}}

    {{-- to add files css or javascript or any files only in this page, use stack --}}
    {{-- @push('script')
<script>alert(1)</script>
@endpush
@push('script')
<script>alert(2)</script>
@endpush --}}
