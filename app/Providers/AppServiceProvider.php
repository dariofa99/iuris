<?php

namespace App\Providers;

use App\Repositories\AsignacionCasosRepository;
use App\Repositories\AsignacionDocenteCasosRepository;
use App\Repositories\AutorizacionesRepository;
use App\Repositories\BaseRepository;
use App\Repositories\BibliotecasRepository;
use App\Repositories\ConcEncuSatisfaccionRepository;
use App\Repositories\ConciliacionComentariosRepository;
use App\Repositories\ConciliacionesRepository;
use App\Repositories\EstadosCasoRepository;
use App\Repositories\ExpedientesRepository;
use App\Repositories\ExpEncuSatisfaccionRepository;
use App\Repositories\LoginRepository;
use App\Repositories\PausasRepository;
use App\Repositories\PeriodosRepository;
use App\Repositories\ProcesoJudicialExpRepository;
use App\Repositories\ReferencesDataRepository;
use App\Repositories\ReferenciasRepository;
use App\Repositories\RequerimientosRepository;
use App\Repositories\SedesRepository;
use App\Repositories\SegmentosRepository;
use App\Repositories\SolicitudesRepository;
use App\Repositories\TurnosRepository;
use App\Repositories\UsersRepository;
use App\Repositories\VacacionesRepository;
use App\Services\AsignacionCasosService;
use App\Services\AsignacionDocenteCasosService;
use App\Services\AutorizacionesService;
use App\Services\BibliotecasService;
use App\Services\ConcEncuSatisfaccionService;
use App\Services\ConciliacionComentarioService;
use App\Services\ConciliacionesService;
use App\Services\EstadosCasoService;
use App\Services\ExpedientesService;
use App\Services\ExpEncuSatisfaccionService;
use App\Services\LoginService;
use App\Services\PausasService;
use App\Services\PeriodosService;
use App\Services\ProcesoJudicialExpService;
use App\Services\ReferencesDataService;
use App\Services\ReferenciasService;
use App\Services\RequerimientosService;
use App\Services\SedesService;
use App\Services\SegmentosService;
use App\Services\SolicitudesService;
use App\Services\TurnosService;
use App\Services\UsersService;
use App\Services\VacacionesService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;

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
        Session::forget('sede');
        Schema::defaultStringLength(191);
     /*     $this->app->bind('nota',function(){
            return new \App\NotaExt();
        });  */
   /*      $this->app->singleton('GuzzleHttp\Client',function(){
            return new Client(
                ['base_uri'=>'http://judex.udenar.edu.co/']
            );
        });  */
        $this->app->bind(   
            BaseRepository::class
        );
        $this->app->bind(            
            ReferencesDataService::class,
            ReferencesDataRepository::class,       
        );
        $this->app->bind(            
            UsersService::class,
            UsersRepository::class,       
        );
        $this->app->bind(            
            ExpEncuSatisfaccionService::class,
            ExpEncuSatisfaccionRepository::class,       
        );
        $this->app->bind(            
            LoginService::class,
            LoginRepository::class,       
        );
        $this->app->bind(            
            ConciliacionComentarioService::class,
            ConciliacionComentariosRepository::class,       
        );
        $this->app->bind(            
            TurnosService::class,
            TurnosRepository::class,       
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
            BibliotecasService::class,
            BibliotecasRepository::class,       
        );
        $this->app->bind(            
            ReferenciasService::class,
            ReferenciasRepository::class,       
        );
        $this->app->bind(            
            SolicitudesService::class,
            SolicitudesRepository::class,       
        );
        $this->app->bind(            
            PausasService::class,
            PausasRepository::class,       
        );
        $this->app->bind(            
            SedesService::class,
            SedesRepository::class,       
        );
        $this->app->bind(            
            VacacionesService::class,
            VacacionesRepository::class,       
        );
        $this->app->bind(            
            ExpedientesService::class,
            ExpedientesRepository::class,       
        );
        $this->app->bind(            
            AsignacionCasosService::class,
            AsignacionCasosRepository::class,       
        );
        $this->app->bind(            
            AsignacionDocenteCasosService::class,
            AsignacionDocenteCasosRepository::class,       
        );
        $this->app->bind(            
            AutorizacionesService::class,
            AutorizacionesRepository::class,       
        );
        $this->app->bind(            
            ProcesoJudicialExpService::class,
            ProcesoJudicialExpRepository::class,       
        );
        $this->app->bind(            
            RequerimientosService::class,
            RequerimientosRepository::class,       
        );
        $this->app->bind(
            PeriodosService::class,
            PeriodosRepository::class,
            BaseRepository::class
        );

        $this->app->bind(
            ConcEncuSatisfaccionService::class,
            ConcEncuSatisfaccionRepository::class,
            
        );

    }
}
