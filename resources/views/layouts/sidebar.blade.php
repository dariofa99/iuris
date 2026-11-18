<!-- Main Sidebar Container -->
<aside
    class="main-sidebar iuris-sidebar {{ config('app.name') == 'ConciliApp' ? 'iuris-sidebar--conciliapp' : '' }} sidebar-{{ $sidebar_modo }}-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/home" class="brand-link iuris-sidebar__brand">
        @if (config('app.name') == 'ConciliApp')
            <img src="{{ asset('dist/img/conciliapplogohor.png') }}" alt="ConciliApp"
                class="iuris-sidebar__brand-logo iuris-sidebar__brand-logo--conciliapp">
        @else
            <img src="{{ asset('dist/img/consultorios-min.png') }}" alt="IURIS" class="iuris-sidebar__brand-logo">
            <span class="brand-text font-weight-{{ $sidebar_brand_modo }} iuris-sidebar__brand-name">IURIS</span>
        @endif
    </a>


    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel iuris-sidebar__user d-flex">
            <div class="image">
                <a href="/users/{{ auth()->user()->id }}/edit" title="Ingresar a perfil">
                    <img src="{{ is_file(public_path('thumbnails/' . currentUser()->image)) ? asset('thumbnails/' . currentUser()->image) : asset('thumbnails/default.jpg') }}"
                        id="image_profile_user_sidebar" class="img-circle elevation-2 image_profile" alt="User Image">
                </a>
            </div>
            <div class="info iuris-sidebar__user-info">
                <a href="/users/{{ auth()->user()->id }}/edit" id="name_profile_user_sidebar"
                    class="iuris-sidebar__profile" title="Ingresar a perfil">
                    <span class="iuris-sidebar__user-name">{{ Auth::user()->name }}</span>
                    <span class="iuris-sidebar__profile-label">Ver perfil</span>
                </a>

                @if (currentUser()->turno)
                    <span title="Color del turno"
                        class="badge iuris-sidebar__shift-badge {{ currentUser()->getColorTurno(currentUser()->turno->color->ref_value) }}">.</span>
                @endif
                @if (currentUser()->hasRole('estudiante') and currentUser()->turno != null)
                    <span class="iuris-sidebar__schedule">
                        <i class="far fa-clock" aria-hidden="true"></i>
                        {{ currentUser()->turno->horario->ref_nombre }}
                    </span>
                @endif
                <a href="{{ route('logout.index') }}" class="iuris-sidebar__logout">
                    <i class="fas fa-sign-out-alt"></i>
                    {{ __('Salir') }}
                </a>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

        <!-- Sidebar Menu -->
        @if (!isset($ocultar_menu))
            <nav class="mt-2 iuris-sidebar__nav">
                <ul class="nav nav-pills nav-sidebar flex-column iuris-sidebar__menu" data-widget="treeview"
                    role="menu" data-accordion="false">
                    <li
                        class="nav-item has-treeview {{ (!Route::is('expedientes.index') and !Route::is('expedientes.create') and !Route::is('expedientes.edit')) ?:
                            'menu-open' }}">
                        @if (config('app.name') != 'ConciliApp' and
                                !currentUser()->hasRole('visitante_conciliacion') and
                                !currentUser()->hasRole('solicitante'))
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-folder"></i>
                                <p>
                                    Expedientes
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                        @endif
                        <ul class="nav nav-treeview">

                            <li class="nav-item ml-3">
                                <a href="{{ route('expedientes.index') }}" class="nav-link">
                                    {{--  <i class="fas fa-clipboard nav-icon"></i> --}}
                                    <p>Ver expedientes</p>
                                </a>
                            </li>

                            @if (currentUser()->can('crear_expediente'))
                                <li class="nav-item ml-3">
                                    <a href="{{ route('expedientes.create') }}" class="nav-link">
                                        {{--  <i class="fa fa-pen-square nav-icon"></i> --}}
                                        <p>Nuevo Expediente</p>
                                    </a>
                                </li>
                            @endif
                            @if (currentUser()->can('crear_defensas_oficio'))
                                <li class="nav-item ml-3">
                                    <a href="{{ route('oficio.create') }}" class="nav-link">
                                        {{-- <i class="fa fa-pen-square nav-icon"></i> --}}
                                        <p>Nueva defensa de oficio</p>
                                    </a>
                                </li>
                            @endif
                            @if (currentUser()->hasRole('monitor_consultorios') ||
                                    currentUser()->hasRole('coordprac') ||
                                    currentUser()->hasRole('diradmin') ||
                                    currentUser()->hasRole('dirgral') ||
                                    currentUser()->hasRole('amatai') ||
                                    currentUser()->hasRole('secretaria'))
                                <li class="nav-item ml-3">
                                    <a href="/requerimientos" class="nav-link">
                                        <p>Requerimientos</p>
                                    </a>
                                </li>
                            @endif

                            @if (currentUser()->hasRole('coordprac') ||
                                    currentUser()->hasRole('diradmin') ||
                                    currentUser()->hasRole('dirgral') ||
                                    currentUser()->hasRole('amatai'))
                                <li class="nav-item ml-3">
                                    <a href="{{ route('expencuesta.index') }}" class="nav-link">
                                        <p>Administrar encuestas</p>
                                    </a>
                                </li>

                                {{-- <li class="nav-item ml-3">
                                    <a href="{{ url('estudiantes/casos/olvidados') }}" class="nav-link">

                                        <p> Ver casos olvidados</p>
                                    
                                    </a>
                                </li> --}}
                            @endif

                            @if (currentUser()->can('ver_casos_olvidados'))
                                <li class="nav-item ml-3">
                                    <a href="{{ url('estudiantes/casos/olvidados') }}" class="nav-link">

                                        <p> Ver casos olvidados</p>

                                    </a>
                                </li>
                            @endif


                            @if (currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('amatai'))
                                <li class="nav-item ml-3">
                                    <a href="/autorizaciones" class="nav-link">
                                        <p>Autorizaciones</p>
                                    </a>
                                </li>
                            @endif

                            @if (currentUser()->hasRole('estudiante') ||
                                    currentUser()->hasRole('diradmin') ||
                                    currentUser()->hasRole('dirgral') ||
                                    currentUser()->hasRole('amatai'))
                                <li class="nav-item ml-3">
                                    <a href="/notas/ver/estudiante" class="nav-link">
                                        Ver Notas
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    @if (currentUser()->hasRole('docente') or
                            currentUser()->hasRole('amatai') or
                            currentUser()->hasRole('estudiante') or
                            currentUser()->hasRole('dirgral') or
                            currentUser()->hasRole('diradmin'))
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-calendar"></i>
                                <p>
                                    Agenda
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @if (currentUser()->hasRole('docente') or
                                        currentUser()->hasRole('amatai') or
                                        currentUser()->hasRole('dirgral') or
                                        currentUser()->hasRole('diradmin'))
                                    <li class="nav-item ml-3">
                                        <a href="{{ route('ag.cedoc') }}" class="nav-link">
                                            <p>Citaciones a estudiantes</p>
                                        </a>
                                    </li>
                                @endif
                                @if (currentUser()->hasRole('docente') or
                                        currentUser()->hasRole('amatai') or
                                        currentUser()->hasRole('estudiante') or
                                        currentUser()->hasRole('dirgral') or
                                        currentUser()->hasRole('diradmin'))
                                    <li class="nav-item ml-3">
                                        <a href="{{ route('ag.cestu') }}" class="nav-link">
                                            @if (currentUser()->hasRole('estudiante') or currentUser()->hasRole('amatai'))
                                                <p>Turnos docentes</p>
                                            @else
                                                <p>Turnos estudiantes</p>
                                            @endif
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (currentUser()->can('ver_conciliaciones') ||
                            currentUser()->hasRole('amatai') ||
                            currentUser()->hasRole('solicitante') ||
                            count(currentUser()->conciliaciones) > 0)
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-handshake"></i>
                                <p>Conciliaciones
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ml-3">
                                    <a href="{{ url('/conciliaciones') }}" class="nav-link">

                                        <p> {{ currentUser()->hasRole('solicitante') ? 'Ver/Crear conciliaciones' : 'Listar conciliaciones' }}
                                        </p>
                                    </a>
                                </li>


                                @if (currentUser()->can('admin_actasandmail'))
                                    <li class="nav-item ml-3">
                                        <a href="{{ route('reportes.create') }}" class="nav-link">
                                            <p> Administrar actas y correos</p>
                                        </a>
                                    </li>
                                @endif


                                @if (currentUser()->can('admin_actasandmail'))
                                    <li class="nav-item ml-3">
                                        <a href="{{ route('concpers.index') }}" class="nav-link">
                                            <p> Admin. Personas externas</p>
                                        </a>
                                    </li>
                                @endif

                                @if (currentUser()->can('admin_actasandmail'))
                                    <li class="nav-item ml-3">
                                        <a href="{{ route('audiencias.agenda') }}" class="nav-link">
                                            <p>Agenda de conciliaciones</p>
                                        </a>
                                    </li>
                                    <li class="nav-item ml-3">
                                        <a href="{{ route('cencuesta.index') }}" class="nav-link">
                                            <p>Ver resultados de encuestas</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if (currentUser()->hasRole('amatai') and currentUser()->can('ver_horarios') and config('app.name') != 'ConciliApp')
                        <li class="nav-item has-treeview {{ !Route::is('horarios.index') ?: 'menu-open' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon far fa-calendar-alt"></i>
                                <p>
                                    Solicitudes
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ml-3">
                                    <a href="{{ route('solicitudes.index') }}" class="nav-link">
                                        <p>Expedientes</p>
                                    </a>
                                </li>

                                <li class="nav-item ml-3">
                                    <a href="{{ route('horarios.index') }}" class="nav-link">
                                        <p>Conciliaciones</p>
                                    </a>
                                </li>


                            </ul>
                        </li>
                    @endif
                    @if (currentUser()->can('ver_usuarios'))
                        <li
                            class="nav-item has-treeview {{ (!Route::is('users.index') and !Route::is('users.edit') and !Route::is('users.create')) ?: 'menu-open' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Usuarios
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ml-3">
                                    <a href="{{ route('users.index') }}" class="nav-link">
                                        <p>Listar</p>
                                    </a>
                                </li>
                                @if (currentUser()->can('crear_usuarios'))
                                    <li class="nav-item ml-3">
                                        <a href="{{ route('users.create') }}" class="nav-link">
                                            <p>Nuevo</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (currentUser()->can('ver_horarios') and config('app.name') != 'ConciliApp')
                        <li class="nav-item has-treeview {{ !Route::is('horarios.index') ?: 'menu-open' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon far fa-calendar-alt"></i>
                                <p>
                                    Horarios
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ml-3">
                                    <a href="{{ route('horarios.index') }}" class="nav-link">
                                        <p>Calendario</p>
                                    </a>
                                </li>
                                @if (currentUser()->hasRole('amatai') ||
                                        currentUser()->hasRole('diradmin') ||
                                        currentUser()->hasRole('dirgral') ||
                                        currentUser()->hasRole('coordprac') ||
                                        currentUser()->can('ver_turnos_estudiantes'))
                                    <li class="nav-item ml-3">
                                        <a href="{{ route('turnos.index') }}" class="nav-link">
                                            <p>Turnos estudiantes</p>
                                        </a>
                                    </li>
                                @endif
                                @if (currentUser()->can('ver_horarios_docente') ||
                                        currentUser()->hasRole('amatai') ||
                                        currentUser()->hasRole('diradmin') ||
                                        currentUser()->hasRole('dirgral') ||
                                        currentUser()->hasRole('coordprac'))
                                    <li class="nav-item ml-3">
                                        <a href="{{ url('/turnos/docentes') }}" class="nav-link">
                                            <p>Turnos docentes</p>
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif
                    <li class="nav-item has-treeview">
                        @if (!currentUser()->hasRole('visitante_conciliacion') and !currentUser()->hasRole('solicitante'))
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-file-word"></i>
                                <p>
                                    Biblioteca
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                        @endif
                        <ul class="nav nav-treeview">
                            <li class="nav-item ml-3">
                                <a href="{{ url('/bibliotecas') }}" class="nav-link">

                                    <p>Ver documentos activos</p>
                                </a>
                            </li>
                            @if (currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral'))
                                <li class="nav-item ml-3">
                                    <a href="{{ url('/bibliotecas/inactivas/view') }}" class="nav-link">

                                        <p>Ver historico</p>
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item ml-3">
                                <a href="/bibliotecas/create" class="nav-link">
                                    <p> Subir documento </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @if (currentUser()->can('ver_configuracion') and config('app.name') != 'ConciliApp')
                        <li class="nav-item has-treeview {{ !Route::is('notas.index') ?: 'menu-open' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-cog"></i>
                                <p>
                                    Reportes
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ml-3">
                                    <a href="{{ route('notas.index') }}" class="nav-link">
                                        <p>Notas</p>
                                    </a>
                                </li>
                                <li class="nav-item ml-3">
                                    <a href="{{ route('graficas.index') }}" class="nav-link">
                                        <p>Gráficos</p>
                                    </a>
                                </li>
                                <li class="nav-item ml-3">
                                    <a href="{{ url('actuar/docente') }}" class="nav-link">
                                        <p>Actuar docente</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if (currentUser()->can('ver_configuracion'))
                        <li
                            class="nav-item has-treeview {{ (!Route::is('periodos.index') and !Route::is('segmentos.index')) ?: 'menu-open' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-cog"></i>
                                <p>
                                    Configuración
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ml-3">
                                    <a href="{{ route('periodos.index') }}" class="nav-link">
                                        <p>Periodos</p>
                                    </a>
                                </li>
                                <li class="nav-item ml-3">
                                    <a href="{{ route('segmentos.index') }}" class="nav-link">
                                        <p>Cortes</p>
                                    </a>
                                </li>
                                @if (currentUser()->hasRole('amatai'))
                                    <li class="nav-item ml-3">
                                        <a href="{{ route('roles.admin') }}" class="nav-link">
                                            <p>Roles y permisos</p>
                                        </a>
                                    </li>
                                    <li class="nav-item ml-3">
                                        <a href="{{ route('categorias.index') }}" class="nav-link">
                                            <p>Categorías</p>
                                        </a>
                                    </li>
                                @endif
                                {{--  <li class="nav-item ml-3">
                                <a href="{{ route('categories.index') }}" class="nav-link">
                                    <p>Categorías estáticas</p>
                                </a>
                            </li> --}}

                            </ul>
                        </li>
                    @endif

                    <li class="nav-item has-treeview {{ !Route::is('horarios.index') ?: 'menu-open' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-comment-dots"></i>
                            <p>
                                Incidencias
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ml-3">
                                <a href="{{ route('incidencias.index') }}" class="nav-link">
                                    <p>Solicitar atención</p>
                                </a>
                            </li>
                        </ul>
                    </li>


                    {{-- <li class="nav-item has-treeview {{ !Route::is('incidencias') ?: 'menu-open' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-comment-dots"></i>
                            <p>
                                Incidencias
                                <i class="fas fa-angle-left right"> </i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ml-3">
                                <a href="{{ route('incidencias.index') }}" class="nav-link">
                                    <p>Solicitar atención</p>
                                </a>
                            </li>

                        </ul>
                    </li> --}}



                </ul>
                </li>
                </ul>
            </nav>
        @endif
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
