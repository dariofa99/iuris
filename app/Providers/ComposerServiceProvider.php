<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services. 
     *
     * @return void
     */
    public function boot()
    {
        View::composer([
            'galeria.create',
            'galeria.index',
            'galeria.show_biblioteca_inactiva'
        ], 'App\Http\ViewComposers\BibliotecasComposer');

        View::composer([
            'myforms.conciliaciones.*',
        ], 'App\Http\ViewComposers\ConciliacionesComposer');

        View::composer([
            'myforms.summernote_reportes.*',
        ], 'App\Http\ViewComposers\SummernoteReportesComposer');


        View::composer([
            'myforms.notas_ver.*',
        ], 'App\Http\ViewComposers\NotasComposer');

        View::composer([
            'myforms.frm_expediente_create',
            'myforms.frm_expediente_edit',
            'myforms.frm_expediente_show',
            'myforms.frm_expediente_list',
            'myforms.frm_requerimiento_list',
            'myforms.solicitudes.*',
            'myforms.conciliaciones.*',
            'myforms.components_user.*',
            'myforms.components_exp.*'
        ], 'App\Http\ViewComposers\ExpedientesComposer');

        View::composer([
            'myforms.frm_defensa_oficio_create',
            'myforms.frm_defensa_oficio_edit',
            'myforms.frm_defensa_oficio_show',
        ], 'App\Http\ViewComposers\DefensasOficioComposer');


        View::composer([
            'myforms.frm_myusers_gen_form',
            'myforms.frm_myusers_edit',
            'myforms.frm_myusers',
            'myforms.frm_myusers_list',
            'myforms.frm_mystudents_list',
            'myforms.frm_oficinas_list',
            'myforms.recepcion.*',
            'myforms.conciliaciones.*',
            'front.solicitudes.*',
            'myforms.register',
            'myforms.components_user.*',
            'myforms.users.*',
            'myforms.frm_expediente_create',

        ], 'App\Http\ViewComposers\UsersComposer');

        View::composer([
            'myforms.frm_oficinas_ver',
        ], 'App\Http\ViewComposers\OficinasComposer');

        View::composer([
            'myforms.solicitudes.*',
            'front.solicitudes.*',
        ], 'App\Http\ViewComposers\SolicitudesComposer');

        View::composer([
            'myforms.categorias.*',
            'myforms.static_categories.*',
        ], 'App\Http\ViewComposers\CategoriasComposer');

        View::composer([
            'myforms.frm_expediente_edit',
            'myforms.frm_defensa_oficio_edit',
            'myforms.frm_expediente_show',
            'myforms.incidencias.*',
            'myforms.frm_defensa_oficio_show',
        ], 'App\Http\ViewComposers\IncidenciasComposer');

        View::composer([
            'layouts.*',
        ], 'App\Http\ViewComposers\SidebarComposer');

        View::composer([
            'auth.recovery',
            'auth.reset_account',
        ], 'App\Http\ViewComposers\RecoveryAccountComposer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {}
}
