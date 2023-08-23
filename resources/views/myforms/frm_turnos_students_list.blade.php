@extends('layouts.dashboard')




@section('titulo_area')
    Listado de Estudiantes
@endsection

@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection
@section('area_forms')

    @include('msg.success')

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active urlactive" id="estasig-tab" data-toggle="tab" href="#estasig_tab" role="tab"
                aria-controls="estasig_tab" aria-selected="false">
                Estudiante Asignados
            </a>
        </li>
        @if (currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral'))
            <li class="nav-item">
                <a class="nav-link urlactive" id="estsinasig-tab" data-toggle="tab" href="#estsinasig_data" role="tab"
                    aria-controls="estsinasig_data" aria-selected="false">
                    Estudiantes por Asignar
                </a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link urlactive" id="reporasistencia-data" data-toggle="tab" href="#reporasistencia_data" role="tab"
                aria-controls="reporasistencia_data" aria-selected="true">
                Reporte asistencia
            </a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent" style="margin-top: 10px !important">
        <div class="tab-pane fade show active" id="estasig_tab" role="tabpanel" aria-labelledby="estasig-tab">
          @include("myforms.turnos.estudiantes_asignados")
        </div>
    </div> 

    @include('myforms.frm_modal_asig_turno_est')
    @include('myforms.frm_modal_detail_reporasistencia')


@stop
@push('scripts')
      <script type="module" src={{ asset('js/admin_turnos.js') }}></script>
@endpush