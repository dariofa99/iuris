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
<td style="font-size: 20px; color: #f00;">
¡AVISO IMPORTANTE!: El anterior correo con las citas asignadas tiene errores, las citas válidas a tener en cuenta son las siguientes:
</td>
</tr>
<tr>
<td>
{!! $notification->motivo !!}
</td>
</tr>
</tbody>
</table>
<hr>
<i> AMATAI Ingeniería Informática SAS. </i>
   {{--  <p> <strong>Fecha</strong> 
     {!! \Carbon\Carbon::parse($fecha)->diffForHumans()!!}</p>
   <p> <strong>Hora</strong> {!!$hora!!}</p>   
   <p> <strong>Motivo</strong> {!!$motivo!!}</p> --}}
 
</body>
</html>