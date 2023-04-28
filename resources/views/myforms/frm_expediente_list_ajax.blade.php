 
 <div class="box-body table-responsive no-padding">
 <table id="tbl_users" class="table table-bordered table-striped dataTable" role="grid">
                

                  <thead>
                    <tr>

                      <th>Expediente</th>
                      @if(!currentUser()->hasRole("solicitante"))
                      <th>Consultante</th>
                      @endif
                      @if(!currentUser()->hasRole("estudiante"))
                      <th>Estudiante</th>
                     @endif
                      <th>Tipo Consulta</th>
                      <th>Fecha</th>
                      <th>Estado</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                <tbody>
                
                @foreach($expedientes as $expediente)
                  <tr role="row" > 
                    <td>{{ $expediente->expid }}</td>
                    @if(!currentUser()->hasRole("solicitante"))
                    <td>
                    <div @if(currentUser()->hasRole("docente"))   class="textcor"  @endif >
                    {{  FullName($expediente->solicitante->name, $expediente->solicitante->lastname )  }}
                    </div>
                    </td>
                    @endif
                     @if(!currentUser()->hasRole("estudiante"))
                    <td>
                      {{  FullName($expediente->estudiante->name, $expediente->estudiante->lastname )  }}
                    </td>
                     @endif
                   
                     
                    <td>
                         @if  ($expediente->exptipoproce_id =='1')  
                               Asesoría
                         @elseif($expediente->exptipoproce_id =='2')
                               Seguimiento
                          @else
                               Defensa de Oficio 
                         @endif 
                    </td>

                    <td>
                    @if  ($expediente->exptipoproce_id =='1' and ($expediente->expestado_id !='2' ) and !currentUser()->hasRole("solicitante") )
                    <label class="pull-center badge-colors dis-block" style="border-radius:8px;border: 2px solid {{$expediente->getDaysOrColorForClose('color')}}; color : {{$expediente->getDaysOrColorForClose('color')}}">
                    {{$expediente->getDaysOrColorForClose('mensaje')}}  
                    </label>
                                
                    @else         
                                                     {{  \Carbon\Carbon::parse($expediente->getAsignacion()->fecha_asig)->diffForHumans() }}
                             
                         @endif 
                   
                  
                    
                    
                    </td>
                    <td>
                  
                             
                        
                     
                          @if  ($expediente->expestado_id =='1')
                             <span class="pull-center badge bg-green dis-block">
                            @if  ($expediente->exptipoproce_id !='1')
                            @php $circle=$expediente->getActuacions($expediente->expid); @endphp
                              <div class="{{ $circle[0] }}">
                              {{$circle[1]}}
                              </div> 
                           @endif
                           @if(!currentUser()->hasRole("solicitante"))
                            {{$expediente->estado->nombre_estado }}
                          @else
                            En proceso
                          @endif
                            </span>

                         @elseif ($expediente->expestado_id =='4')   

                             <span class="pull-center badge bg-yellow dis-block">
                             @if  ($expediente->exptipoproce_id !='1')
                             @php $circle=$expediente->getActuacions($expediente->expid); @endphp
                              <div class="{{ $circle[0] }}">
                              {{ $circle[1] }}
                              </div>
                           @endif
                           @if(!currentUser()->hasRole("solicitante"))
                           {{$expediente->estado->nombre_estado }}
                           @else
                            En revisión
                          @endif
                             </span>

                         @elseif ($expediente->expestado_id =='2')
                             <span class="pull-center badge bg-blue dis-block">
                              <div>
                           </div>
                           @if(!currentUser()->hasRole("solicitante"))
                              {{$expediente->estado->nombre_estado }}
                          @else
                            Revisado
                          @endif
                          </span>
                         @elseif ($expediente->expestado_id =='3')
                             <span class="pull-center badge bg-red dis-block">
                             @if  ($expediente->exptipoproce_id !='1')
                             @php $circle=$expediente->getActuacions($expediente->expid); @endphp
                              <div class="{{ $circle[0] }}">
                              {{ $circle[1] }}
                              </div>
                              @endif
                          
                           @if(!currentUser()->hasRole("solicitante"))
                           {{$expediente->estado->nombre_estado }}
                           @else
                            En proceso
                          @endif
                           </span>
                          @elseif ($expediente->expestado_id =='5')
                             <span class="pull-center badge bg-blue dis-block">
                              <div>
                           </div>
                           @if(!currentUser()->hasRole("solicitante"))
                           {{$expediente->estado->nombre_estado }}
                           @else
                            Sin revisión
                          @endif
                           </span>


                         @endif 

            
                      
       
                    </td>
                    <td>
                   @if(!currentUser()->hasRole("secretaria") and !currentUser()->hasRole("solicitante"))

                    @if(currentUser()->hasRole("estudiante") and ($expediente->expestado_id =='1'))
                        @if($expediente->exptipoproce_id=='3') 
                            {!! link_to_route('oficio.edit', $title = 'Editar', $parameters = $expediente->expid, $attributes = ['class'=>'btn btn-primary btn-sm btn-edit-le']) !!}

                            @else

                            {!! link_to_route('expedientes.edit', $title = 'Editar', $parameters = $expediente->expid, $attributes = ['class'=>'btn btn-primary btn-sm btn-edit-le']) !!}
                        @endif
                        

                     @elseif(!currentUser()->hasRole("estudiante") and ($expediente->expestado_id =='1' or $expediente->expestado_id =='4') or $expediente->expestado_id =='3')

                       @if($expediente->exptipoproce_id=='3') 
                            {!! link_to_route('oficio.edit', $title = 'Editar', $parameters = $expediente->expid, $attributes = ['class'=>'btn btn-primary btn-sm btn-edit-le']) !!}
                            
                            @else

                            {!! link_to_route('expedientes.edit', $title = 'Editar', $parameters = $expediente->expid, $attributes = ['class'=>'btn btn-primary btn-sm btn-edit-le']) !!}
                        @endif

                       @else

                       @if($expediente->exptipoproce_id=='3') 
                            {!! link_to_route('oficio.show', $title = 'Ver', $parameters = $expediente->expid, $attributes = ['class'=>'btn btn-primary btn-sm btn-edit-le']) !!}
                            
                            @else

                            {!! link_to_route('expedientes.show', $title = 'Ver', $parameters = $expediente->expid, $attributes = ['class'=>'btn btn-primary btn-sm btn-edit-le']) !!}
                        @endif

                        

                    @endif              
                             
                      			@else

                      				 @if($expediente->exptipoproce_id=='3') 
                            {!! link_to_route('oficio.show', $title = 'Ver', $parameters = $expediente->expid, $attributes = ['class'=>'btn btn-primary btn-sm btn-edit-le']) !!}
                            
                            @else

                            {!! link_to_route('expedientes.show', $title = 'Ver', $parameters = $expediente->expid, $attributes = ['class'=>'btn btn-primary btn-sm btn-edit-le']) !!}
                        @endif

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
                              @if(!currentUser()->hasRole("solicitante"))
                                <tr>
                                  <th>DOCENTE:</th>
                                   <td>{{$expediente->getDocenteAsig()->name}} {{$expediente->getDocenteAsig()->lastname}}</td> 
                                </tr>
                                <tr>
                                  <th>CÓDIGO EXPEDIENTE:</th>
                                   <td>{{  $expediente->expid}}</td>
                                </tr>
                              
                                  <tr>
                                  <th>RAMA DERECHO:</th>
                                   <td>
                                    {{  $expediente->rama_derecho->ramadernombre  }}
                                  </td>
                                </tr>

                                <tr>
                                  <th>IDENTIFICACIÓN SOLICITANTE:</th>
                                   <td>{{  $expediente->solicitante->idnumber   }}
                                   </td>
                                </tr>

                                <tr>
                                  <th>SOLICITANTE:</th>
                                   <td>{{  FullName($expediente->solicitante->name, $expediente->solicitante->lastname )  }}
                                   </td>
                                </tr>

                                 <tr>
                                  <th>TELÉFONO SOLICITANTE:</th>
                                   <td> {{  $expediente->solicitante->tel1  }}   @if  ($expediente->solicitante->tel2 !='')  -  {{  $expediente->solicitante->tel2  }} @endif
                                   </td>
                                </tr>
                           
                                <tr>
                                  <th>DIRECCIÓN SOLICITANTE:</th>
                                   <td> {{  $expediente->solicitante->address }}
                                   </td>
                                </tr>
                              @endif
                                <tr>
                                  <th>FECHA CREACIÓN EXPEDIENTE:</th>
                                   <td> {{  $expediente->expfecha  }}
                                   </td>
                                </tr>


                                <tr>
                                  <th>ÚLTIMA ACTUALIZACIÓN:</th>
                                   <td> {{  $expediente->updated_at  }}
                                   </td>
                                </tr>
                                                                <tr>
                                  <th></th>
                                   <td>
                                   </td>
                                </tr>
                                @if(!currentUser()->hasRole("estudiante"))
                                <tr>
                                  
                                   <td colspan="2" style="text-align: center;">
                                    <img src="{{ is_file(public_path('thumbnails/'.$expediente->estudiante->image)) ? asset('thumbnails/'.$expediente->estudiante->image ) : asset('thumbnails/default.jpg' )}}" style="border-radius: 10px;-webkit-box-shadow: -9px 10px 9px 0px rgba(0,0,0,0.75);-moz-box-shadow: -9px 10px 9px 0px rgba(0,0,0,0.75);box-shadow: -9px 10px 9px 0px rgba(0,0,0,0.75); width: 180px;"  alt="User Image">
                                  </td>
                                </tr>
                                @endif
                                @if(!currentUser()->hasRole("solicitante"))
                                 <tr>
                                  <th>IDENTIFICACIÓN ESTUDIANTE:</th>
                                   <td> {{ $expediente->estudiante->idnumber }}
                                   </td>
                                </tr>
                                @endif
                                 <tr>
                                  <th>ESTUDIANTE:</th>
                                   <td> {{  FullName($expediente->estudiante->name, $expediente->estudiante->lastname )  }}
                                   </td>
                                </tr>
                                <tr>
                                  <th>CURSO:</th>
                                   <td> {{  $expediente->estudiante->curso->ref_nombre  }}
                                   </td>
                                </tr>
                              @if(!currentUser()->hasRole("solicitante"))
                                <tr>
                                  <th>TELÉFONO ESTUDIANTE:</th>
                                   <td>{{  $expediente->estudiante->tel1  }}   @if  ($expediente->estudiante->tel2 !='')  -  {{  $expediente->estudiante->tel2  }} @endif
                                   </td>
                                </tr>
                                <tr>
                                  <th>DIRECCIÓN ESTUDIANTE:</th>
                                   <td> {{  $expediente->estudiante->address  }}
                                   </td>
                                </tr>
                             
                                <tr>
                                  <th>TURNO:</th> 
                                   <td>
                      @if($expediente->estudiante->turno)             
                        <a href="/horarios" style="cursor: pointer;" title="Ir a horarios" >
                          <label style="cursor: pointer;" class="label {{ ($expediente->getColorTurno($expediente->estudiante->turno->color->ref_value)) }}">

 {{ $expediente->getMjs($expediente->estudiante->turno->horario->ref_value) }} 
                         
                        </label> 
                        </a>
                        @endif
                                    
                                        
                                    
                                   </td>
                                </tr>
                                @endif
                                @if(currentUser()->hasRole("solicitante"))
                                <tr>
                                  <th>DUDAS ADICIONALES DE SU CASO SOLO:</th> 
                                   <td>
                                    @if($expediente->estudiante->turno)             
                                      {{ $expediente->getMjs($expediente->estudiante->turno->horario->ref_value) }} 
                                      @endif
                               
                                   </td>
                                </tr>
                              @endif
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
    
    {{ $expedientes->appends(request()->query())->links() }}

    <script>
      (function(){
        element = document.getElementById('badgeCount');
       valor = convertirMoneda({{$numEx}});
        element.innerHTML = valor;
      })();

      function convertirMoneda(valor){
          number = parseInt(valor);
            number =  number.toLocaleString('es-ES');
            return number;
        }
    
    </script>