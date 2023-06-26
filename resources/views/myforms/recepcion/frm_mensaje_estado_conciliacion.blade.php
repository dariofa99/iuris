@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-success" style="margin-bottom: 25px;">
                <div class="panel-heading">
                    <b>Atención!</b>
                </div>

                <div class="panel-body">
                   <div class="row">
                    <div class="col-md-2">
                        <img style="width: 450px;height:410px" src="{{asset("/dist/img/revisando.jpg")}}" alt="">
                    </div>
                    <div class="col-md-10">
                        <h3>
                       
                            La solicitud de conciliación esta en revisión. Debes estar atento a tu correo electrónico o número de 
                            teléfono suministrado.   
                            </h3> 
                    </div>
                   </div>
                        
                </div>
            </div>
        </div>
        
        
    </div>
</div>
@endsection
