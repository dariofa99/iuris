@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    {!! Form::open(['route'=>'mail.store']) !!}
                    <div class="row">
                        <div class="col-md-6">
                        <input type="text" name="nombre">
                        <br>
                        <input type="text" name="correo">
                        
                        <textarea name="comentario" id="" cols="30" rows="10"></textarea>
                        </div>       
                    </div> 
                    <div class="row">
                        <div class="col-md-5">
                            <input type="submit" value="Enviar">
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
