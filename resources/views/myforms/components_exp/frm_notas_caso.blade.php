<!--notas-->




@if(count($expediente->get_has_nota_final())<=0) 

<div class="row">
        @if($segmento and $periodo)
        
            @if(count($expediente->get_nota_corte('etica'))>0  and $expediente->get_nota_corte('etica')['id'] == 0 and $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber)
         
            <div class="col-md-3 col-md-offset-9">
           
                @if($segmento->fecha_fin >= date("Y-m-d"))
                    @if($expediente->expestado_id == 4 || ($expediente->expestado_id == 2) )
                    <input type="button" class="btn btn-primary pull-right add_nota_expedientes" value="Asignar Notas Finales" data-toggle="modal" data-target="#myModal_add_nota_final_expedientes" id="1">
                    @elseif(($expediente->expestado_id == 1 || $expediente->expestado_id == 3) and $segmento->act_fc and $expediente->exptipoproce_id != 1)
                    <a class="btn btn-warning pull-right add_nota_expedientes" id="2" data-toggle="modal" data-target="#myModal_add_nota_final_expedientes" data-placement="top" data-original-title="Agregar Nota"><i class="fa  fa-calculator">
                            </i> Asignar Notas Final de Corte</a>                    
                    @endif
                @else
                        <button disabled class="btn btn-warning pull-right">La fecha final de corte esta vencida</button> 

                @endif

            </div> 


            @elseif(count($expediente->get_nota_corte('etica'))>0 and $expediente->get_nota_corte('etica')['id'] != 0 )  
                
           
            <div class="col-md-3 col-md-offset-8">
                    @if($expediente->get_nota_corte('etica')['tipo_id']=='2')
                    <label class="pull-right">El caso ya fue evaluado (Parcial)</label>
                    @elseif($expediente->get_nota_corte('etica')['tipo_id']=='1')
                    <label class="pull-right">El caso ya fue evaluado (Final)</label>
                    @endif
            </div>                 
                <div class="col-md-1">
                        <a style="cursor:pointer" id="btn_edit_nt_exp">Ver Notas</a>
                </div>                      
            @endif          
        @endif

    </div>
    @else
   
    <div class="row">
            <div class="col-md-3 col-md-offset-8">
                <label class="pull-right">El caso ya fue evaluado (Final)</label>
            </div> 
            <div class="col-md-1">
                    <a style="cursor:pointer" id="btn_edit_nt_exp"   >Ver Notas</a>
            </div>
    </div>
    @endif
    
<!--notas-->