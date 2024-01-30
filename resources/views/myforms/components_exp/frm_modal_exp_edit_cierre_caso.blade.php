@component('components.b4.modal_medium')

    @slot('size')
        modal-dialog modal-dialog
    @endslot

    @slot('trigger')
        myModal_exp_edit_cierre_caso
    @endslot

    @slot('title')
        Actualizando Estado
    @endslot


    @slot('body')
        {!! Form::model($expediente, [
            'route' => ['expedientes.update', $expediente->id],
            'id' => 'myform_exp_edit_cierre_caso',
        ]) !!}

        <div class="col-md-3">
            <div class="form-group">
                {!! Form::hidden('expid', null, ['class' => 'form-control', 'readonly', 'id' => 'expid']) !!}
            </div>
        </div>
        @if (currentUser()->hasRole('estudiante'))
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Estado del caso') !!}

                    <select name="new_expestado" placeholder="Seleccione..." id="new_expestado" class="form-control required">
                        <option value="">Seleccione...</option>
                        @foreach ($estados as $estado)
                            @if ($estado->categoria == 'estudiante')
                                <option value="{{ $estado->id }}">Solicitar cierre de caso</option>
                            @endif
                        @endforeach

                    </select>

                    {{-- {!!Form::select('new_expestado',$estados,null, ['placeholder' => 'Selecciona...', 'class' => 'form-control required', 'required' => 'required', 'id'=>'new_expestado' ]); !!} --}}
                </div>
            </div>
        @endif
        @if ($expediente->getDocenteAsig()->idnumber == currentUser()->idnumber or
                currentUser()->hasRole('amatai') or
                currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral'))
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Estado del caso') !!}

                    <select name="new_expestado" placeholder="Seleccione..." id="new_expestado" class="form-control required">
                        <option value="">Seleccione...</option>
                        @foreach ($estados as $estado)
                            @if (currentUser()->hasRole('diradmin') || currentUser()->hasRole('amatai') || currentUser()->hasRole('dirgral'))
                                <option value="{{ $estado->id }}">{{ $estado->nombre_estado }}</option>
                            @else
                                @if ($estado->categoria == 'docente')
                                    <option value="{{ $estado->id }}">{{ $estado->nombre_estado }}</option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                    <small id="lbl_msj_nf" style="display:none;border-bottom: 1px solid #F1948A;padding:3px;margin-top:2px">
                    </small>
                    {{-- {!!Form::select('new_expestado',$estados,null, ['placeholder' => 'Selecciona...', 'class' => 'form-control required', 'required' => 'required', 'id'=>'new_expestado' ]); !!} --}}
                </div>
            </div>
        @endif
        @if (currentUser()->hasRole('estudiante'))
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Motivo') !!}
                    <select name="motivo_estado" placeholder="Seleccione..." id="motivo_estado" class="form-control required">

                        <option value="">Seleccione...</option>
                        @foreach ($motivos_cierre as $motivo)
                            @if ($motivo->categoria == 'estudiante')
                                <option value="{{ $motivo->id }}">{{ $motivo->nombre_motivo }}</option>
                            @endif
                        @endforeach

                    </select>

                </div>
            </div>
        @endif
        @if ($expediente->getDocenteAsig()->idnumber == currentUser()->idnumber or
                currentUser()->hasRole('amatai') or
                currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral'))
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('Motivo') !!}
                    <select name="motivo_estado" placeholder="Seleccione..." id="motivo_estado" class="form-control required">
                        <option value="">Seleccione...</option>
                        @foreach ($motivos_cierre as $motivo)
                            @if (currentUser()->hasRole('diradmin') or currentUser()->hasRole('amatai'))
                                <option value="{{ $motivo->id }}">{{ $motivo->nombre_motivo }}</option>
                            @else
                                @if ($motivo->categoria == 'docente')
                                    <option value="{{ $motivo->id }}">{{ $motivo->nombre_motivo }}</option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('Realice su Comentario: ') !!}
                {!! Form::textarea('comentario', null, [
                    'id' => 'comentario',
                    'class' => 'form-control required',
                    'maxlength' => '4000',
                    'rows' => 2,
                ]) !!}
            </div>
        </div>

        <div class="col-md-12" align="right">
            <div class="form-group">
                <br>
				<button type="submit" class = 'btn btn-primary' id = 'btn_exp_edit_cierre_caso'>
Enviar
				</button>
                {{-- {!! link_to(
                    '#',
                    'Enviar',
                    $attributes = [
                        'id' => 'btn_exp_edit_cierre_caso',
                        'type' => 'button',
                        'class' => 'btn btn-primary'
                    ],
                    $secure = null,
                ) !!} --}}
            </div>
        </div>
        {!! Form::close() !!}
    @endslot
@endcomponent