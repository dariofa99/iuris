<?php

namespace App\Providers;

use App\Repositories\BaseRepository;
use App\Repositories\UsersRepository;
use App\Services\UsersService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Carbon\Carbon::setlocale('es');
        Schema::defaultStringLength(191);
         $this->app->bind('nota',function(){
            return new \App\NotaExt();
        }); 
        $this->app->bind(
            UsersService::class,
            UsersRepository::class,
            BaseRepository::class
        );
    }
}
