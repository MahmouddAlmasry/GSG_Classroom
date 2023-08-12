<?php

use App\Http\Controllers\ClassroomsController;
use App\Http\Controllers\ClassworkController;
use App\Http\Controllers\JoinClassroomController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopicsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function(){

    Route::prefix('classrooms/trashed')
        ->as('classrooms.')
        ->controller(ClassroomsController::class)
        ->group(function(){
            Route::get('/', 'trashed')->name('trashed');
            Route::put('/{id}', 'restore')->name('restore');
            Route::delete('/{id}', 'forceDelete')->name('force-delete');

        });

    Route::get('/classrooms/{classroom}/join', [JoinClassroomController::class, 'create'])
        ->name('classrooms.join')
        ->middleware('signed');

    Route::post('/classrooms/{classroom}/join', [JoinClassroomController::class, 'store']);

    // Route::resource('/classrooms', ClassroomsController::class)
    //     ->where([
    //     //shutdown it when you use model binig
    //     // 'classroom' => '\d+',
    //     ]);

        Route::resources([
            'topics' => TopicsController::class,
            'classrooms' => ClassroomsController::class,
        ]);

        Route::resource('/classrooms.classworks', ClassworkController::class);

});


require __DIR__.'/auth.php';
