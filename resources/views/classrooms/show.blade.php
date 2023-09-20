@extends('layouts.master')
@section('title', 'Classroom Website')
@section('content')
    <section class="container">
        <h1>Classroom Details</h1>
        <h2>{{ $classroom->name }} => #{{ $classroom->id }}</h2>
        <h3>{{$classroom->section}}</h3>
        <div class="row">
            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <span class="text-success fs-2">{{$classroom->code}}</span>0
                </div>
            </div>
            <div class="col-md-9">
                <p>Invitation Link: <a href="{{$invitation_link}}">{{$invitation_link}}</a></p>

                <p><a href="{{route('classrooms.classworks.index', $classroom->id)}}" class="btn btn-outline-dark">Classworks</a></p>
            </div>
        </div>
    </section>
@endsection
