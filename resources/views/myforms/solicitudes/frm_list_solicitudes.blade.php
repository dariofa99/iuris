@extends('layouts.dashboard')
@section('titulo_area')
    Solicitudes
@endsection

@section('area_forms')

    @include('msg.success')

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link urlactive active" id="revisar" data-toggle="tab" href="#revisar-tab" role="tab"
                        aria-controls="revisar-tab" aria-selected="true">
                        Por revisar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link urlactive" id="historial" data-toggle="tab" href="#historial-tab"
                        role="tab" aria-controls="historial" aria-selected="false">
                        Historial
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content" id="myTabContent" style="margin-top: 10px !important">

                <div class="tab-pane fade active show" id="revisar-tab" role="tabpanel" aria-labelledby="revisar-tab">
                        @include('myforms.solicitudes.frm_list_solicitudes_ajax')
                </div>

                <div class="tab-pane fade" id="historial-tab" role="tabpanel" aria-labelledby="historial-tab">
                        @include('myforms.solicitudes.frm_list_solicitudesh_ajax')
               </div>
            </div>
        </div>
    </div>

@stop
