<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
    table  {
width:100%;

}

table thead {
background:#CFFCF4;
text-align:center;

}

    </style>
</head>
<body>

<table>
<thead>
<th colspan="2">
IURIS
</th>
</thead>
<tbody>

<tr>
<td width="10%">
Motivo:
</td>
<td>
@if($notification->is_user)
Usted esta recibiendo este correo electrónico porque se ha generado un documento previamente firmado. Para visualizarlo de clic en el enlace e ingrese la siguiente clave.
@else
Usted esta recibiendo este correo electrónico porque se ha solicitado la Consulta de documentos. Para visualizarlo de clic en el enlace y utilice la siguiente clave.

@endif

</td>
</tr>
<tr>
    <td>
    Clave de acceso 
    </td>
    <td>
        {{$notification->clave}}
    </td>
    </tr> 
<tr>
    <td>
    Enlace para verificar: 
    </td>
    <td>
        <a href="{{url('/firmar/pdf/verify/'.$notification->token)}}">Consultar</a>
    </td>
    </tr> 

</tbody>
</table>
<hr>
<i> Amatai, Ingeniería Informática SAS. </i>
   {{--  <p> <strong>Fecha</strong> 
     {!! \Carbon\Carbon::parse($fecha)->diffForHumans()!!}</p>
   <p> <strong>Hora</strong> {!!$hora!!}</p>   
   <p> <strong>Motivo</strong> {!!$motivo!!}</p> --}}
 
</body>
</html>