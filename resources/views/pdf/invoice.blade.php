

@foreach ($requerimiento as $req)
<table id="tbl_ajax_req" class="table table-bordered table-striped dataTable" role="grid" border="1">


   <tr  style="text-align: center;" >
      <td style="text-align: center;"><img src="/mtn/san/www/iuris/resources/views/pdf/images/logoudenar.jpg" width="70" height="70" /></td>
      <td colspan="4"  style="text-align: center;"  >
     <b> CONSULTORIOS JURÍDICOS - CENTRO DE CONCILIACIÓN</b><br>
      "EDUARDO ALVARADO HURTADO"<br>
<hr>
      CITA O REQUERIMIENTO A USUARIO
      </td>
   </tr>


    <tr>
      <th>EXPEDIENTE</th>
       <td>

          {{ $req->expediente }}

     </td>

      <th>FECHA/HORA</th>
       <td colspan="2" >

         {{ $req->reqfecha }}/{{ $req->reqhora }}

     </td>
  </tr>


    <tr>
      <th>NOMBRE</th>
       <td colspan="4">

          {{ $req->usr_nombre." ".$req->usr_ape }}
 		 
 	   </td>
</tr>
<tr>
      <th>DIRECCIÓN</th>
      <td colspan="4">{{ $req->usr_direc }}</td>
    </tr>
  <tr>   
      <th>MOTIVO</th>
      <td colspan="4">{{ $req->rmotivo }}</td>
    </tr>  
    <tr>  
      <th>DESCRIPCIÓN</th>
        <td  colspan="4" style="text-align: justify;"  >
		       {{ $req->descrip }}
		 </td>
    </tr>
    <tr>  
      <th>ESTUDIANTE</th>
        <td colspan="2">
	     {{ $req->est_nombre." ".$req->est_ape }}
	 </td>
      <th>CELULAR ESTUDIANTE</th>
      <td> {{ $req->est_tel1.",  ".$req->est_tel2  }}</td>
    </tr>


</table>




<br>




<table id="tbl_ajax_req" class="table table-bordered table-striped dataTable" role="grid" border="1">


   <tr  style="text-align: center;" >
      <td style="text-align: center;">
      <img src="/mnt/san/www/iuris/resources/views/pdf/images/logoudenar.jpg" width="70" height="70" /></td>
      <td colspan="4"  style="text-align: center;"  >
     <b> CONSULTORIOS JURÍDICOS - CENTRO DE CONCILIACIÓN</b><br>
      "EDUARDO ALVARADO HURTADO"<br>
<hr>
      CITA O REQUERIMIENTO A USUARIO
      </td>
   </tr>


    <tr>
      <th>EXPEDIENTE</th>
       <td>

          {{ $req->expediente }}
     
     </td>
 
      <th>FECHA/HORA</th>
       <td colspan="2">

          {{ $req->reqfecha }}/{{ $req->reqhora }}
     
     </td>
  </tr>
  
    <tr>
      <th>NOMBRE</th>
       <td colspan="4">

          {{ $req->usr_nombre." ".$req->usr_ape }}
     
     </td>
</tr>
<tr>
      <th>DIRECCIÓN</th>
      <td colspan="4">{{ $req->usr_direc }}</td>
    </tr>
    <tr>   
      <th>MOTIVO</th>
      <td colspan="4">{{ $req->rmotivo }}</td>
    </tr>
    <tr>
      <th>DESCRIPCIÓN</th>
        <td colspan="4"  style="text-align: justify;" >
           {{ $req->descrip }}
     </td>
    </tr>
    <tr>
      <th>ESTUDIANTE</th>
        <td colspan="2" >
       {{ $req->est_nombre." ".$req->est_ape }}
   </td>

      <th>CELULAR ESTUDIANTE</th>
      <td> {{ $req->est_tel1.",  ".$req->est_tel2  }}</td>
    </tr>


</table>

@endforeach







