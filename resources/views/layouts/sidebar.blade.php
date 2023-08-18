<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-{{ $sidebar_modo }}-primary elevation-4" style="background-color:#222d32">
    <!-- Brand Logo -->

    <a href="/home" class="brand-link"
        style="background-color: #374850 !important; padding: .4125rem .5rem;text-align: center;">
        <img src="{{ asset('dist/img/consultorios-min.png') }}" alt="Lybra" class="img-fluid"
            style="border-radius: 20px;height:42px">
        <span class="brand-text font-weight-{{ $sidebar_brand_modo }}"></span> IURIS </span>
    </a>


    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <a href="/users/{{ auth()->user()->id }}/edit" title="Ingresar a perfil">
                    <img src="{{ is_file(public_path('thumbnails/' . currentUser()->image)) ? asset('thumbnails/' . currentUser()->image) : asset('thumbnails/default.jpg') }}"
                        id="image_profile_user_sidebar" class="img-circle elevation-2 image_profile" alt="User Image">

                </a>
            </div>
            <div class="info">
                <small>
                    <a href="/users/{{ auth()->user()->id }}/edit" id="name_profile_user_sidebar"
                        title="Ingresar a perfil">{{ Auth::user()->name }}</a>


                </small>

                @if (currentUser()->turno)
                    <span style="width: 20px; height:20px; border-radius:10px" title="Color del turno"
                        class="badge {{ currentUser()->getColorTurno(currentUser()->turno->color->ref_value) }}">.</span>
                @else
                @endif
                <br>
                <small>
                    <a>
                        @if (currentUser()->hasRole('estudiante') and currentUser()->turno != null)
                         Horario: {{ currentUser()->turno->horario->ref_nombre }}
                        @endif
                    </a>
<br>
                    <a href="{{ route('logout.index') }}">
                        <i class="fas fa-sign-out-alt"></i>
    
                        {{ __('Salir') }}
                    </a>
                </small>


                


            </div>

            <hr>


            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li
                    class="nav-item has-treeview {{ (!Route::is('expedientes.index') and !Route::is('expedientes.create') and !Route::is('expedientes.edit')) ?:
                        'menu-open' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>
                            Expedientes
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
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
                        @if (currentUser()->hasRole('coordprac') ||
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
                @if (currentUser()->can('ver_conciliaciones_'))
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Conciliaciones
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ml-3">
                                <a href="{{ route('users.index') }}" class="nav-link">

                                    <p>Listar</p>
                                </a>
                            </li>
                            <li class="nav-item ml-3">
                                <a href="{{ route('reportes.create') }}" class="nav-link">
                                    <p> Administrar actas y correos</p>
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
                @if (currentUser()->can('ver_horarios'))
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
                                    currentUser()->hasRole('coordprac'))
                                <li class="nav-item ml-3">
                                    <a href="{{ route('turnos.index') }}" class="nav-link">
                                        <p>Turnos estudiantes</p>
                                    </a>
                                </li>
                            @endif
                            @if (currentUser()->hasRole('amatai') ||
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
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-word"></i>
                        <p>
                            Biblioteca
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item ml-3">
                            <a href="{{ url('/bibliotecas') }}" class="nav-link">

                                <p>Ver documentos</p>
                            </a>
                        </li>
                        <li class="nav-item ml-3">
                            <a href="/bibliotecas/create" class="nav-link">
                                <p> Subir documento </p>
                            </a>
                        </li>
                    </ul>
                </li>

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
                        </ul>
                    </li>
                @endif
                
            </ul>
            </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
