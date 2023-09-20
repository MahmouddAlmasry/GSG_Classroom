<?php

namespace App\Http\Controllers;

use App\Models\Classwork;
use App\Models\ClassworkUser;
use App\Models\Submission;
use App\Rules\ForbiddenFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class SubmissionController extends Controller
{
    public function store(Request $request, Classwork $classwork)
    {

        Gate::authorize('submissions.create');

        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', new ForbiddenFile('application/x-msdownload','text/html')],
        ]);

        $assigned = $classwork->users()->where('id', '=', Auth::id())->exists();
        if(!$assigned){
            abort(403);
        }

        DB::beginTransaction();
        try{
            $data = [];
            foreach($request->file('files') as $file){
                $data = [
                        'user_id' => Auth::id(),
                        'classwork_id' => $classwork->id,
                        'content' => $file->store("submissions/{$classwork->id}"),
                        'type' => 'file',
                        'created_at' => now(),
                        'updated_at' => now(),
                ];
            }
            Submission::insert($data);

            ClassworkUser::where([
                'user_id' => Auth::id(),
                'classwork_id' => $classwork->id,
            ])->update([
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);
            DB::commit();
        }catch(Throwable $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Work submitted');
    }

    public function file(Submission $submission)
    {
        $user = Auth::user();
        // check user has access to this submission.
        $isTeacher = $submission->classwork->classroom->teachers()
            ->where('id', $user->id)->exists();

        $isOwner = $submission->user_id == $user->id;

        if(!$isTeacher && !$isOwner){
            abort(403);
        }

        return response()->file(storage_path('app/'. $submission->content));
    }
}
