<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\ClassroomModel;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\ClassroomResource;
use App\Http\Resources\ClassroomCollection;

class ClassroomsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return ClassroomModel::with('user:id,name', 'topics')
        // ->withCount('students', 'teachers')
        // ->paginate();

        $classrooms = ClassroomModel::with('user:id,name', 'topics')
        ->withCount('students', 'teachers')
        ->paginate();

        // return Response::json(ClassroomResource::collection($classroom));

        return new ClassroomCollection($classrooms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string']
        ]);

        $classroom = ClassroomModel::create($request->all());

        return [
            'code' => 100,
            'message' => __('Classroom Created.'),
            'classroom' => $classroom,
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassroomModel $classroom)
    {
        $classroom->load('user:id,name')->loadCount('students', 'teachers');
        return new ClassroomResource($classroom);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassroomModel $classroom)
    {
        $request->validate([
        'name' => ['sometimes', 'required', Rule::unique('classrooms', 'name')->ignore($classroom->id)/*,"unique:classrooms,name,$classroom->id"*/],
            'section' => ['sometimes', 'required'],
        ]);

        $classroom->updated($request->all());

        return [
            'code' => 100,
            'message' => __('Classroom Updated.'),
            'classroom' => $classroom,
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ClassroomModel::destroy($id);

        return Response::json([
            'code' => 100,
            'message' => __('Classroom Deleted.'),
        ]/*,204*/);
    }
}
