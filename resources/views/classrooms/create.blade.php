@extends('layouts.master')
@section('title', 'Classroom Website')
@section('content')
<x-alert name="error" class="alert-danger" id="error"/>
    <section class="container">
        <h1>Create Classroom</h1>
        <x-all-errors/>
        {{-- to check if errors existing and show it --}}
        {{-- --$errors-- as object stored static in laravel --}}
        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
        <form action="{{ route('classrooms.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('classrooms._form',[
                'button_label' => 'Create Classroom'
            ])
        </form>
    </section>
@endsection
