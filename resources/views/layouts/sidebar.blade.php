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
                <a href="/users/{{ auth()->user()->id }}/edit" id="name_profile_user_sidebar"
                    title="Ingresar a perfil">{{ Auth::user()->name }}</a>
                @if (currentUser()->turno)
                    <span title="Color del turno"
                        class="badge {{ currentUser()->getColorTurno(currentUser()->turno->color->ref_value) }}">.</span>
                @else
                @endif



                <a href="{{ route('logout.index') }}">
                    <br>
                    <i class="fas fa-sign-out-alt"></i>

                    {{ __('Salir') }}
                </a>


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
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                {{--     <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Expedientes
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Nuevo expediente</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index2.html" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v2</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index3.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v3</p>
                </a>
              </li>
            </ul>
          </li> --}}
                <li
                    class="nav-item has-treeview {{ (!Route::is('expedientes.index') 
                    and !Route::is('expedientes.create')
                    and !Route::is('expedientes.edit')) ?: 'menu-open' }}">
                    <a href="/casos" class="nav-link">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>
                            Expedientes
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item ml-3">
                            <a href="{{ route('expedientes.index') }}" class="nav-link">
                                <i class="fas fa-clipboard nav-icon"></i>
                                <p>Ver expedientes</p>
                            </a>
                        </li>

                        <li class="nav-item ml-3">
                            <a href="{{ route('expedientes.create') }}" class="nav-link">
                                <i class="fa fa-pen-square nav-icon"></i>
                                <p>Nuevo Expediente</p>
                            </a>
                        </li>



                    </ul>
                </li>
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
                                <i class="fa fa-pen-square nav-icon"></i>
                                <p>Listar</p>
                            </a>
                        </li>
                        <li class="nav-item ml-3">
                            <a href="{{ route('users.create') }}" class="nav-link">
                                <i class="fas fa-user nav-icon"></i>
                                <p>Nuevo</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @if (1 > 0)
                    <li class="nav-item">
                        <a href="/recepciones" class="nav-link">
                            <i class="nav-icon fas fa-clipboard"></i>
                            <p>
                                Recepciones
                                <span class="badge badge-info right">0</span>
                            </p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="/agenda" class="nav-link">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Agenda
                            <span class="badge badge-info right">0</span>
                        </p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="/clientes" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Solicitantes
                            <span class="badge badge-info right">0</span>
                        </p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="/directorio" class="nav-link">
                        <i class="nav-icon fa fa-address-book"></i>
                        <p>
                            Directorio
                            <span class="badge badge-info right">0</span>
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/biblioteca" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Biblioteca
                            <span class="badge badge-info right">0</span>
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/reportes" class="nav-link">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            Reportes
                            {{--  <span class="badge badge-info right">0</span> --}}
                        </p>
                    </a>
                </li>


                <li class="nav-item has-treeview ">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Expedientes
                            <i class="fas fa-angle-left right"></i>
                            {{-- <span class="badge badge-info right">6</span> --}}
                        </p>
                    </a>
                    <ul class="nav nav-treeview">


                        <li class="nav-item ml-3">
                            <a href="{{ route('expedientes.create') }}" class="nav-link">
                                <i class="fas fa-users-cog nav-icon"></i>
                                <p>Nuevo Expediente</p>
                            </a>
                        </li>


                        <li class="nav-item ml-3">
                            <a href="{{ route('expedientes.index') }}" class="nav-link">
                                <i class="fas fa-user-plus nav-icon"></i>
                                <p>Ver expedientes</p>
                            </a>
                        </li>

                        <li class="nav-item ml-3">
                            <a href="{{ route('categorias.index') }}" class="nav-link">
                                <i class="fas fa-bars nav-icon"></i>
                                <p>Categorias</p>
                            </a>
                        </li>

                        <li class="nav-item ml-3">
                            <a href="{{ route('auditoria.index') }}" class="nav-link">
                                <i class="fas fa-database nav-icon"></i>
                                <p>Auditoria</p>
                            </a>
                        </li>

                        {{--     <li class="nav-item">
                <a href="pages/layout/fixed-footer.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Fixed Footer</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/layout/collapsed-sidebar.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Collapsed Sidebar</p>
                </a>
              </li> --}}
                    </ul>
                </li>

                @can('ver_administracion')
                    <li class="nav-item has-treeview ">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-mobile-alt"></i>
                            <p>
                                Botón de pánico
                                <i class="fas fa-angle-left right"></i>
                                {{-- <span class="badge badge-info right">6</span> --}}
                            </p>
                        </a>
                        <ul class="nav nav-treeview">


                            <li class="nav-item ml-3">
                                <a href="{{ route('panic.alerts') }}" class="nav-link">
                                    <i class="fas fa-bell nav-icon"></i>
                                    <p>Alertas</p>
                                </a>
                            </li>

                            @can('ver_usuarios')
                                <li class="nav-item ml-3">
                                    <a href="{{ route('panic.directories') }}" class="nav-link">
                                        <i class="fas fa-address-card nav-icon"></i>
                                        <p>Usuarios - Directorio</p>
                                    </a>
                                </li>
                            @endcan




                            {{--     <li class="nav-item">
      <a href="pages/layout/fixed-footer.html" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Fixed Footer</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="pages/layout/collapsed-sidebar.html" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Collapsed Sidebar</p>
      </a>
    </li> --}}
                        </ul>
                    </li>
                @endcan


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
