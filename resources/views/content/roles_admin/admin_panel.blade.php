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

<div class="container-fluid spark-screen">
    <div class="row">
        <!-- Nav tabs -->
        <div class="col-md-12 ">
        
     @include('content.roles_admin.navbar')
       

              </div>
      </div>
  </div>

 <div class="container-fluid spark-screen">
    <div class="row">
        <!-- Nav tabs -->
        <div class="col-md-12">
        
    
  

  <!-- Tab panes -->
  <div class="">
    <div role="" class="table-responsive no-padding" id="home"> 
    <table class="table table-hover" id="table_list" style="font-size:14px">
            
            <tbody>   
            
             
            </tbody>
          </table>
  </div>
    

  </div>      

              </div>
      </div>
  </div>


@stop


@push('scripts')
<!-- aqui van los scripts de cada vista -->
<script type="text/javascript">
(function(){
 getRolesPermissions();

})();
 
</script>
@endpush