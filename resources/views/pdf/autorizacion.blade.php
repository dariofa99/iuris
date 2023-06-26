
<table style="font-family:arial;font-size:13.333px;width:100%">
<tr>
<td align="center" style="border:1px solid black">
<img src="{{public_path('/img/logoudenar_2.png')}}" width="100" height="100" /></td>

</td>
<td align="center" style="border:1px solid black">
CONSULTORIOS JURIDICOS - CENTRO DE CONCILIACIÓN <br>“EDUARDO ALVARADO HURTADO”<br>
<br>
AUTORIZACIÓN ESTUDIANTIL 

</td>
<td style="border:1px solid black">
<span style="padding:2px;display:block;text-align:left;border-bottom:1px solid black">Código: CJU-PRS-FR-04</span>
<span style="padding:2px;display:block;text-align:left;border-bottom:1px solid black">Página: 1 de 1</span>
<span style="padding:2px;display:block;text-align:left;border-bottom:1px solid black">Versión: 2</span>
<span style="padding:2px;display:block;text-align:left;border-bottom:1px solid black">Vigente a Partir de:
 2011-08-18</span>

</td>
</tr>

</table>
<br>
<div style="padding:0px 50px 0px 50px">


<table>
<tr>
<td align="center" style="font-family:arial;font-size:18.667px">
<b>EL DIRECTOR ADMINISTRATIVO  DE LOS CONSULTORIOS JURIDICOS Y EL CENTRO DE CONCILIACIÓN 
UNIVERSIDAD DE NARIÑO 
<br><br><br>
<span style="font-family:arial;font-size:16px">AUTORIZA</span>
</b>
<br><br>
</td>
</tr>
<tr>
<td align="justify" style="font-family:arial;font-size:16px">
<p>
@if($autorizacion->genero=='f')
A la estudiante 
@else
Al estudiante 
@endif
{{$autorizacion->nombre_estudiante}}, quien se identifica con 
Cédula de Ciudadanía Nro. {{$autorizacion->num_identificacion}} expedida en 
{{$autorizacion->doc_expedicion}}, y carné estudiantil Nro. {{$autorizacion->num_carne}}
 y que se encuentra @if($autorizacion->genero=='f')
registrada
@else
registrado
@endif  en los Consultorios Jurídicos de la Universidad 
 de Nariño, aprobado mediante Resolución 
 Nro. 1808 de 3 de Octubre de 1991 del Honorable Tribunal Superior del Distrito 
 Judicial de Pasto, para que actúe en calidad de {{$autorizacion->calidad_de}}, 
 dentro del proceso: {{$autorizacion->tipo_proceso}}, 
 radicado bajo el No. {{$autorizacion->num_radicado}}, que cursa en el  
 {{$autorizacion->juzgado}}.
</p>
<p>
Esta autorizacion se expide en San Juan de Pasto, a los {{getLettersDays($autorizacion->fecha_autorizado)}}, {{parseLongDate($autorizacion->fecha_autorizado)}} para efectos de que trata el inciso 2º del Artículo 1º de la Ley 583 de 2000.
</p>
<p>
<b>Nota: </b>Favor presentar copia del Acta de Posesión ante el Asistente Administrativo, en caso de procesos penales ante los juzgados y fiscalías. Presentar copia de esta autorizacion con el sello de recibido de la entidad correspondiente.
</p>
</td>
</tr>

</table>
<br><br><br><br>
<table style="width:100%"> 
<tr>
<td align="center" width="50%">
<img src="{{public_path('/img/firma.png')}}" width="160" height="90" />
</td>
<td>
</td>
</tr>
<tr>

<td width="50%" align="center">

DIRECTOR ADMINISTRATIVO
</td>
<td align="center">
ESTUDIANTE
</td>
</tr>
<tr><td></td></tr>
<tr>
<td></td>
<td><p><b style="font-family:arial;font-size:13.333px">VIGILADO Ministerio del interior y Justicia.</b></p></td>
</tr>
</table>
<table style="margin-top:100px">
<tr><td colspan="2"> <span style="font-family:arial;font-size:11px">Para verificar la autenticidad del 
presente documento consulte el siguiente enlace <b>{{url('/autorizacion')}}</b></span></td></tr>
</table>
</div>