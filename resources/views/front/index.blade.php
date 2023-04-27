@extends('layouts.front.dashboard')

@section('titulo_general')
@if(currentUser()->hasRole('solicitante'))
    Casos
  @else
    Expedientes
  @endif

@endsection

@section('titulo_area')
  @if(currentUser()->hasRole('solicitante'))
    Mis Casos
  @else
    Listar
  @endif
@endsection




@section('area_forms') 

@include('msg.success')
@include('msg.info') 




@stop
