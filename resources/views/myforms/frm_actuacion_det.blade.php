@component('components.modal_dynamic')


	@slot('size')
		modal-dialog modal-lg
	@endslot
	
	@slot('trigger')
		myModal_act_det
	@endslot

	@slot('title')
		Detalle
	@endslot

 
	@slot('body')

<div class="box-body table-responsive no-padding">
	<table class="table table-hover" id="tbl_actuacion_det">
                             
                                <tr>
                                  <th>CÓDIGO EXPEDIENTE:</th>
                                   <td>{{  $expediente->expid}}</td>
                                </tr>
                              

                                <tr>
                                  <th>IDENTIFICACIÓN:</th>
                                   <td>{{  $expediente->solicitante->idnumber }}
                                   </td>
                                </tr>

                                <tr>
                                  <th>SOLICITANTE:</th>
                                   <td>{{  FullName($expediente->solicitante->name, $expediente->solicitante->lastname )  }}
                                   </td>
                                </tr>
                           
                                
                                 <tr>
                                  <th>ESTUDIANTE:</th>
                                   <td> {{  FullName($expediente->estudiante->name, $expediente->estudiante->lastname )  }}
                                   </td>
                                </tr>



                                <tr>
                                  <th>ACTUACIÓN:</th>
                                   <td> 
                                   </td>
                                </tr>


                                <tr>
                                  <th>DESCRIPCIÓN:</th>
                                   <td> 
                                   </td>
                                </tr>


                                <tr>
                                  <th>RECOMENDACIÓN DOCENTE:</th>
                                   <td> 
                                   </td>
                                </tr>                                
                           

                            
  
                              </table>
                              </div>

	@endslot
@endcomponent
<!-- /modal -->










