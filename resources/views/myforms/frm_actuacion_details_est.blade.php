@component('components.b4.modal_large')


    @slot('trigger')
        myModal_act_details
    @endslot

    @slot('title')
        Detalles
    @endslot


    @slot('body')
        {!! Form::open(['id' => 'myform_act_edit_docen', 'files' => true]) !!}
       <div class="row">

	  
		<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        @section('msg-contenido')
            Registrado
        @endsection
        @include('msg.ajax.success')

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Código expediente') !!}
                {!! Form::text('actexpid_det', $expediente->expid, [
                    'id' => 'actexpid_det',
                    'class' => 'form-control',
                    'readonly',
                ]) !!}
            </div>
        </div>






        <div class="col-sm-6">
            {!! Form::label('Fecha creación: ') !!}

			<div class="input-group mb-3">
				<div class="input-group-prepend">
				  <span class="input-group-text" id="inputGroup-sizing-default">
					<i class="fa fa-calendar"></i>
				  </span>
				</div>
				{!! Form::text('actfecha', null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'data-inputmask' => "'alias': 'yyyy/mm/dd'",
                    'data-mask',
                    'readonly',
                ]) !!}  </div>

             <!-- /.input group -->
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Creado por:') !!}
                {!! Form::text('fullnameest', null, [
                    'id' => 'fullnameest',
                    'class' => 'form-control required',
                    'maxlength' => '225',
                    'readonly',
                ]) !!}
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Título:') !!}
                {!! Form::text('actnombre_det', null, [
                    'id' => 'actnombre_det',
                    'class' => 'form-control required',
                    'maxlength' => '225',
                    'readonly',
                ]) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Fecha límite de entrega:') !!}
                {!! Form::date('fecha_limit_d', null, [
                    'id' => 'fecha_limit_d',
                    'class' => 'form-control required',
                    'maxlength' => '225',
                    'readonly',
                ]) !!}
            </div>
        </div>

		<div class="col-md-6">
			<div class="form-group">
				{!! Form::label('Estado de la actuación') !!}
				{!! Form::select('actestado_det', $act_estados, null, [
					'id' => 'actestado_det',
					'placeholder' => 'Selecciona...',
					'class' => 'form-control',
					'required' => 'required',
					'disabled',
				]) !!}
			</div>
		</div>


        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('Descripción: ') !!}
                {!! Form::textarea('actdescrip_det', null, [
                    'id' => 'actdescrip_det',
                    'class' => 'form-control required',
                    'maxlength' => '225',
                    'rows' => 4,
                    'readonly',
                ]) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('Última actualizacion hecha por: ') !!}
                <label id="label_nombre_docente">Nombre del docente</label>
            </div>
        </div>
        <div id="datos_docente">
<div class="row">


			<div class="col-md-6">
                <div class="form-group">
                    <div>
                        <label>Archivo estudiante:</label><br>

                        <label> <i class="fa fa-file"> </i> <small id="label-upload"> <i id="lab-nombre-est">
							Nombre del archivo</i></small>
                        </label>

                    </div>

                </div>
            </div>

         
            <div class="col-md-6">
                <div class="form-group">
                    <div>
                        <label>Archivo docente:</label><br>

                        <label> 
							<i class="fa fa-file"> </i>
							 <small id="label-upload"> <i id="lab-nombre-doc">
								Nombre del  archivo</i></small>
                        </label>

                    </div>

                </div>
            </div>


            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Recomendación: ') !!}
                    {!! Form::textarea('actdocenrecomendac_det', null, [
                        'id' => 'actdocenrecomendac_det',
                        'class' => 'form-control required',
                        'maxlength' => '10000',
                        'rows' => 4,
                        'disabled',
                    ]) !!}
                </div>
            </div>

		</div>
        </div>


        <div class="row" id="cont_notas_ac" style="display: none;">
            <div class="col-md-12">
                <h4>Notas:</h4>
                <input type="hidden" id="actuacion_id">
                <div class="row">
                    <div class="col-md-3">
                        <label for="">Nota Conocimiento</label><br>
                        <label id="lbl_not_conac"></label>
                    </div>
                    <div class="col-md-3">
                        <label for="">Nota Aplicación</label><br>
                        <label id="lbl_not_aplac"></label>
                    </div>
                    <div class="col-md-3">
                        <label for="">Nota Ética</label><br>
                        <label id="lbl_not_etiac"></label>
                    </div>
                    @if ($segmento)
                        <div class="col-md-3">
                            <input type="hidden" value="{{ $segmento->id }}" id="segmento_id" />
                            <a style="cursor:pointer" id="btn_cam_nt_act">Cambiar Notas</a>
                            <br>

                        </div>
                    @endif
                    <div class="col-md-12">
                        <label for="">Concepto</label><br>
                        {!! Form::textarea('ntaconcepto', null, [
                            'id' => 'ntaconcepto_text',
                            'class' => 'form-control required',
                            'maxlength' => '225',
                            'rows' => 3,
                            'disabled',
                        ]) !!}

                    </div>

                    <div class="col-md-12">
                        <label for="">Evaluado por: </label>
                        <i id="lbldocevname">user</i>
                    </div>

                </div>
            </div>
        </div>
	</div>
        {!! Form::close() !!}
    @endslot
@endcomponent
<!-- /modal -->
