<div class="row" >
 <div class="col-md-2">
    @if(((currentUser()->hasRole('diradmin') || currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai')))
    || ((currentUserInConciliacion($conciliacion->id,['autor','conciliador','asistente']))))     
        <input type="button" data-section="general" value="Nueva asignación" class="btn btn-primary btn-block btn-sm btn_asinar_usuario_gen_conciliacion" id="btn_asinar_usuario">
    @endif
    </div> 
</div>

<div class="row">
    <div class="col-md-12 table-responsive no-padding">
        <table class="table" id="table_list_user_asig">
            <thead>
                 
                <th>
                    Nombres
                </th>
                <th>
                    Correo
                </th>
                <th>
                   Tipo
                </th>
                
                <th>
                    Fecha asignación
                </th>
              {{--   <th>
                    Acciones
                </th> --}}
            </thead>
            <tbody>
               @include('myforms.conciliaciones.componentes.solicitud_user_asig_ajax')
            </tbody>
        </table>
    </div>
</div>