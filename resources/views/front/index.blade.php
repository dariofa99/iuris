@extends('layouts.dashboard')



@section('titulo_area')
  @if(currentUser()->hasRole('solicitante'))
    Mis Casos
  @else
    Listar
  @endif
@endsection



@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@section('area_forms') 

@include('msg.success')
@include('msg.info') 

<div class="row">
  @foreach(auth()->user()->sedes as $key => $sede)    
  <div class="col-md-3">
      <form id="myFormCambiarSede-{{$sede->id_sede}}" action="{{url('/change/sedes')}}" method="GET">
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
      </form>      
  </div>     
  @endforeach
</div>


@stop
