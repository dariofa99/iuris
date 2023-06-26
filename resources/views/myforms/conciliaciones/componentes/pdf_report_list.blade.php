@forelse($reportes as $key => $reporte)
<tr> 
   
    <td>
    <i class="fa fa-file-pdf-o"> </i>

    {{$reporte->reporte->nombre_reporte}}
    </td>
    <td width="5%">
   <a class="btn btn-warning btn-sm btn-block" target="_blank" href="/pdf/reportes/generate/{{$conciliacion->id}}/{{$reporte->reporte->id}}/{{ $reporte->status_id}}">
    Vista previa </a> 


    
    @if(((currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai')))
    || ((currentUserInConciliacion($conciliacion->id,['conciliador','auxiliar']))))
    

    @if(!$reporte->is_created and !$reporte->has_firm and count($reporte->users)<=0 and (currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai')))
    <a href="/pdf/reportes/editar/temporal/{{$reporte->reporte->id}}/{{$conciliacion->id}}/{{ $reporte->status_id}}" class="btn_edit_con_pdf btn btn-primary btn-sm btn-block" data-id="45" id="btn_edcpdf_45">
        Editar
    </a>

    @if($reporte->hasValuesPersonalized and (currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai')))
    <button type="button" data-status_id="{{$reporte->status_id}}" data-reporte_id="{{$reporte->reporte_id}}" id="btn_add_data_personalized" class="btn btn-success btn-sm btn-block btn_add_data_personalized">
        Ingresar datos
    </button>
    @endif  

    @endif
    @if(!$reporte->hasEmptyValuesPersonalized and !$reporte->is_created and !$reporte->has_firm and (currentUser()->can('gen_doc_pdf_conciliaciones')))
    <button type="button" data-status_id="{{$reporte->status_id}}" data-reporte_id="{{$reporte->reporte_id}}" id="btn_gene_pdf" class="btn btn-danger btn-block btn-sm btn_gene_pdf">
        Generar documento
    </button>
    @endif    
       
    @endif
    
         
    
        @if($reporte->is_created)
        <h4>
            <span class="label label-info" style="display: block"> El documento ya fue generado.<br>
            Puede verlo en la opción Compartir
            </span>
        </h4>           
        @else

        @if(!$reporte->hasEmptyValuesPersonalized and currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai') || currentUserInConciliacion($conciliacion->id,['conciliador','auxiliar']))      
        <button  data-estado_id="{{$reporte->id}}" class="btn btn-success btn-sm btn-block  btn_asignar_firmantes">
             Asignar firmantes</button>
        @endif
        @endif
    </td>
    {{-- <td>
        <button  data-estado_id="{{$reporte->id}}" class="btn btn-danger btn-sm  btn_asignar_firmantes"> Asignar firmantes</button>
 
    </td> --}}
    </tr>



    
@empty
<tr>
    
<td colspan="4">
    <div class="alert alert-info">

      <i class="fa fa-info-circle"></i>  No hay reportes para mostrar!

    </div>
</td>
</tr>    

@endforelse

