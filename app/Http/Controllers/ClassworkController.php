<?php

namespace App\Http\Controllers;

use App\Events\ClassworkCreated;
use App\Models\ClassroomModel;
use App\Models\Classwork;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ClassworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ClassroomModel $classroom)
    {
        $this->authorize('view-any', [Classwork::class, $classroom]);

        // $classworks = Classwork::where('classroom_id', '=', $classroom->id)->get();

        $classworks = $classroom->classworks()
            ->with('topic') //Eager loading
            ->filter($request->query())
            // ->orderBy('published_at', 'DESC')
            ->latest('published_at')
            ->where(function($query){
                $query->whereRaw('EXISTS (SELECT 1 FROM classwork_user
                    WHERE classwork_user.classwork_id = classworks.id
                    AND classwork_user.user_id = ?)', [Auth::id()]);

                $query->orwhereRaw('EXISTS (SELECT 1 FROM classroom_user
                    WHERE classroom_user.classroom_id = classworks.classroom_id
                    AND classroom_user.user_id = ?
                    AND classroom_user.role = ?)', [Auth::id(), 'teacher']);

            })
            ->paginate(5);

            return view('classworks.index', [
                'classroom' => $classroom,
                'classworks'=>  $classworks,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, ClassroomModel $classroom)
    {
        // $this->authorize('create', $classroom);

        Gate::authorize('classworks.create', [$classroom]);
        // if(!Gate::allows('classworks.create', [$classroom])){
        //     abort(404);
        // }

        $type = $this->get_type();
        $classwork = new Classwork();

        return view('classworks.create', compact('classroom', 'type', 'classwork'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ClassroomModel $classroom)
    {
        // $this->authorize('create', $classroom);

        Gate::authorize('classworks.create', [$classroom]);
        // if(Gate::denies('classworks.create', [$classroom])){
        //     abort(404);
        // }

        $type = $this->get_type();

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'topic_id' => ['nullable', 'int', 'exists:topic,id'],
            'options.grade' => [Rule::requiredIf(function () use ($type){
                return $type == 'assignment';
            }), 'numeric', 'min:0'],
            'options.due' => ['nullable','date', 'after:published_at'] ,
        ]);

        $request->merge([
            "user_id" => Auth::id(),
            'classroom_id' => $classroom->id,
            'type' => $type,
        ]);

        try{
            DB::transaction(function () use ($request, $classroom) {

                $classwork = $classroom->classworks()->create($request->all());
                // $classwork = Classwork::create($request->all());

            $classwork->users()->attach($request->input('students'));

            event(new ClassworkCreated($classwork));
            // ClassworkCreated::dispatch($classwork);

            });
        }catch (QueryException $e) {
            return back()->with('error', $e->getMessage());
        }



        return redirect()->route('classrooms.classworks.index', $classroom->id)
            ->with('success', __('Classwork Added'));
        }

    /**
     * Display the specified resource.
     */
    public function show(ClassroomModel $classroom, Classwork $classwork)
    {

        $this->authorize('view', $classwork);

        // if(!Gate::allows('classworks.view', [$classwork])){
        //     abort(404);
        // }

        $submissions = Auth::user()->submissions()->where('classwork_id', $classwork->id)->get();
        return view('classworks.show', compact('classroom', 'classwork', 'submissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,ClassroomModel $classroom, Classwork $classwork)
    {
        $this->authorize('update', $classwork);

        $type = $classwork->type;
        $assigned = $classwork->users()->pluck('id')->toArray();

        return view('classworks.edit', compact('classroom', 'classwork' ,'type', 'assigned'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassroomModel $classroom, Classwork $classwork)
    {
        $this->authorize('update', $classwork);

        $type = $classwork->type;

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'topic_id' => ['nullable', 'int', 'exists:topic,id'],
            'options.grade' => [Rule::requiredIf(function () use ($type){
                return $type == 'assignment';
            }), 'numeric', 'min:0'],
            'options.due' => ['nullable','date', 'after:published_at'] ,
        ]);

        $classwork->update($request->all());
        $classwork->users()->sync($request->input('students'));

        return back()
            ->with('success', __('Classwork Updated!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassroomModel $classroom, Classwork $classwork)
    {
        $this->authorize('delete', $classwork);
    }

    protected function get_type()
    {
        $type = request()->query('type');

        $allowed_types = [
            Classwork::TYPE_ASSIGNMENT,
            Classwork::TYPE_MATERIAL,
            Classwork::TYPE_QUESTION,
        ];

        if(!in_array($type, $allowed_types) ){
           abort(404);
        }

        return $type;
    }
}
