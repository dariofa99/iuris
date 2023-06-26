<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mail</title>
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

    <div class="row" style="background-color: #222d32; opacity: 1; margin-right: 0px;" >
        <div class="col-md-3 image d-none d-sm-inline-block" style="padding-left: 50px;">
            <img src="{{ asset('dist/img/udenarbl.png') }}" class="elevation-2" style="width: 250px;margin:10px;" alt="User Image">
        </div>
        <div class="col-md-6 " style="padding-top: 25px; text-align: center; font-size: 17px;">
            <p style="color:#ffffff;     font-size: 20px; font-weight: 900;"><b>Consultorios Jurídicos y Centro de Conciliación<br>"Eduardo Alvarado Hurtado"</b></p> 
        </div>
    </div>
    
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
@if(isset($url))
<tr>
  <td colspan="2">
    <p>
        <a class="btn-revisar" href="{{$url}}">Ver conciliación</a>
    </p>
   
  </td> 
</tr>
@endif
{{-- <tr>
<td width="5%">
Solicitante:
</td>
<td>
{{auth()->user()->name}} {{auth()->user()->lastname}}
</td>
</tr>  --}}
</tbody>
</table>
<hr>
<i> Amatai, Ingeniería Informática SAS. </i><br>

   {{--  <p> <strong>Fecha</strong> 
     {!! \Carbon\Carbon::parse($fecha)->diffForHumans()!!}</p>
   <p> <strong>Hora</strong> {!!$hora!!}</p>   
   <p> <strong>Motivo</strong> {!!$motivo!!}</p> --}}
 
</body>
</html>