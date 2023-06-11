@extends('layouts.dashboard')


@section('titulo_general')
 
@endsection

@section('titulo_area')

@endsection


@section('area_forms')



<div class="contain">
   
<div class="row">
    <div class="col-md-12">
       <h2> Hola!!  {{  currentUser()->name  }} {{  currentUser()->lastname  }}  bienvenido a
         <strong>{{ config('app.name') }}
        </strong>!! </h2>
    </div>
</div>

    {{-- <div class="row">
        <div class="col-md-4 col-xl-3">
            <div class="card bg-light-blue color-palette order-card">
                <div class="card-block">
                    <h4 class="m-b-20">Estudiantes que menos han ingresado</h4>
                    <h2 class="text-right"><i class="fa fa-users f-left"></i><span>
                        <a style="color: aliceblue" href="/dashboard/search?type=users_not_session">486</a></span></h2>
                   {{--  <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> 
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-green order-card">
                <div class="card-block">
                    <h4 class="m-b-20">Estudiantes con menos interacciones</h4>
                    <h2 class="text-right"><i class="fa fa-user-times f-left"></i><span>486</span></h2>
                   {{--  <p class="m-b-0">Completed Orders<span class="f-right">351</span></p> 
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-yellow order-card">
                <div class="card-block">
                    <h4 class="m-b-20">Estudiantes con mas casos evaluados por sistema</h4>
                    <h2 class="text-right"><i class="fa fa-clipboard f-left"></i><span>486</span></h2>
                   {{--  <p class="m-b-0">Completed Orders<span class="f-right">351</span></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-pink order-card">
                <div class="card-block">
                    <h4 class="m-b-20">Estudiantes con notas mas bajas</h4>
                    <h2 class="text-right"><i class="fa fa-credit-card f-left"></i><span>486</span></h2>
                    
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-3">
          <div class="card bg-c-yellow order-card">
              <div class="card-block">
                  <h4 class="m-b-20">Casos con menos asesorias registradas por docentes</h4>
                  <h2 class="text-right"><i class="fa fa-credit-card f-left"></i><span>486</span></h2>
                  
              </div>
          </div>
      </div>

      <div class="col-md-4 col-xl-3">
        <div class="card bg-c-green order-card">
            <div class="card-block">
                <h4 class="m-b-20">Casos sin actuaciones</h4>
                <h2 class="text-right"><i class="fa fa-credit-card f-left"></i><span>486</span></h2>
                
            </div>
        </div>
    </div>
	</div> --}}
</div>



@if((count($sedes)>=2 and count(Auth::user()->sedes)<=0) || auth()->user()->can('cambiar_sede'))
@if(!auth()->user()->can('cambiar_sede'))
  <div class="row">
    <div class="col-md-12">
      <h3>Selecciona una sede, recuerda que este registro solo lo podras hacer una vez. <br>
      Para realizar el cambio deberás comunicarte con el administrador.</h3>
    </div>
  </div>
@endif
<div class="row">
    @foreach($sedes as $key => $sede)
    
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
@elseif(count($sedes)<=0)
<div class="col-md-12">       
  <div class="alert alert-warning alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong>Atención!</strong> No hay sedes disponibles..! <a href="{{route('sedes.index')}}">Crear una sede</a>
      @if(auth()->user()->can('cambiar_sede'))
<a href="{{route('sedes.index')}}">Crear una sede</a>
      @endif
    </div> 
</div>
@endif
@stop
