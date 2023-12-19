 <!-- Left side column. contains the sidebar -->
 <aside class="main-sidebar" >
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img class='img-circle' src="{{ is_file(public_path('thumbnails/'.currentUser()->image)) ? asset('thumbnails/'.currentUser()->image ) : asset('thumbnails/default.jpg' )}}" alt="User">
           
        </div>

        <div class="pull-left info">
        
            @if(currentUser()->turno)
            <span  class="badge {{ (currentUser()->getColorTurno(currentUser()->turno->color->ref_value)) }}">.</span>
           @else
         {{--   <span title="En linea"  class="badge bg-green">.</span> <br>
         --}}
            @endif
            <p>
             <small> {{  currentUser()->name  }} <br> {{  currentUser()->lastname  }} </small>
            </p> 
          {{-- <a href="#"><label class="label ">Online</label> </a> --}}
        </div>
        
      </div>
      <!-- search form -->
    {{--   <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                  <i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> --}}
      @if(session('sede') and count($sedes)>=2)
          @if(auth()->user()->can('cambiar_sede'))
          <div class="input-group dropdown" style="width:100%">
            <button style="width:95%; margin:3px; text-align:left; background-color:#AEB6BF" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
              Sede: {{session('sede')->nombre}}
              <span class="caret float-right"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
              @foreach($sedes as $id => $sede)
                <li class="{{ session('sede')->id == $id  ? 'disabled' : ''}}" >
                  <a href="#" class="{{ session('sede')->id != $id  ? 'btn_cambiar_sede' : ''}}" data-id="{{$id}}">{{$sede}}</a>
                </li>
              @endforeach          
            </ul>
          </div>
          <form id="myFormCambiarSede" action="{{url('/change/sedes')}}" method="GET">
            <input type="hidden" name="sede_id">       
        </form>
        @else
        <button style="width:95%; margin:3px; text-align:left; background-color:#AEB6BF" class="btn btn-default" type="button">
          Sede: {{session('sede')->nombre}}     
        </button>
        @endif
      @endif
   
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      @if(session('sede'))
      <ul class="sidebar-menu">
        <li class="header">MENÚ DE NAVEGACIÓN</li>
            @if(currentUser()->can('ver_usuarios'))
            @if ( isset($active_users))
              <li class="treeview {{ $active_users }}">
            @else
               <li class="treeview">
            @endif         
            <a href="#">
              <i class="fa fa-users"></i> <span>Usuarios</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @if(currentUser()->can('crear_usuarios'))
              <li>{!! link_to_route('users.create', 'Crear Nuevo')!!}</li>          
              @endif              
              <li>{!! link_to_route('users.index', 'Listar Usuarios')!!}</li>
            </ul>
        </li>
        @endif

{{-----------------------------------menu solicitudes--------------------------------------}}
@if(currentUser()->can("ver_solicitudes"))
        <li class="treeview">        
                    <a href="#">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                      <span>Solicitudes</span>  
                      <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
              <ul class="treeview-menu">       
                <li><a href="{{url('/solicitudes')}}"> Ver solicitudes </a></li>           
              </ul>
        </li>
        @endif

{{-----------------------------------menu expedientes----------------------------------------}}

       
            @if(currentUser()->can("ver_expedientes"))
               <li class="treeview">
    
        
          <a href="#">
            <i class="fa fa-folder-open"></i> <span>
              @if(currentUser()->hasRole("solicitante"))
                Casos 
              @else 
                Expedientes
              @endif</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             
             
             @if(currentUser()->can("crear_expediente") )
             <li>{!! link_to_route('expedientes.create', 'Nuevo Expediente')!!}</li>
             @endif

               
            @if(currentUser()->can("crear_defensas_oficio"))
             <li>{!! link_to_route('oficio.create', 'Nueva Defensa de Oficio')!!}</li>
            @endif


            @if(currentUser()->hasRole("solicitante"))
                <li>{!! link_to_route('expedientes.index', 'Mis casos')!!}</li> 
              @else 
                <li>{!! link_to_route('expedientes.index', 'Listar Expedientes')!!}</li> 
              @endif
             
            
             {{-- @if(currentUser()->hasRole("docente") || currentUser()->hasRole("amatai"))
                    <li>{!! link_to_route('asignaciones.index', 'Asignaciones ')!!}</li>   
                  @endif
             --}}
             @if(currentUser()->hasRole("coordprac") || currentUser()->hasRole("diradmin") || currentUser()->hasRole("dirgral") || currentUser()->hasRole("amatai") || currentUser()->hasRole("secretaria")) 
              <li><a href="/requerimientos">Requerimientos</a></li>
             @endif

            @if(currentUser()->hasRole("diradmin") || currentUser()->hasRole("dirgral") || currentUser()->hasRole("amatai")) 
             <li><a href="/autorizaciones">Autorizaciones</a></li>
            @endif
            
            @if(currentUser()->hasRole("diradmin") || currentUser()->hasRole("dirgral") || currentUser()->hasRole("amatai") || currentUser()->hasRole("coordprac"))
             <li><a href="/expediente/replacecaso">Sustituciones</a></li>
            @endif

            @if(currentUser()->hasRole("diradmin") || currentUser()->hasRole("amatai") || currentUser()->hasRole("docente") || currentUser()->hasRole("coordprac") || currentUser()->hasRole("dirgral"))
             <li><a href="/expediente/casos/reasignados">Reasignados</a></li>
            @endif
    
             @if(currentUser()->hasRole("amatai"))
              <li><a href="/expediactuacion">Actuaciones Pendientes</a></li>
             @endif

             @if(currentUser()->hasRole("amatai"))
             <li><a href="/expediactuacion">Actuaciones Pendientes</a></li>
            @endif
             @if(currentUser()->hasRole("dirgral") || currentUser()->hasRole("amatai") || currentUser()->hasRole("diradmin") || currentUser()->hasRole("estudiante"))
             <li><a href="/notas/ver/estudiante">Ver Notas</a></li>
              @endif
            
          </ul>
        </li>
@endif
        {{-----------------------------------menu asiganciones----------------------------------------}}

            @if ( isset($active_asig))
              <li class="treeview {{ $active_asig }}">
            @else
               <li class="treeview">
            @endif 
        @if(currentUser()->hasRole("diradmin") || currentUser()->hasRole("amatai") || currentUser()->hasRole("docente") || currentUser()->hasRole("dirgral"))
         {{--  <a href="#">
            <i class="fa  fa-calendar-check-o"></i> <span>Asignaciones</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a> --}}
	@endif
         {{--  <ul class="treeview-menu">
             
    
             @if(currentUser()->hasRole("docente") || currentUser()->hasRole("dirgral") || currentUser()->hasRole("amatai") || currentUser()->hasRole("diradmin"))
              <li>{!! link_to_route('asignaciones.index', 'Estudiantes')!!}</li>   
             @endif
            
            @if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin'))
              <li>{!! link_to_route('horario.index', 'Docentes')!!}</li>
             @endif

    
             

           
          </ul> --}}
        </li>

        

{{-----------------------------------menu oficinas--------------------------------------}}
          @if(currentUser()->can("asignar_oficina") || count(auth()->user()->oficinas)>0)
        
              <li class="treeview {{active('oficinas/ver/*')}}">
           
        
          <a href="#">
            <i class="fa fa-building"></i> <span>Oficinas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @foreach (auth()->user()->oficinas as $oficina )
                <li><a href="/oficinas/ver/{{$oficina->id}}">{{$oficina->nombre}}</a></li>
            @endforeach

          </ul>
        </li>
          @endif
        <!--Menu reportes-->
     
<!---->
 {{-----------------------------------menu conciliaciones----------------------------------------}}
@if(currentUser()->can("ver_conciliaciones"))           
          <li class="treeview">        
                    <a href="#">
                    <i class="fa fa-hand-paper-o" aria-hidden="true"></i>
                      <span>Conciliaciones</span>  
                      <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
              <ul class="treeview-menu">       
                <li><a href="/conciliaciones"> Ver conciliaciones </a></li>   
                <li><a href="/audiencias"> Agenda audiencias </a></li>  
          @if(((currentUser()->can('adm_formatos_conciliaciones'))))
            <li><a href="{{route('reportes.create')}}"> Administrar actas y correos </a></li>        
           @endif
              </ul>
        </li>
@endif
{{----------------------------------- fin menu conciliaicones----------------------------------------}}
    {{-- @if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin'))
              <li>{!! link_to_route('horario.index', 'Docentes')!!}</li>
             @endif --}}
{{-----------------------------------menu calendario--------------------------------------}}
          @if(currentUser()->can("ver_horarios"))
         @if ( isset($active_calendar))
              <li class="treeview {{ $active_calendar }}">
            @else
               <li class="treeview">
            @endif 
        
          <a href="#">
            <i class="fa fa-calendar"></i> <span>Horarios</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

             <li>{!! link_to_route('horarios.index', 'Calendario')!!}</li>

             @if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole("dirgral") || currentUser()->hasRole("coordprac"))
              <li>{!! link_to_route('turnos.index', 'Turnos estudiantes')!!}</li>
             @endif
            @if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole("dirgral") || currentUser()->hasRole("coordprac"))
              <li><a href="/turnos/docentes"> Turnos docentes </a></li>
             @endif

          </ul>
        </li>
          @endif
        <!-- -----------------------------------------------------------------Menu reportes-->
        @if(currentUser()->can("ver_reportes"))
       

            
              <li class="treeview {{(Request::is('excel') ? 'active' : '')}}">
           
        
          <a href="#">
            <i class="fa fa-calculator"></i> <span>Reportes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a> 
          <ul class="treeview-menu">
            <li>{!! link_to_route('notas.index', 'Notas')!!}</li>  

             
             <li>{!! link_to_route('graficas.index', 'Gráficos')!!}</li>
             

             <li>{!! link_to_route('excel.index', 'Excel')!!}</li>

            
            
           
          </ul>
        </li>
        @endif
<!---->


{{-----------------------------------menu configuración--------------------------------------}}


@if(currentUser()->can("ver_configuracion"))
            @if ( isset($active_config))
              <li class="treeview {{ $active_config }}">
            @else
               <li class="treeview">
            @endif 
        
          <a href="#">
            <i class="fa fa-gears"></i> <span>Configuración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if(currentUser()->hasRole("amatai"))
                  
            <li>{!! link_to_route('users.index', 'Usuarios')!!}</li>  
            <li>{!! link_to_route('users.index', 'Parametros Generales')!!}</li> 

            @endif
            @permission("ver_administracion")
            <li>{!! link_to_route('roles.admin', 'Roles y permisos')!!}</li>  
            @endpermission

              <li>{!! link_to_route('oficinas.index', 'Oficinas')!!}</li>
              <li>{!! link_to_route('sedes.index', 'Sedes')!!}</li> 
              <li>{!! link_to_route('segmentos.index', 'Cortes')!!}</li>
              <li>{!! link_to_route('periodos.index', 'Periodos')!!}</li>
              <li>{!! link_to_route('auditoria.index', 'Auditoria')!!}</li>

             <li>{!! link_to_route('students.index', 'Gestión de Cursos/Estudiantes')!!}</li> 
             <li>{!! link_to_route('categorias.index', 'Categorías')!!}</li> 
             <li>{!! link_to_route('categories.index', 'Categorías Estáticas')!!}</li> 
            
           
           
          </ul>
        </li>
@endif

{{-----------------------------------menu galeria--------------------------------------}}
@if(currentUser()->can("ver_biblioteca"))

            @if ( isset($active_galeria))
              <li class="treeview {{ $active_galeria }}">
            @else
               <li class="treeview">
            @endif 
        
          <a href="#">
            <i class="fa fa-file-word-o"></i> <span>Biblioteca</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             

             
             <li><a href="/bibliotecas"> Ver Biblioteca </a></li>
             @if(!currentUser()->hasRole("estudiante"))
             <li><a href="/bibliotecas/create"> Crear Biblioteca </a></li>
             @endif
             @if(currentUser()->hasRole("amatai"))
            <li>
            {!! link_to_route('biblioteca.inactivas', $title = 'Ver Inactivas') !!}
            </li>
            @endif
 
          
             
           
           
          </ul>
        </li>
        @endif
 @if(currentUser()->docente_asignado and currentUser()->docente_asignado->confirmado)
<li class="header">Docente Asignado:
<br>

<i> 
  
 
  {{currentUser()->docente_asignado->docente->name}} {{currentUser()->docente_asignado->docente->lastname}}

</i>

</li> 
@endif
  </ul>
  @endif
    </section>
    <!-- /.sidebar -->
  </aside>
