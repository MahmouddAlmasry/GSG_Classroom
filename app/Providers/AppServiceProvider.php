<?php

namespace App\Providers;

use App\Models\Classwork;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        ResourceCollection::withoutWrapping();

        // Paginator::useBootstrapFive();

        // Relation::enforceMorphMap([
        //     'classwork' => Classwork::class,
        //     'post' => Post::class,
        //     'user' => User::class,
        // ]);
    }
}
