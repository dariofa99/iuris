{!!Form::model($expediente, ['route'=>['expedientes.update', $expediente->id], 'method'=>'PUT','id'=>'form_expediente_edit'])!!}
<div class="shadow">
@include('myforms.components_exp.frm_datos_gen')
 

</div>
    <!--cont_data_req-->
    <div @if(currentUser()->hasRole('estudiante')) id="cont_data_req" @endif>
        <div class="row">
            <div class="col-md-4">
                {!!Form::label('Identidicación: ') !!}
                <label class="lab-ast-req" title="Campo obligatorio"> * </label>
        
                    <div class="input-group">
        
                            <div class="input-group-btn">
        
                              {!! Form::button('Editar', array($disabled,'class'=>'btn btn-success','data-toggle'=>'modal', 'data-target'=>'#myModal_exp_user_edit', 'value'=>$expediente->solicitante->id , 'id'=>'btn_exp_user_carga')) !!}
                            </div>
                        <!-- /btn-group -->
                        {!!Form::text('expidnumber', $expediente->solicitante->idnumber , [$disabled,'class' => 'form-control', 'required' => 'required' , 'readonly' ] ); !!}
                      </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!!Form::label('Nombres: ') !!}
                    <label class="lab-ast-req" title="Campo obligatorio"> * </label>
                    {!!Form::text('name',   $expediente->solicitante->name  , [$disabled,'class' => 'form-control required', 'readonly' ]); !!}
                </div>
            </div>
        
            <div class="col-md-4">
                <div class="form-group">
                    {!!Form::label('Apellidos: ') !!}
                    <label class="lab-ast-req" title="Campo obligatorio"> * </label>
                    {!!Form::text('lastname',  $expediente->solicitante->lastname , [$disabled,'class' => 'form-control required' , 'readonly']); !!}
                </div>
            </div>
        </div>
        <div class="row">
            
            <div class="col-md-4">
                <div class="form-group"> 
                    {!!Form::label('Departamento: ') !!}
                    <label class="lab-ast-req" title="Campo obligatorio"> * </label>
        
                    {!!Form::select('expdepto_id',$deptos,$expediente->expdepto_id, [$disabled,'placeholder' => 'Selecciona...', 'class' => 'form-control required', 'required' => 'required','onblur'=>'comprDato("form_expediente_user_edit")','data-name'=>'Departamento' ]); !!}
                </div>
            </div>
        
            <div class="col-md-4">
                <div class="form-group">
                    {!!Form::label('Municipio: ') !!}
                    <label class="lab-ast-req" title="Campo obligatorio"> * </label>
        
                    {!!Form::select('expmunicipio_id',$muncpios,$expediente->expmunicipio_id, [$disabled,'placeholder' => 'Selecciona...', 'class' => 'form-control required', 'required' => 'required','onblur'=>'comprDato("form_expediente_user_edit")','data-name'=>'Municipio' ]); !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {!!Form::label('Juzgado o entidad: ') !!}
                    {!!Form::text('expjuzoent', null , ['class' => 'form-control','maxlength'=>'120',$disabled ]); !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!!Form::label('Número de proceso ') !!}
                    {!!Form::text('expnumproc',  null , ['class' => 'form-control',$disabled ]); !!}
                </div>
            </div>
        
        
        
            <div class="col-md-4">
                <div class="form-group">
                    {!!Form::label('Persona Demandante') !!}
                    {!!Form::text('exppersondemandante',  null , ['class' => 'form-control',$disabled ]); !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {!!Form::label('Persona Demandada') !!}
                    {!!Form::text('exppersondemandada',  null , ['class' => 'form-control',$disabled ]); !!}
                </div> 
            </div>
        
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!!Form::label('Resumen del Caso: ') !!}
                    {!!Form::textarea('exphechos',  null , [$disabled,'class' => 'form-control','maxlength'=>'100000','id'=>'exp_hechos' ]); !!}
                </div>
            </div>
        </div>
        @if(currentUser()->hasRole("estudiante") and ($expediente->expestado_id==1 || $expediente->expestado_id==3 ))
		<div class="row">
			<div class="col-md-12" align="right">
		    	<div class="form-group" >
                  <br/>
                    <button id="btn-enviar-dataEst" class="btn btn-primary btn-lg" {{ $disabled }} disabled>
                        <i class="fa fa-save"> </i> Guardar Datos del Caso
                    </button>
				</div>
            </div>
        </div>
        @endif
    </div>
<!--cont_data_req-->
{!!Form::close()!!}
@include('myforms.frm_expediente_user_edit')
