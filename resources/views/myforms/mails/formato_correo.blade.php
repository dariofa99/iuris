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
.btn-revisar{
  background: #249b2e;
  border: 1px solid #249b2e;
  height: 40px;
  padding: 5px;
  border-radius: 4px;
  margin: 3px;
  color: white !important;
  text-decoration:none;
  
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

<td colspan="2">
{!! $mensaje !!}
</td>
</tr>

<tr>
  <td colspan="2">
    <p>Para ver la solicitud de clic en el siguente botón
        <a class="btn-revisar" href="{{$url}}">Ver conciliación</a></p>
   
  </td> 
</tr>

<tr>
<td width="5%">
Envió:
</td>
<td>
{{auth()->user()->name}} {{auth()->user()->lastname}}
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