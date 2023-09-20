<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomRequest;
use App\Models\ClassroomModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ClassroomsController extends Controller
{
    public function __construct()
    {
        $this->middleware('subscriped')->only('create', 'store');
    }

    public function index()
    {
        // three way to get data from session
        // session()->get('success');
        // Session::get('success');
        $success = session('success');

        // to put data into session
        // Session::put('key', 'value');//the value will stay always in session
        // Session::flash('key', 'value');//the value will delete after few time

        // $classrooms = ClassroomModel::where('user-id', '=', Auth::user()->id)->get();
        $classrooms = ClassroomModel::all();
        return view('classrooms.index', compact('classrooms', 'success'));
    }

    public function create(){
        $classroom = new ClassroomModel();
        return view('classrooms.create', compact('classroom'));
    }

    public function store(ClassroomRequest $request)
    {
        // this code now in ClassroomRequest
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'section' => 'nullable|string|max:255',
        //     'subject' => 'nullable|string|max:255',
        //     'room' => 'nullable|string|max:255',
        //     'cover_image' => [
        //         'nullable',
        //         'image',
        //         // Rule::dimensions([
        //         //     'min_width' => 600,
        //         //     'min_height' => 300,
        //         // ]),
        //     ],
        // ],[
        //     'required'=>"The :attribute field is required.",
        //     "unique"=>":Attribute already exists",
        // ]);

        if($request->has('cover_image')){
            $file = $request->file('cover_image');//UploadedFile

            $path = ClassroomModel::uploadCoverImage($file);

            $request->merge([
                'cover_image_path' => $path,
            ]);
        }

        $request->merge([
            // 'code' => Str::random(8),
            'user-id' => Auth::id(),
        ]);

        DB::beginTransaction();
        try{
            $classroom = ClassroomModel::create( $request->all());

            DB::table('classroom_user')->insert([
                'classroom_id' => $classroom->id,
                'user_id' => Auth::id(),
                'role' => 'teacher',
                'created_at' => now(),
            ]);
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }


        return redirect()->route('classrooms.index')
        ->with('success', 'Classroom Created!');
    }

    // use it with model binig
    //if you want use original way put $id as parameter and active the comment
    public function show(ClassroomModel $classroom)
    {
        // $classroom = ClassroomModel::findOrFail($id);

        $invitation_link =URL::temporarySignedRoute('classrooms.join',now()->addHour(3), [
            'classroom' => $classroom->id,
        'code' => $classroom->code]);

        return view('classrooms.show', [
            'classroom' => $classroom,
            'invitation_link' => $invitation_link,
        ]);
    }

    public function edit(ClassroomModel $classroom)
    {
        // $classroom = ClassroomModel::findOrFail($id);
        // if(!$classroom){
        //     abort(404);
        // }
        return view('classrooms.edit', [
            'classroom' => $classroom,
        ]);
    }

    public function update(ClassroomRequest $request, ClassroomModel $classroom)
    {
        //this code now in ClassroomRequest
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'section' => 'nullable|string|max:255',
        //     'subject' => 'nullable|string|max:255',
        //     'room' => 'nullable|string|max:255',
        //     'cover_image' => [
        //         'nullable',
        //         'image',
        //         // Rule::dimensions([
        //         //     'min_width' => 600,
        //         //     'min_height' => 300,
        //         // ]),
        //     ],
        // ],[
        //     'required'=>"The :attribute field is required.",
        //     "unique"=>":Attribute already exists",
        // ]);
        if($request->has('cover_image')){
            $file = $request->file('cover_image');//UploadedFile
            //Solution 1
            // $name = $classroom->cover_image_path ?? (Str::random(40) . '.' . $file->getClientOriginalExtension());
            // $path = $file->storeAs('/covers', basename($name) ,[
            //     'disk' => 'public'
            // ]);

            //solution2
            $path = $classroom->uploadCoverImage($file);

            $old_Image = $classroom->cover_image_path;

            $request->merge([
                'cover_image_path' => $path,
            ]);
        }
        // $classroom = ClassroomModel::findorFail($id);
        $classroom->update($request->all());
        return Redirect::route('classrooms.index')
            ->with('success', 'Classroom Updated!');
            //part of solution 2
        if($old_Image && $old_Image != $classroom->cover_image_path){
            $classroom->deleteCoverImage($old_Image);
        }
    }

    //if we need the data maybe use the binig model else stay on this way
    public function destroy(ClassroomModel $classroom)
    {
        // $count = ClassroomModel::destroy($id);
        // $classroom = ClassroomModel::findOrFail($id);
        $classroom->delete();
        // when we use soft delete we dont use delete image in destroy
        // $classroom->deleteCoverImage($classroom->cover_image_path);
        //use with redirect Flash Message
        return redirect(route('classrooms.index'))
            ->with('error', 'Recored Deleted!');

    }

    public function trashed(){
        $classrooms = ClassroomModel::onlyTrashed()->latest('deleted_at')->get();

        return view('classrooms.trashed', compact('classrooms'));
    }

    public function restore($id){
        $classroom = ClassroomModel::onlyTrashed()->findOrFail($id);
        $classroom->restore();
        return redirect()->route('classrooms.index')->with('success', "Classroom ({$classroom->name}) Restore!");
    }

    public function forceDelete($id){
        $classroom = ClassroomModel::withTrashed()->findOrFail($id);
        $classroom->forceDelete();
        // $classroom->deleteCoverImage($classroom->cover_image_path);
        return redirect()->route('classrooms.trashed')->with('success', "Classroom ({$classroom->name}) Deleted Forever!");
    }
}
