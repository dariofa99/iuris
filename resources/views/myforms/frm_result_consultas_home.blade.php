@extends('layouts.dashboard')
@section('titulo_area')

          
@endsection
@section('area_forms')

@include('msg.success')

<div class="row">
  
</div>

<div class="row">
<div class="col-md-12 table-responsive no-padding">

<table class="table">
    <thead>
        <tr>
          <th>Identificación</th>
          <th>Nombre</th>
          <th>Email</th>
          <th>Teléfono</th>          
          <th>No. Expedientes</th>
         
          <th>Ver</th>
        </tr>
      </thead>
      <tbody>

        <tr role="row" class="odd" id="1143993794">
<td>1143993794</td>
<td>JHONATAN ANDRES SEBASTIAN CHAMORRO PINEDA</td>
<td>tathan.4t@gmail.com</td>
<td>3225897394</td>
<td>



<center><span class="pull-center badge bg-green">150</span></center>





</td>



<td>
    <a href="https://iurisapp.udenar.edu.co/users/17233/edit" class="btn btn-primary btn-sm">Editar</a>




</td>
</tr>



                  <tr role="row" class="odd" id="1085347023">
                    <td>1085347023</td>
                    <td>Lesly Geraldine Castañeda Ibarra</td>
                    <td>geralcts@udenar.edu.co</td>
                    <td>3043732075</td>
                    <td>

                      

                      <center><span class="pull-center badge bg-green">100</span></center>
                      


                      

                    </td>
                   
 
                    <td><a href="https://iurisapp.udenar.edu.co/users/17029/edit" class="btn btn-primary btn-sm">Editar</a>
  

                      
                    </td>
                  </tr>
      </tbody>
</table>

</div>
</div>

@stop
