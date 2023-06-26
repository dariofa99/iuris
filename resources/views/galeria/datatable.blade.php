@extends('layouts.dashboard')


@section('titulo_general')
Galeria
@endsection

@section('titulo_area')

@endsection


@section('area_forms')

@include('msg.success')

	<div class="row">
		<div class="col-md-12">
		<div id="contenedo">
            <section>
             
                    

          <div class="box-body">
               <table class="table table-bordered" id="users-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
    </table>
            </div>


                    
                
            </section>

        </div>
	</div>
	</div>
{{-- @include('galeria.frm_biblioteca_edit')
@include('galeria.show_detalles_modal')--}}


@stop
