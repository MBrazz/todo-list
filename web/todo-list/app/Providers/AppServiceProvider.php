<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        /*** Repositories injection  */
        $this->app->bind('App\Repositories\Exemplo\Interfaces\ExemploInterface','App\Repositories\Exemplo\ExemploRepository');
        $this->app->bind('App\Repositories\User\Interfaces\UserInterface','App\Repositories\User\UserRepository');
        $this->app->bind('App\Repositories\Task\Interfaces\TaskInterface','App\Repositories\Task\TaskRepository');
        $this->app->bind('App\Repositories\Comment\Interfaces\CommentInterface', 'App\Repositories\Comment\CommentRepository');
        $this->app->bind('App\Repositories\User\Interfaces\PasswordResetInterface','App\Repositories\User\PasswordResetRepository');



        /*** Services injection */
        $this->app->bind('App\Services\Exemplo\Interfaces\ExemploInterface','App\Services\Exemplo\ExemploService');
        $this->app->bind('App\Services\Task\Interfaces\TaskInterface','App\Services\Task\TaskService');
        $this->app->bind('App\Services\Comment\Interfaces\CommentInterface', 'App\Services\Comment\CommentService');
        $this->app->bind('App\Services\User\Interfaces\UserInterface','App\Services\User\UserService');

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {}
}
