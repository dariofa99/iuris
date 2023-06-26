

<div class="col-md-6">
<div class="form-group">
    {!!Form::label('Estado Civil') !!}
    <select name="estadocivil_id" id="estadocivil_id" class="form-control required" required>
        <option value="">Seleccione...</option>
        @foreach($estcivil as $tipo)
        <option {{ (isset($user) and $user->estadocivil_id == $tipo->id) ? 'selected':''}} value="{{$tipo->id}}">{{$tipo->ref_nombre}}</option>
        @endforeach
    </select>
</div>
</div>


<div class="col-md-6">
<div class="form-group">
    {!!Form::label('Estrato ') !!}
    <select name="estrato_id" id="estrato_id" class="form-control required" required>
        <option value="">Seleccione...</option>
        @foreach($estrato as $tipo)
        <option {{ (isset($user) and $user->estrato_id == $tipo->id) ? 'selected':''}}  value="{{$tipo->id}}">{{$tipo->ref_nombre}}</option>
        @endforeach
    </select>
</div>
</div>
