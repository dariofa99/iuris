

@include('myforms.conciliaciones.componentes.values',[
    'labels'=>$conciliacion->getUserQueForm($parte,'datos_personales')
])
@include('myforms.conciliaciones.componentes.values',[
    'labels'=>$conciliacion->getUserQueForm($parte,'sin_seccion')
])
@include('myforms.conciliaciones.componentes.values',[
    'labels'=>$conciliacion->getUserQueForm($parte,'discapacidad')
])