@if(!isset($conciliacion))
    <div class="col-md-12">
        <label for="active">Activo:</label><br>
        <input type="checkbox" name="active" id="active_us" value="1">
    </div>
    @endif
    <div class="col-md-6">
        <div class="form-group">
            {!!Form::label('Tipo de documento') !!}
            <select {{(isset($user) and Request::has('is_edit')) ? "disabled":''}}  name="tipodoc_id" id="tipodoc_id" class="form-control required" required>
                <option value="">Seleccione...</option>
                @foreach($tipodoc as $doc)
                <option {{((isset($user) and $user->tipodoc_id == $doc->id) || Request::get('tipodoc_id')  == $doc->id ) ? 'selected':''}} value="{{$doc->id}}">{{$doc->ref_nombre}}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!!Form::label('Identificación: ') !!}
            {!!Form::text('idnumber', (isset($user)) ? $user->idnumber: Request::get('idnumber') , ['class' => 'form-control onlynumber findUser', 'id'=>'idnumber',
            'data-toggle'=>'tooltip', 'title'=>'Solo números', 'required',(isset($user) and Request::has('is_edit')) ? "disabled":'']) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!!Form::label('Nombres: ') !!}
            {!!Form::text('name', (isset($user)) ? $user->name:''  , ['class' => 'form-control required', 'id'=>'name_us','required']); !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!!Form::label('Apellidos: ') !!}
            {!!Form::text('lastname', (isset($user)) ? $user->lastname:''  , ['class' => 'form-control required' , 'id'=>'lastname_us','required']); !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!!Form::label('Tel. Celular: ') !!}
            {!!Form::text('tel1', (isset($user)) ? $user->tel1:'' , ['class' => 'form-control', 'id'=>'tel1','required']); !!}
        </div>
    </div>