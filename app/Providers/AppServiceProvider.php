<?php

namespace App\Providers;

use App\Repositories\BaseRepository;
use App\Repositories\ConciliacionesRepository;
use App\Repositories\EstadosCasoRepository;
use App\Repositories\PeriodosRepository;
use App\Repositories\SedesRepository;
use App\Repositories\SegmentosRepository;
use App\Repositories\SolicitudesRepository;
use App\Repositories\UsersRepository;
use App\Services\ConciliacionesService;
use App\Services\EstadosCasoService;
use App\Services\PeriodosService;
use App\Services\SedesService;
use App\Services\SegmentosService;
use App\Services\SolicitudesService;
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
        );
        $this->app->bind(            
            EstadosCasoService::class,
            EstadosCasoRepository::class,       
        );
        $this->app->bind(            
            SegmentosService::class,
            SegmentosRepository::class,       
        );
        $this->app->bind(            
            ConciliacionesService::class,
            ConciliacionesRepository::class,       
        );
        $this->app->bind(            
            SolicitudesService::class,
            SolicitudesRepository::class,       
        );
        $this->app->bind(            
            SedesService::class,
            SedesRepository::class,       
        );
        $this->app->bind(
            PeriodosService::class,
            PeriodosRepository::class,
            BaseRepository::class
        );
    }
}
