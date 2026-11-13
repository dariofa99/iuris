@extends('layouts.dashboard')

@section('body_class')
    @if (currentUser()->hasRole('solicitante'))
        solicitante-portal-page
    @endif
@endsection

@section('navbar')
    @if (currentUser()->hasRole('solicitante'))
        <nav class="main-header navbar navbar-expand navbar-white navbar-light solicitante-portal-topbar">
            <a href="{{ url('/oficina/solicitante') }}" class="solicitante-portal-brand">
                <img src="{{ asset('dist/img/consultorios-min.png') }}" alt="IURIS">
                <span>Portal de atención virtual</span>
            </a>
            <div class="d-flex align-items-center ml-auto">
                <span class="solicitante-portal-user mr-3">
                    {{ Auth::user()->name }} {{ Auth::user()->lastname }}
                </span>
                <a href="{{ route('logout.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-sign-out-alt mr-1" aria-hidden="true"></i>
                    Salir
                </a>
            </div>
        </nav>
    @else
        @include('content.navbar')
    @endif
@endsection

@section('titulo_area')
    @if (currentUser()->hasRole('solicitante'))
        Portal del solicitante
    @else
        Listar
    @endif
@endsection

@section('area_forms')
    @if (currentUser()->hasRole('solicitante'))
        <main class="solicitante-portal">
            <section class="solicitante-portal-hero iuris-gradient-banner">
                <div class="iuris-gradient-banner-content">
                    <span class="solicitante-portal-eyebrow">Atención virtual IURIS</span>
                    <h1>Hola, {{ Auth::user()->name }}</h1>
                    <p>
                        Desde este espacio puedes iniciar una solicitud de conciliación, consultar tus procesos
                        y continuar su gestión en línea.
                    </p>
                </div>
            </section>

            <h2 class="solicitante-portal-section-title">¿Qué necesitas hacer?</h2>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <section class="solicitante-service-card solicitante-service-card--primary">
                        <div class="solicitante-service-icon iuris-icon-tile" aria-hidden="true">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3>Conciliación virtual</h3>
                        <p>
                            Solicita atención y gestiona tu proceso desde un solo lugar: completa la información,
                            adjunta documentos y consulta las novedades.
                        </p>
                        <div class="d-flex flex-wrap">
                            @if (currentUser()->can('crear_conciliaciones'))
                                <a href="#" id="btn_new_conciliacion" class="btn btn-iuris-primary mr-2 mb-2">
                                    <i class="fas fa-plus mr-1" aria-hidden="true"></i>
                                    Solicitar conciliación
                                </a>
                            @endif
                            <a href="{{ route('front.conciliaciones') }}" class="btn btn-outline-iuris mb-2">
                                <i class="fas fa-folder-open mr-1" aria-hidden="true"></i>
                                Ver mis procesos
                            </a>
                        </div>
                    </section>
                </div>

                <div class="col-lg-6 mb-3">
                    <section class="solicitante-service-card solicitante-service-card--disabled">
                        <div class="solicitante-service-icon iuris-icon-tile" aria-hidden="true">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <span class="solicitante-service-status">No disponible por ahora</span>
                        <h3>Asesoría jurídica virtual</h3>
                        <p>
                            Estamos trabajando para habilitar este servicio. Por el momento puedes iniciar o
                            gestionar una conciliación virtual.
                        </p>
                        <button type="button" class="btn btn-outline-secondary" disabled aria-disabled="true">
                            Próximamente
                        </button>
                    </section>
                </div>
            </div>

            <section class="solicitante-portal-help">
                <h3><i class="fas fa-info-circle text-info mr-2" aria-hidden="true"></i>¿Cómo gestionar tu conciliación?</h3>
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="solicitante-portal-step">
                            <span class="solicitante-portal-step-number">1</span>
                            <span>Inicia una solicitud con el botón <strong>Solicitar conciliación</strong>.</span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="solicitante-portal-step">
                            <span class="solicitante-portal-step-number">2</span>
                            <span>Completa la información del proceso y adjunta los documentos solicitados.</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="solicitante-portal-step">
                            <span class="solicitante-portal-step-number">3</span>
                            <span>Vuelve a <strong>Mis procesos</strong> para revisar el estado y las novedades.</span>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    @else
        @include('msg.success')
        @include('msg.info')

        <div class="row">
            @foreach (auth()->user()->sedes as $key => $sede)
                <div class="col-md-3">
                    {{--   <form id="myFormCambiarSede-{{$sede->id_sede}}" action="{{url('/change/sedes')}}" method="GET">
          <div class="panel panel-default">
              <!-- Default panel contents -->
              <div class="panel-heading">{{$sede->nombre}}</div>
              <div class="panel-body">
                  <input type="hidden" name="sede_id" value="{{$sede->id_sede}}">
                <p>{{$sede->ubicacion}}</p>
              </div>
            <div class="panel-footer">
                <button data-id="{{$sede->id_sede}}" {{(session()->has('sede') and session()->get('sede')->id_sede == $sede->id_sede) ? 'disabled' : ''}} type="button" class="btn btn-success btn_change_sede">
                  Seleccionar
              </button>
            </div>
          </div>
      </form>    --}}
                </div>
            @endforeach
        </div>
    @endif

    @include('front.solicitudes.frm_modal_mensaje_solicitud_conciliacion')
@stop

@push('scripts')
    <script type="module" src={{ asset('js/admin_conciliacion.js?v=' . config('app_config.asset_version')) }}></script>
@endpush
