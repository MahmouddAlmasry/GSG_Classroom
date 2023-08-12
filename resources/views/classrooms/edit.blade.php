@extends('layouts.master')
@section('title', 'Classroom Website')
@section('content')
    <section class="container">
        <h1>Edit Classroom</h1>
        <x-all-errors/>
        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
        <form action="{{ route('classrooms.update', $classroom->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('put')
            @include('classrooms._form', [
                'button_label' => 'Update Classroom',
            ])
        </form>
    </section>
@endsection
