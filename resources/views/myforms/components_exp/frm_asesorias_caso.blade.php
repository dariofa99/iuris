<div class="row">
    <div class="col-md-12">
        <label>Comentarios:</label>
    </div>
</div>

<!--comentarios--> 
<div class="row">
    <div class="col-md-12">
        <!--cont-comentarios-->
        <div class="cont-comentarios">
                @if(count($expediente->asesorias_docente) <=0)
               <label> No existen comentarios para este caso </label>
                @else
                @foreach($expediente->asesorias_docente()->where(['estado'=>true])->get() as $asesoria)
                @if((currentUser()->hasRole("estudiante") && $asesoria->apl_shared) || (currentUser()->hasRole("diradmin") || currentUser()->hasRole("docente") || currentUser()->hasRole("coordprac") || currentUser()->hasRole("amatai")))
              
                <div class="row">   
    
                    <div class="col-md-2">
                            <label>{{ $asesoria->docente->name }}: </label>
                    </div> 

 
                 @if($asesoria->docente->idnumber == Auth::user()->idnumber)                       
                            <div class="col-md-3 col-md-offset-6">
                                    <div class="pull-right" style="min-height: 25px;">	
                                            <i>Compartir con estudiante:  </i>								
                                                    @if($asesoria->apl_shared)									
                                                    <i class="fa fa-toggle-on switch-on" id="switch_edit{{$asesoria->id}}" onclick="updateAplShared({{$asesoria->id}})"></i> 
                                                    @else									
                                                    <i class="fa fa-toggle-on switch-off" id="switch_edit{{$asesoria->id}}" onclick="updateAplShared({{$asesoria->id}})"></i>
                                                    @endif
                                    </div>
                            </div>
                            <div class="col-md-1">
                                    <div class="tolls pull-right">
                                            <div class="dropdown dropdown-menu-edit">
                                              <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-cog" title="Clic para Editar o Eliminar"></i>
                                               </button>
                                              <ul class="dropdown-menu pull-right" aria-labelledby="dLabel" >
                                                <li><a onclick="editAsesoria({{ $asesoria->id }})">Editar</a></li>
                                                <li><a onclick="deleteAsesoria({{ $asesoria->id }})">Eliminar</a></li>
                                              </ul>
                                            </div>
                                    </div>
                            </div>                        
                        @elseif(!currentUser()->hasRole("estudiante"))
                        <div class="col-md-2 col-md-offset-8">
                                <div class="pull-right">
                                        @if($asesoria->apl_shared)																
                                            <i style="color: green"  id="switch_edit{{$asesoria->id}}")>Compartido</i>
                                            @else									
                                            <i style="color: red" id="switch_edit{{$asesoria->id}}">Sin compartir</i>
                                        @endif
                                </div>
                        </div>
                        @endif
                    </div> 
                      <div class="row">
                        <div class="col-md-12">
                                <div class="cont-text">                                     
                                    {!!Form::textarea('asesorias_docente',  $asesoria->comentario , ['class' => 'form-control textarea-asesorias-docente','readonly' ]); !!}
                                </div>                                        
                                <div class="cont-fecha">
                                    <i>	{{ $asesoria->created_at }}</i>
                                </div>
                        </div>
                    </div>
                    

@endif
                @endforeach        

            @endif
             
                     
            </div>
             <!--cont-comentarios-->
    </div>    
</div>
<div class="row">
        @if((currentUser()->hasRole("docente") || currentUser()->hasRole("amatai") || currentUser()->hasRole("diradmin")))					
        <div class="col-md-6">
            <hr>

        <input type="button" class="btn btn-success" value="Agregar Asesoría" data-toggle="modal" data-target="#myModal_add_asesoria_docente" >
        </div>						
@include('myforms.frm_add_asesoria_docente')
@include('myforms.frm_update_asesoria_docente')	
@endif
</div>
<!--comentarios-->