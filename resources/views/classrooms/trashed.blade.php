    {{-- @extends('layouts.master')
    @section('title', 'Classroom Website')
    @section('content') --}}
    <x-main-layout title="Classrooms">
        <section class="container">
            <h1>Trashed Classroom</h1>

            <x-alert name="success" class="alert-success" id="success"/>
            <x-alert name="error" class="alert-danger" id="error"/>
            {{-- //this code change to <x-alert/> --}}
            {{-- @if (session()->has('success'))
                <div class="alert alert-success">
                    From Component: {{ session('success') }}
                </div>
            @endif --}}
            <div class="row">
                <div>
                    <a href="{{ route('classrooms.index') }}" class="btn btn-outline-dark">Classrooms</a>
                </div>
                @foreach ($classrooms as $classroom)
                    <div class="col-md-3">
                        <div class="card">
                            <img src="/storage/{{ $classroom->cover_image_path }}" class="card-img-top" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">{{ $classroom->name }}</h5>
                                <p class="card-text">{{ $classroom->section }} - {{ $classroom->room }}</p>
                                {{-- // use it with model binig --}}
                                {{-- //if you want use original way put $id as parameter and active the comment --}}
                                <div class="d-flex justify-content-between">
                                    <form method="POST" action="{{route('classrooms.restore', $classroom->id)}}">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-sm btn-outline-success">Restore</button>
                                    </form>
                                    <form action="{{ route('classrooms.force-delete', $classroom->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-sm btn-outline-danger">Force Delete</button>
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
