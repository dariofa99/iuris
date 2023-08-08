@component('components.b4.modal_large')


    @slot('size')
        modal-dialog modal-lg
    @endslot

    @slot('trigger')
        myModal_req_details
    @endslot

    @slot('title')
        Detalles
    @endslot


    @slot('body')
        {!! Form::open(['method' => 'post', 'id' => 'myformUpdateAct', 'files' => true]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="req_id_det">
        <div class="box-body table-responsive no-padding">
            <table class="table">

                <tr>
                    <td width="15%">
                        Código Expediente
                    </td>
                    <td>
                        <label id="lab_cod_exp_det"></label>
                    </td>
                    {{--  <td width="15%">
        Fecha Creación
      </td>
      <td>
        <label id="lab_fech_crea_det" ></label>
      </td> --}}
                </tr>
                <tr>
                    <td>
                        Cédula
                    </td>
                    <td>
                        <label id="lab_ced_solic_det"></label>
                    </td>
                </tr>
                <tr>
                    <td>
                        Nombres
                    </td>
                    <td>
                        <label id="lab_nom_solic_det"></label>
                    </td>
                    <td>
                        Apellidos
                    </td>
                    <td>
                        <label id="lab_apell_solic_det"></label>
                    </td>
                </tr>
                <tr>
                    <td>
                        Fecha Citación
                    </td>
                    <td>
                        <label id="lab_fech_cita_det"></label>
                    </td>
                    <td>
                        Hora Citación
                    </td>
                    <td>
                        <label id="lab_hora_cita_det"></label>
                    </td>
                </tr>
                <tr>
                    <td>
                        Motivo
                    </td>
                    <td colspan="3">
                        <label id="lab_req_motivo_det"></label>
                    </td>
                </tr>
                <tr>
                    <td>
                        Descripción
                    </td>
                    <td colspan="3">
                        <label id="lab_req_descrip_det"></label>
                    </td>
                </tr>
                <tr>
                    <td>
                        Registro de Asistencia
                    </td>
                    <td colspan="3">
                        <label id="lab_req_asistencia_det"></label>
                    </td>
                </tr>
                <tr>
                    <td>
                        Comentario Coordinador
                    </td>
                    <td colspan="3">
                        <label id="lab_req_comcoor_det"></label>
                    </td>
                </tr>
                <tr>
                    <td>
                        Comentario Estudiante
                    </td>
                    <td colspan="3">
                        <label id="lab_req_comest_det"></label>
                    </td>
                </tr>


                {{--     @if (currentUser()->hasRole('coordprac'))
    <tr>



      <td>
        Asistencia
      </td>
      <td colspan="3">

        <select name="reqid_asistencia" id="reqid_asistencia" class="form-control required"> 
          <option value="1">Asistieron Ambos</option>
          @foreach ($reqasis as $asis)          
            <option value="{{ $asis->reqid_refasis }}">{{ $asis->ref_reqasistencia }}</option>
          @endforeach  
        </select>
      </td>
    </tr>
    <tr>
      <td>
        Comentario
      </td>
      <td colspan="3">
        <textarea name="reqcomentario_coorprac" id="reqcomentario_coorprac" rows="5" class="form-control required"></textarea>
      </td>
    </tr>
  @endif
 
  @if (currentUser()->hasRole('docente'))
    <tr>
      <td>
        Nota
      </td>
      <td colspan="3">
        <input type="text" name="nota" id="val_input" class="form-control">
      </td>
    </tr>
    <tr>
      <td>
        Concepto Nota
      </td>
      <td colspan="3">
        <textarea name="com_coor_pract" id="com_coor_pract" rows="5" class="form-control"></textarea>
      </td>
    </tr>
  @endif

  @if (currentUser()->hasRole('estudiante'))
     <tr>
      <td>
        Nota
      </td>
      <td colspan="3">
        <input type="text" name="nota" id="val_input">
      </td>
    </tr> 
    <tr>
      <td>
       Comentario
      </td>
      <td colspan="3">
        <textarea name="com_coor_pract" id="com_coor_pract" rows="5" class="form-control"></textarea>
      </td>
    </tr>
  @endif


    <tr>
      <td colspan="4">
        <a class="btn btn-primary pull-right" onclick="updateRequerimiento()"> Guardar </a>
      </td>
    </tr> --}}
            </table>
        </div>
        {!! Form::close() !!}
        @if (isset($expedientes))
            <div class="row" id="cont_notas_req" style="display: none;">
                <div class="col-md-12">
                    <h4>Notas:</h4>
                    <div class="row">
                        <div class="col-md-9">
                            <label for="">Nota Ética: </label>
                            <label id="lbl_not_etireq"></label>
                        </div>

                        <div class="col-md-3">
                            @if ($expediente->getDocenteAsig()->idnumber == currentUser()->idnumber || currentUser()->hasRole('amatai'))
                                <input type="hidden" value="{{ $segmento->id }}" id="segmento_id" />
                                <a class="btn btn-sm btn-warning" style="cursor:pointer" id="btn_cam_nt_req">
                                    Cambiar Notas
                                </a>
                                <br>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <label for="">Concepto nota</label><br>
                            {!! Form::textarea('ntaconcepto_req', null, [
                                'id' => 'ntaconcepto_req',
                                'class' => 'form-control required',
                                'maxlength' => '225',
                                'rows' => 3,
                                'disabled',
                            ]) !!}

                        </div>
                        <div class="col-md-12">
                            <label for="">Evaluado por: </label>
                            <i id="lbldocevname"></i>
                        </div>
                    </div>


                </div>
            </div>
        @endif
    @endslot
@endcomponent
<!-- /modal -->
