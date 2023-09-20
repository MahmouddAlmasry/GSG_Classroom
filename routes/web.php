<?php

use App\Http\Controllers\ClassroomPeopleController;
use App\Http\Controllers\ClassroomsController;
use App\Http\Controllers\ClassworkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\JoinClassroomController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\TopicsController;
use App\Http\Middleware\ApplyUserpreferences;
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

        Route::get('/classrooms/{classroom}/people', [ClassroomPeopleController::class, 'index'])
            ->name('classrooms.people');

        Route::delete('/classrooms/{classroom}/people', [ClassroomPeopleController::class, 'destroy'])
            ->name('classrooms.people.destroy');

        Route::post('comments/stroe', [CommentController::class, 'store'])
            ->name('comments.store');

        Route::post('classworks/{classwork}/submissions', [SubmissionController::class, 'store'])
            ->name('submissions.store')
            ->middleware('can:create, App/Models/Classwork');

        Route::get('submissions/{submission}/file', [SubmissionController::class, 'file'])
            ->name('submissions.file');

        Route::post('subscriptions', [SubscriptionsController::class, 'store'])
            ->name('subscriptions.store');

        Route::post('payments', [PaymentsController::class, 'store'])
            ->name('payments.store');

        Route::get('payments/{subscription}/success', [PaymentsController::class, 'success'])
            ->name('payments.success');

        Route::get('payments/{subscription}/cancel', [PaymentsController::class, 'cancel'])
            ->name('payments.cancel');

        Route::get('subscriptions/{subscription}/checkout', [PaymentsController::class, 'create'])
            ->name('checkout');

});

Route::get('plans', [PlanController::class, 'index'])->name('plans');

// Route::post('/payments/stripe/webhook', StripeController::class);

// require __DIR__.'/auth.php';
