<div class="row" style="margin-top: 5px">
    <div class="col-md-12">
        <div class="form-group item_value">
            <small data-table="users" data-summernote="{{$mySummernote}}"    data-short_name="name" class="item_con" user-type="{{$tipo_usuario_id}}" data-name="nombre_{{$parte}}">Nombres [{{$parte}}]</small> 
           
        </div>
        
    </div> 
    
    <div class="col-md-12">
        <div class="form-group item_value">
            <small data-table="users" data-summernote="{{$mySummernote}}"    data-short_name="lastname" class="item_con" user-type="{{$tipo_usuario_id}}" data-name="apellido_{{$parte}}">Apellidos [{{$parte}}]</small> 
        </div>
    </div>  

<div class="col-md-12">
    <div class="form-group item_value">
        <small data-table="users"  data-summernote="{{$mySummernote}}"  data-short_name="idnumber" class="item_con" user-type="{{$tipo_usuario_id}}" data-name="identificacion_{{$parte}}">No. Identificación [{{$parte}}]</small> 
            </div>
</div> 

<div class="col-md-12">
    <div class="form-group item_value">
        <small data-table="users" data-summernote="{{$mySummernote}}"   data-short_name="tel1" class="item_con" user-type="{{$tipo_usuario_id}}" data-name="telefono_{{$parte}}">Teléfono [{{$parte}}]</small> 
          

    </div>
</div>

<div class="col-md-12">
    <div class="form-group item_value">
        <small data-table="users" data-summernote="{{$mySummernote}}"   data-short_name="email" class="item_con" user-type="{{$tipo_usuario_id}}" data-name="email_{{$parte}}">Correo electrónico [{{$parte}}]</small> 
    </div>
</div>

@include('myforms.conciliaciones.componentes.labels')


@if(count($conciliacion->getUserQueForm($parte,'socio_economica'))>0)
<div class="col-md-12">
    <div class="form-group item_value">
       <label>Info. Socioeconómica</label>
    </div>
</div>

<div class="col-md-12">
    <div class="form-group item_value">
        <small  data-table="users"  data-summernote="{{$mySummernote}}"  data-short_name="estado_civil" class="item_con" user-type="{{$tipo_usuario_id}}" data-name="estado_civil_{{$parte}}">Estado civil [{{$parte}}]</small> 
    </div>
</div>

@include('myforms.conciliaciones.componentes.values',[
    'labels'=>$conciliacion->getUserQueForm($parte,'socio_economica')
])

@endif

</div>