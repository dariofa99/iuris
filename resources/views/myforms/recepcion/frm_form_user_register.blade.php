{!!Form::open([ 'id'=>'myform_recp_user_create'])!!}
	
  
<input type="hidden" id="usercreated" name="usercreated" value="{{$solicitud->idnumber}}" >
<input type="hidden" id="userupdated" name="userupdated" value="{{$solicitud->idnumber}}" >
<input type="hidden" id='accesofvir' name='accesofvir' value="0">
<input type="hidden" id='institution' name='institution' value="0">
<input type="hidden" id='datecreated' name='datecreated' >
<input type="hidden" id='cursando_id' name='cursando_id' value="1">

{!!Form::hidden('id',  null , ['class' => 'form-control', 'readonly', 'id'=>'id' ]); !!}
<div class="form-group row">
<label>Acceder al sistema con:</label><br>
</div>
<div class="form-group">
   
<label class="radio">
<input type="radio" name="type_register" id="inlineRadio2" value="email" checked>
Correo electrónico
</label>
<label class="radio">
<input type="radio" name="type_register" id="inlineRadio3" value="cc"> No. de documento
</label>	</div>


<div class="form-group row">
    {!!Form::label('Usuario:') !!}
    <input id="email_us" type="email" class="form-control" name="user_name" value="{{ old('user_name') }}" required placeholder="Correo electrónico">
</div>

<div class="form-group row">
    {!!Form::label('Contraseña:') !!}
    <div class="input-group">
        <div class="input-group-addon" style="cursor: pointer;" onmousedown="showPassword('password')" onmouseup="showPassword('password')">
            <span class="fa fa-eye"></span>
        </div>
    {!!Form::password('password', ['class' => 'form-control','id'=>'password']); !!}
    </div>
</div>



<hr>
<div class="row">
<div class="col-md-6 col-md-offset-3">
<button class="btn btn-primary btn-block">Registrar</button>
</div>
</div>
{!!Form::close()!!}