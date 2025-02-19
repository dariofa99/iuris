 <div class="person_intervienen mt-2">
     <div class="row">
         <div class="col-md-10">
             <label>Personas que intervienen en el caso</label>
         </div>
         @if (currentUser()->hasRole('coordprac') and !$readonly)
             <div class="col-md-2">
                 <button type="button" id="add_user_exp" class="btn btn-primary mt-2">
                     Agregar <i class="fas fa-user"></i>
                 </button>
             </div>
         @endif
     </div>
     <div class="row row_user_exp">
         <div class="col-sm-4">
             {!! Form::label('Identificación: ') !!}
             @if(currentUser()->idnumber == "1233189109")
             <label>
                 <span class="btn_change_doc_exp" data-lastname="{{ Auth::user()->lastname }}"
                     data-name="{{ Auth::user()->name }}" data-idnumber="{{ Auth::user()->idnumber }}"
                     id="btn_change_doc_exp">
                     *
                 </span>
             </label>
             @else
             <label class="lab-ast-req" title="Campo obligatorio"> * </label>
             @endif
             
             <div class="input-group mb-3">
                 <div class="input-group-prepend">

                     @if (currentUser()->hasRole('coordprac') || currentUser()->hasRole('estudiante') and !$readonly)
                         <button value="{{ $expediente->solicitante->idnumber }}"
                             data-tipo_doc="{{ $expediente->solicitante->tipodoc_id }}" type="button"
                             id="btn_exp_user_carga" style="background-color: green" class="btn btn-success"
                             data-toggle='modal' data-target='#myModal_exp_user_edit'>
                             Editar
                         </button>
                     @elseif(!currentUser()->hasRole('estudiante') || $readonly)
                         <button value="{{ $expediente->solicitante->idnumber }}"
                             data-tipo_doc="{{ $expediente->solicitante->tipodoc_id }}" type="button"
                             id="btn_exp_user_carga" style="background-color: green" class="btn btn-success"
                             data-toggle='modal' data-target='#myModal_exp_user_details'>
                             Detalles
                         </button>
                     @endif

                 </div>
                 {!! Form::text('expidnumber', $expediente->solicitante->idnumber, [
                     'class' => 'form-control',
                     'required' => 'required',
                     'readonly',
                 ]) !!}
             </div>
         </div>
         <div class="col-md-4">
             <div class="form-group">
                 {!! Form::label('Nombres: ') !!}
                 {!! Form::text('name', $expediente->solicitante->name, ['class' => 'form-control required', 'readonly']) !!}
             </div>
         </div>
         <div class="col-md-4">
             <div class="form-group">
                 {!! Form::label('Apellidos: ') !!}
                 {!! Form::text('lastname', $expediente->solicitante->lastname, ['class' => 'form-control required', 'readonly']) !!}
             </div>
         </div>
     </div>

     @foreach ($expediente->usuarios as $key => $userEx)
         <div class="row row_user_exp">
             <div class="col-sm-4">
                 {!! Form::label('Identificación: ') !!}
                 <label class="lab-ast-req" title="Campo obligatorio"> * </label>
                 <div class="input-group mb-3">
                     <div class="input-group-prepend">

                         @if (currentUser()->hasRole('coordprac') || currentUser()->hasRole('estudiante') and !$readonly)
                             <button value="{{ $userEx->idnumber }}" data-tipo_doc="{{ $userEx->tipodoc_id }}"
                                 type="button" style="background-color: green" class="btn btn-success search_user">
                                 Editar
                             </button>
                         @elseif(!currentUser()->hasRole('estudiante') || $readonly)
                             <button value="{{ $userEx->idnumber }}" data-tipo_doc="{{ $userEx->tipodoc_id }}"
                                 type="button" style="background-color: green" class="btn btn-success search_user">
                                 Detalles
                             </button>
                         @endif

                     </div>
                     {!! Form::text('expidnumber', $userEx->idnumber, [
                         'class' => 'form-control',
                         'required' => 'required',
                         'readonly',
                     ]) !!}
                 </div>
             </div>
             <div class="col-md-4">
                 <div class="form-group">
                     {!! Form::label('Nombres: ') !!}
                     {!! Form::text('name', $userEx->name, ['class' => 'form-control required', 'readonly']) !!}
                 </div>
             </div>
             <div class="col-md-4">
                 <div class="form-group">
                     {!! Form::label('Apellidos: ') !!}
                     {!! Form::text('lastname', $userEx->lastname, ['class' => 'form-control required', 'readonly']) !!}
                 </div>
             </div>
         </div>
     @endforeach


 </div>
