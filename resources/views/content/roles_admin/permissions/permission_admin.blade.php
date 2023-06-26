
@extends('layouts.dashboard')

@section('titulo_general')


@endsection

@section('titulo_area')
Editar
@endsection

@section('area_buttons')
<div class="pull-right" style="float: right !important;">
	<a href="#" class="btn-atrasexed pull-right btn bg-gray" style="color:#777"><i class="fa fa-backward"></i> Atrás</a>
</div>
@endsection
 

@section('area_forms') 

<div class="row">
    @include('content.roles_admin.navbar')
        <!-- Nav tabs -->
        <div class="col-md-4" id="content_form">
      <form action="{{url('permissions')}}" id="myFormCreatePermission">
              
                    <input type="hidden" required class="form-control" id="id" name="id">
                  
                    <div class="form-group">
                        <label for="display_name">Nombre largo</label>
                        <input type="text" required class="form-control" id="display_name" name="display_name">
                    </div>

                    <div class="form-group">
                        <label for="name">Nombre corto</label>
                        <input type="text" required class="form-control" id="name" name="name">
                    </div>

                    <div class="form-group">
                        <label for="description">Descripción</label>
                    <textarea required class="form-control" name="description" rows="2" id="description"></textarea>    
                    </div>

                    <button type="submit" id="btn_save_permission" name="button" class="btn btn-success btn-block">Guardar</button>
                    
                    <button type="button" style="display:none" data-form="myFormCreatePermission" id="btn_save_cancel" class="btn btn-default btn-block">Cancelar</button>
                    
                    </form>

          </div> 


          <div class="col-md-8">

         <div class="card-body content_ajax">
                @include('content.roles_admin.permissions.permissions_list_ajax')
              </div>

          </div> 

          </div>


@stop


@push('scripts')
<!-- aqui van los scripts de cada vista -->

@endpush




