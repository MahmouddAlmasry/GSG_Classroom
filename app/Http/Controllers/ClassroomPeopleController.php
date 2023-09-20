<?php

namespace App\Http\Controllers;

use App\Models\ClassroomModel;
use Illuminate\Http\Request;

class ClassroomPeopleController extends Controller
{
    public function index(ClassroomModel $classroom)
    {
        return view('classrooms.people', compact('classroom'));
    }

    public function destroy(Request $request, ClassroomModel $classroom)
    {
        $request->validate([
            'user_id' => ['required'],
        ]);

        $user_id = $request->input('user_id');

        dd($classroom->user_id);
        if($user_id !== $classroom->user_id){
            return redirect()->route('classrooms.people', $classroom->id)
            ->with('error', 'Cannot Remove User!');
        }

        $classroom->users->detach($user_id);

        return redirect()->route('classrooms.people', $classroom->id)
            ->with('success', 'User removed!');
    }
}
