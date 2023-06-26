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
Señor  {!! $notification->name!!} {!! $notification->lastname!!} se solicita la firma del documento <b>{!! $notification->nombre_reporte!!}</b> en calidad de <b>{!! $notification->calidad!!}</b>.
</td>
</tr>
<tr>
<td>
Clave de acceso: 
</td>
<td>
    {!! $notification->codigo!!}
</td>
</tr> 

<tr>
    <td>
    Enlace firma: 
    </td>
    <td>
        <a href="{{url('/firmar/digital/'.$notification->token)}}">Firmar documento electrónicamente</a>
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