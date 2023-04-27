@component('components.modal')
	
	@slot('trigger')
		myModal_edit_notas
	@endslot

	@slot('title')
		Editando  Notas:
	@endslot

 
    @slot('body')
    @php
    if(!isset($ajax)){

    
    $nota_conocimiento = '0.0';
    $nota_etica = '0.0';
    $nota_aplicacion = '0.0';
    $nota_final = '0.0';
    $nota_conocimientoid = 0;
    $nota_eticaid=0;
    $nota_aplicacionid=0;
    $nota_concepto = 0;
    $nota_conceptoid = 0;
        if ($periodo and $segmento) { 
         
           /*  if(count($tabla->get_has_nota_final())>0){           
                $nota_conocimiento = number_format($tabla->get_has_nota_final()['nota_conocimiento']['nota'],1,'.','.');
                $nota_conocimientoid = $tabla->get_has_nota_final()['nota_conocimiento']['id'];
                $nota_etica = number_format($tabla->get_has_nota_final()['nota_etica']['nota'],1,'.','.');
                $nota_eticaid = $tabla->get_has_nota_final()['nota_etica']['id'];
                $nota_aplicacion = number_format($tabla->get_has_nota_final()['nota_aplicacion']['nota'],1,'.','.');
                $nota_aplicacionid = $tabla->get_has_nota_final()['nota_aplicacion']['id'];
                $nota_concepto = ($tabla->get_has_nota_final()['nota_concepto']['nota']);
                $nota_conceptoid = $tabla->get_has_nota_final()['nota_concepto']['id'];
                $nota_final = number_format($tabla->get_has_nota_final()['nota_final']['nota'],1,'.','.');
            }elseif ($tabla->get_nota_corte('conocimiento')) {
                $nota_conocimiento = number_format($tabla->get_nota_corte('conocimiento')['nota'],1,'.','.');
                 $nota_conocimientoid = $tabla->get_nota_corte('conocimiento')['id'];
                 $nota_etica = number_format($tabla->get_nota_corte('etica')['nota'],1,'.','.');
                 $nota_eticaid = $tabla->get_nota_corte('etica')['id'];
                 $nota_aplicacion = number_format($tabla->get_nota_corte('aplicacion')['nota'],1,'.','.');
                 $nota_aplicacionid = $tabla->get_nota_corte('aplicacion')['id'];
                 $nota_concepto = ($tabla->get_nota_corte('concepto')['nota']);
                 $nota_conceptoid = $tabla->get_nota_corte('concepto')['id'];
                 $nota_final = number_format($tabla->get_nota_corte('final')['nota'],1,'.','.');
        
            } */
         
         
                 
        }
    }
    @endphp
    {!!Form::open(['url'=>'/notas/update','method'=>'POST','id'=>'myform_update_notas'])!!}
<input type="text" style="display:none" value="{{$expediente->id}}" name="exp_id">
    <input type="text" style="display:none" value="" name="origen"  id="origen">
    <input type="text" style="display:none" value="" name="tbl_org_id"  id="tbl_org_id">
    <input type="text" style="display:none" value="" disabled name="tipo_nota_id"  id="tipo_nota_id">
    <div class="row">
            <div class="col-md-12">
            <div class="box-body table-responsive no-padding">
                    <table id="tbl_cierre_cas" class="table">
                            <thead>
                            <tr>
                                <th colspan="2">
                                    <label for="">Evaluado por: <i id="lbldocevname"> </i></label>
                                    
                                  </th>    
                        
                        </tr>
                            <tr>
                                <th>
                                    
                                    @if($periodo and $segmento)
                                    <label id="lbl_periodo">
                                            {{$periodo->prddes_periodo }}
                                    </label>
                                     
                        --
                        <label id="lbl_segmento">
                                {{ $segmento->segnombre }}
                        </label>
                                 
                                     @endif
                                  </th>    
                        <th>Tipo Nota: <label id="lbl_tipo">Parcial</label> </th>
                        </tr>
                        <tr class="fil_nt_co">
                            <th>Nota Conocimiento </th>     
                        <td>
                            <div class="input-group">    
                                            <input type="text" class="form-control notat"   disabled  id="nota_conocimiento" name="nota[]" value="{{ $nota_conocimiento  }}" data-inputmask="'mask': ['9.9']" data-mask>
                                            <input type="text" class="form-control notat" style="display:none"  disabled  name="nota_id[]" id="nota_conocimientoid" value="{{ $nota_conocimientoid  }}">
                            </div>
                        </td>    
                        </tr>
                        <tr class="fil_nt_co">
                            <th>Nota Aplicación </th>    
                             <td>
                                <div class="input-group">    
                                            <input type="text" class="form-control notat"   disabled id="nota_aplicacion" name="nota[]" value="{{ $nota_aplicacion  }}" data-inputmask="'mask': ['9.9']" data-mask>
                                            <input type="text" style="display:none" class="form-control notat"   disabled  id="nota_aplicacionid" name="nota_id[]" value="{{ $nota_aplicacionid  }}">
                                </div>
                            </td> 
                        </tr>
                        <tr>
                            <th>Nota Ética </th>
                                <td>
                                    <div class="input-group">
                                            <input type="text" class="form-control notat"   disabled id="nota_etica" name="nota[]" value="{{ $nota_etica }}" data-inputmask="'mask': ['9.9']" data-mask>
                                            <input type="text" style="display:none" class="form-control notat"   disabled  id="nota_eticaid" name="nota_id[]" value="{{ $nota_eticaid  }}">
                                        </div>
                                </td>		
                        </tr>    
                        <tr>
                            <th>Concepto Notas </th>     
                                <td>                    
                                    <div class="input-group">
                                        <textarea name="nota[]" id="nota_concepto" style="width: 100%" disabled class="form-control notat" >{{ $nota_concepto }}</textarea>
                                        <input type="text" style="display:none" class="form-control notat"   disabled  id="nota_conceptoid" name="nota_id[]" value="{{ $nota_conceptoid  }}">
                                    </div>
                                </td>   
                        </tr> 
                        </table>
                        </div>
            </div>
    </div>
    <div class="row">
            <div class="col-md-6">                    
                   {{--  Promedio Corte: <label id="lbl_nota_gen_caso">{{ $nota_final }}</label> --}}
            </div>
    </div>
    <div class="row" id="btns_edit_notas" style="display:none">
            @if($periodo and $segmento and  (currentUser()->hasRole('docente') || currentUser()->hasRole('amatai')|| currentUser()->hasRole('dirgral') || currentUser()->hasRole('diradmin')))
          
        <div class="col-md-12"> 
            <div class="btn-group"> 
                    <button style="display:none" type="submit" class="btn btn-success" id="btn_update_notas">Actualizar</button>
            </div>

            <div class="btn-group">
                    <a style="display:none" class="btn btn-warning" id="btn_cancelar_notas">X</a>
            </div>
            @if($expediente->expestado_id!='5')
            <div class="btn-group">
             <a class="btn btn-primary" id="btn_cambiar_notas">Cambiar notas</a>
            </div>
@endif
        @if($expediente->expestado_id=='4')
            <div class="btn-group">
             <a href="#" style="display:none" class="btn btn-warning" data-value="" id="btn_tipo_update">Cambiar notas a:</a>
            </div>
        @endif
        @if($expediente->expestado_id!='2' and $expediente->expestado_id!='5')
        <div class="btn-group">
         <a class="btn btn-danger" id="btn_delete_notas">Eliminar todas las notas</a>
        </div>
    @endif
    </div> 
    
      @else
    <div class="col-md-12">
         <label>Para visualizar las notas verifique que haya un periodo y corte activo</label>      
    </div> 
      
        @endif 
    </div>
    
    {!!Form::close()!!}
	@endslot
@endcomponent
<!-- /modal -->

