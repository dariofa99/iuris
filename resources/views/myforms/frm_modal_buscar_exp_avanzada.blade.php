@component('components.modal')

	@slot('trigger')
		mymodalBuscarExpAvanzadas
	@endslot


	@slot('title')
		Busqueda avanzada
	@endslot

 
	@slot('body')


@section('msg-contenido')

@endsection
@include('msg.ajax.success')
<div class="row">
    <div class="col-md-12 ">
        <form id="myFormBsExpAdv" method="GET" action="{{route('expedientes.index')}}">
            <input type="hidden" name="tipo_busqueda" value="adv">
            <div class="form-group col-md-12">
                <label for="">Estudiante</label>
                {!!Form::select('expidnumberest',[],null,['class' => 'selectpicker buscar_usuario', 'data-live-search'=>'true', 'data-select-origen'=>'estudiante', 'required' => 'required','id'=>'select_data_estudiantes','data-width'=>'500px','data-live-search-placeholder'=>'Escriba el nombre'] ); !!}
            
                 {{--  <input type="text" required class="form-control" name="expidnumberest" placeholder="Numero de identificación">
                --}}
            </div>
            <div class="form-group col-md-6">
                <label for="">Tipo de proceso</label>
                
                    {!!Form::select('exptipoproce_id',[                      
                       "2"=>"Seguimiento",
                       "1"=>"Asesoría",
                       '3'=>'Defensa de Oficio'
                       ],null,['class' => 'form-control', 'required' => 'required'] ); !!}                   
                       
            </div>

            <div class="form-group col-md-6">
                <label for="">Estado</label>
                
                {!!Form::select('expestado_id',[
                   
                   "1"=>"Abierto",
                   "2"=>"Cerrado",
                   "4"=>"En solicitud de cierre",
                   "3"=>"Rechazado"
                    ],null,['class' => 'form-control', 'required' => 'required'] ); !!}              
           
            </div>

            <div class="form-group col-md-6">
                <br>
                <button type="submit" class="btn btn-success"><i class="fa fa-search"> </i> Buscar </button>
                <button id="btn_desc_exp_us" type="button" class="btn btn-warning"><i class="fa fa-file-excel"> </i> Descargar </button>
        
            </div>

        </form>
    </div>
</div>

	@endslot
@endcomponent
<!-- /modal -->










