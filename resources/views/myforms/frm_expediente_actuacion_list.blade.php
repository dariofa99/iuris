@extends('layouts.dashboard')

@section('titulo_general')
Actuaciones Pendientes
@endsection

@section('titulo_area')
Listar

@endsection




@section('area_forms')

@include('msg.success')
@include('msg.info') 




<br>


<div id='divc'>
<div class="row">
  <div class="col-sm-12">
  <div class="box-body table-responsive no-padding">
    <table id="tbl_users" class="table table-bordered table-striped dataTable" role="grid">
                

                  <thead>
                    <tr>

                      <th>Expediente</th>
                      <th>Consultante</th>
                      @if(!currentUser()->hasRole("estudiante"))
                      <th>Estudiante</th>
                     @endif
                      <th>Tipo Consulta</th>
                      <th>Fecha creación</th>
                      <th>Estado</th>
                      <th>Actuación</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                <tbody>
                
                @foreach($expedientes as $expediente)





                  <tr role="row" >
                    <td>{{ $expediente->expid }}</td>
                    
                    <td>
			<div  @if(currentUser()->hasRole("docente")) class="textcor" @endif>
                    {{  FullName($expediente->nombresolicita, $expediente->apesolicita )  }}
                   </div>
		 </td>
                     @if(!currentUser()->hasRole("estudiante"))
                    <td>
                      {{  FullName($expediente->nombrest, $expediente->apeest )  }}
                    </td>
                     @endif
                   
                    
                    <td>
                         @if  ($expediente->exptipoproce =='1')

                            Simple
                         @else 
                            Compleja
 
                         @endif
                    </td>

                    <td>{{$expediente->expfecha}}</td>

                    <td>
                         @if  ($expediente->expestado =='1')
                            <span class="pull-center badge bg-green">Abierto</span>

                         @elseif ($expediente->expestado =='2')                             
                             <span class="pull-center badge bg-yellow">Solicitud de cierre</span>

                         @elseif ($expediente->expestado =='3')
                             <span class="pull-center badge bg-blue">Cerrado</span>

                         @elseif ($expediente->expestado =='4')
                             <span class="pull-center badge bg-red">Cierre denegado</span>
                         @endif
                    </td>

                    <td>


                       
                

                       




                   
                            <span class="pull-center badge bg-green">Hay pendientes</span>



                 
                        


                   

                    </td>
                    <td>
                   
                      @if($expediente->expestado <>'3')

                             {!! link_to_route('expedientes.edit', $title = 'Editar', $parameters = $expediente->expid, $attributes = ['class'=>'btn btn-primary btn-sm']) !!}
                     @endif


             <!-- Trigger the modal with a button -->
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal{{ $expediente->expid }}">Detalles</button>
               <!-- Modal -->
                   <div id="myModal{{ $expediente->expid }}" class="modal fade" role="dialog">
                      <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Detalles</h4>
                          </div>
                          <div class="modal-body">
                            <p>
                            <div class="box-body table-responsive no-padding">
                             <table class="table table-hover">
                             
                                <tr>
                                  <th>CÓDIGO EXPEDIENTE:</th>
                                   <td>{{  $expediente->expid}}</td>
                                </tr>
                              

                                <tr>
                                  <th>IDENTIFICACIÓN:</th>
                                   <td>{{  $expediente->expidnumber   }}
                                   </td>
                                </tr>

                                <tr>
                                  <th>SOLICITANTE:</th>
                                   <td>{{  FullName($expediente->nombresolicita, $expediente->apesolicita )  }}
                                   </td>
                                </tr>

                                 <tr>
                                  <th>TELÉFONO SOLICITANTE:</th>
                                   <td> {{$expediente->tel1solicita  }}
                                   </td>  
                                </tr>
                           
                                
                                 <tr>
                                  <th>ESTUDIANTE:</th>
                                   <td> {{  FullName($expediente->nombrest, $expediente->apeest )  }}
                                   </td>
                                </tr>





                                <tr>
                                  <th>FECHA CREACIÓN:</th>
                                   <td> {{  $expediente->expfecha  }}
                                   </td>
                                </tr>


                                <tr>
                                  <th>ÚLTIMA ACTUALIZACIÓN:</th>
                                   <td> {{  $expediente->updated_at  }}
                                   </td>
                                </tr>
                            
                           

                            
  
                              </table>
                              </div>



    
                            </p>



                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                          </div>
                        </div>

                      </div>
                    </div>
                  </td>
                  </tr>






                @endforeach  
                </tbody>
                
    </table>
    </div>
    
    {!! $expedientes->render()!!}
  </div>
</div>

<div>
              @stop
