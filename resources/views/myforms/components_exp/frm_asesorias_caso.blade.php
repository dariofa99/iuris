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
            @if (count($expediente->asesorias_docente) <= 0)
                <label> No existen comentarios para este caso </label>
            @else
                @foreach ($expediente->asesorias_docente()->where(['estado' => true])->get() as $asesoria)
                    @if (
                        (currentUser()->hasRole('estudiante') && $asesoria->apl_shared) ||
                            (currentUser()->hasRole('dirgral') ||
                                currentUser()->hasRole('diradmin') ||
                                currentUser()->hasRole('docente') ||
                                currentUser()->hasRole('coordprac') ||
                                currentUser()->hasRole('amatai')))
                        <div class="row">

                            <div class="col-md-4">
                                <label>{{ $asesoria->docente->name }} {{ $asesoria->docente->lastname }}: </label>
                            </div>


                            @if (!$readonly and $asesoria->docente->idnumber == Auth::user()->idnumber)
                            <div class="col-md-4"></div>
                                <div class="col-md-4">                     
                                    <div class="tolls float-right ml-2">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-cog" title="Clic para Editar o Eliminar"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                              <a style="cursor: pointer" class="dropdown-item btn_edit_asesoria_caso" data-id="{{ $asesoria->id }}">Editar</a>
                                              <a style="cursor: pointer" class="dropdown-item btn_delete_asesoria_caso" data-id="{{ $asesoria->id }}">Eliminar</a>
                                             
                                            </div>
                                          </div>                                 
                                    </div>
                                    <div class="float-right" style="min-height: 25px;">
                                        <i>Compartir con estudiante: </i>
                                        <i data-id="{{ $asesoria->id }}" data-status="{{$asesoria->apl_shared}}"  class="fa fa-toggle-on {{$asesoria->apl_shared ? 'switch-on':'switch-off'}} chk_change_shared" id="switch_edit{{ $asesoria->id }}"></i>
                                    </div>
                                </div>
                            @elseif(!currentUser()->hasRole('estudiante'))
                                <div class="col-md-8">
                                    <div style="float:right">
                                        @if ($asesoria->apl_shared)
                                            <i style="color: green" id="switch_edit{{ $asesoria->id }}">Compartido</i>
                                        @else
                                            <i style="color: red" id="switch_edit{{ $asesoria->id }}">Sin compartir</i>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="cont-text">
                                    {!! Form::textarea('asesorias_docente', $asesoria->comentario, [
                                        'class' => 'form-control textarea-asesorias-docente',
                                        'readonly',
                                    ]) !!}
                                </div>
                                <div class="cont-fecha">
                                    <i> {{ $asesoria->created_at }}</i>
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
    @if (!$readonly and (currentUser()->hasRole('docente') 
    || currentUser()->hasRole('amatai') ||
     currentUser()->hasRole('diradmin')))
        <div class="col-md-6">
            <hr>

            <input type="button" class="btn btn-success btn-sm" value="Agregar Asesoría" data-toggle="modal"
                data-target="#myModal_add_asesoria_docente">
        </div>
    @endif
</div>
<!--comentarios-->
