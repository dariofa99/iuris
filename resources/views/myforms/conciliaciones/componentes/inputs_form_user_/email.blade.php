
<div class="col-md-6">
    <div class="form-group">
        {!!Form::label('Correo Principal: ') !!}
        {!!Form::text('email', (isset($user)) ? $user->email:'' , ['class' => 'form-control required', 'id'=>'email','required']); !!}
    </div>
</div>
