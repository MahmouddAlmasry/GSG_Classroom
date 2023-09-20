<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\ClassroomModel;
use App\Models\Classwork;
use App\Models\User;
use App\Policies\ClassroomPolicy;
use App\Policies\ClassworkPolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Classwork::class => ClassworkPolicy::class,
        ClassroomModel::class => ClassroomPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {

        // Gate::before(function(User $user, $abilty){
            // if($user->super_admin){
                // return true;
            // }
        // });

        //Define Gates
     /*   Gate::define('classworks.view', function (User $user, Classwork $classwork) {
            $teacher = $user->classrooms()
            ->wherePivot('classroom_id', '=', $classwork->classroom_id)
            ->wherePivot('role', '=', 'teacher')->exists();

            $assigned = $user->classworks()
                ->wherePivot('classwork_id', '=', $classwork->id)->exists();

            return ($teacher || $assigned);
        });*/
        Gate::define('classworks.create', function (User $user, ClassroomModel $classroom) {
            $result = $user->classrooms()
            ->wherePivot('classroom_id', '=', $classroom->id)
            ->wherePivot('role', '=', 'teacher')->exists();

            return $result
                ? Response::allow()
                : Response::deny('You are not a teacher in this classroom.');
        });
       /* Gate::define('classworks.update', function (User $user, Classwork $classwork) {
            return $classwork->user_id == $user->id && $user->classrooms()
            ->wherePivot('classroom_id', '=', $classwork->classroom_id)
            ->wherePivot('role', '=', 'teacher')->exists();
        });
        Gate::define('classworks.delete', function (User $user, Classwork $classwork) {
            return $classwork->user_id == $user->id && $user->classrooms()
            ->wherePivot('classroom_id', '=', $classwork->classroom_id)
            ->wherePivot('role', '=', 'teacher')->exists();
        });

        Gate::define('submissions.create', function (User $user, Classwork $classwork) {
            $teacher = $user->classrooms()
            ->wherePivot('classroom_id', '=', $classwork->classroom_id)
            ->wherePivot('role', '=', 'teacher')->exists();

            if($teacher){
                return false;
            }

            return $user->classworks()
                ->wherePivot('classwork_id', '=', $classwork->id)->exists();

        });*/
    }
}
