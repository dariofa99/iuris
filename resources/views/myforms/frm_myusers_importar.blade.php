@extends('layouts.dashboard')
@section('area_forms')

@include('msg.success')

<div class="col-md-12">
<nav class="navbar navbar-default">
  <div class="container-fluid">
   <div class="navbar-header">
    <a class="navbar-brand" href="#">Importar Usuarios</a>
   </div>
  </div>
 </nav>
 <div class="container">
  
  <form action="{{ URL::to('usuarios/importar/iniciar') }}" class="form-horizontal" method="post" handling facade or enctype="multipart/form-data">
    {{ csrf_field() }}
   <div class="col-md-4">
   <input type="file" name="file" />
   </div>

    <div class="col-md-4">
   <button class="btn btn-primary">Importar</button>
   </div>
  </form>
 </div>
</div> 

@stop