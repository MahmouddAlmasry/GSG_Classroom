<?php

namespace App\Http\Controllers;

use App\Models\ClassroomModel;
use App\Models\Classwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ClassroomModel $classroom)
    {
        // $classworks = Classwork::where('classroom_id', '=', $classroom->id)->get();

        $classworks = $classroom->classworks()
            ->orderBy('published_at')->get();

            return view('classworks.index', [
                'classroom' => $classroom,
                'classworks'=>  $classworks,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ClassroomModel $classroom)
    {
        return view('classworks.create', compact('classroom'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ClassroomModel $classroom)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'topic' => ['nullable', 'int', 'exists:topic,id'],
        ]);

        $request->merge([
            "user_id" => Auth::id(),
            'classroom_id' => $classroom->id,
        ]);

        // $classwork = $classroom->classworks()->create($request->all());
        $classwork = Classwork::create($request->all());

        return redirect()->route('classrooms.classrorks.index', $classroom->id)
            ->with('success', 'Classwork Added');
        }

    /**
     * Display the specified resource.
     */
    public function show(ClassroomModel $classroom, Classwork $classwork)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassroomModel $classroom, Classwork $classwork)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassroomModel $classroom, Classwork $classwork)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassroomModel $classroom, Classwork $classwork)
    {
        //
    }
}
