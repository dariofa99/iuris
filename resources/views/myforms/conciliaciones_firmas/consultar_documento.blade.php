@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
       

      <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <label >Consulta de documentos </label>
                </div> 

                <div class="card-body">  
                 
                    <div class="row">
                        <div class="col-md-12">
                            @if (session('status'))
                                <div class="alert alert-danger">
                                    {{ session('status') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    

                <form id="myFormConsultarDocConciliacion" action="{{route("store.rpdescargado")}}" method="POST">   
                    @csrf 
                    <input type="hidden" name="token" value="{{$token}}">
                   <div class="row">
                    <div class="col-md-12">
                        <div class="form-group has-feedback"><label for="clave">Clave</label>
                            <input id='clave' name='clave' value="{{ old('clave') }}"  required type="text" class="form-control form-control-sm">
                            <span class="nav-icon fa fa-user-secret form-control-feedback"></span>
                            <div class="invalid-feedback">                            
                            </div>
                        </div>
                        @if($conGen->category_id!=215)                       
                        <div class="form-group has-feedback"><label for="documento">Número de documento</label>
                            <input id='documento' name='documento' value="{{ old('documento') }}"  required type="number" class="form-control form-control-sm">
                            <span class="nav-icon fa fa-address-card form-control-feedback"></span>
                            <div class="invalid-feedback">                            
                            </div>
                        </div>
                        <div id="con_inse" class="form-group has-feedback"><label for="nombres">Nombres</label>
                            <input id='nombres' name='nombres' value="{{ old('nombres') }}"  required type="text" class="form-control form-control-sm">
                            <span class="nav-icon fa fa-address-card-o form-control-feedback"></span>
                            <div class="invalid-feedback">                            
                            </div>
                        </div>
                        @else                        
                        <div class="form-group has-feedback"><label for="entidad_nombre">Nombre de la entidad</label>
                            <input id='entidad_nombre' name='entidad_nombre' value="{{ old('entidad_nombre') }}"  required type="text" class="form-control form-control-sm">
                            <span class="nav-icon fa fa-address-card-o form-control-feedback"></span>
                            <div class="invalid-feedback">                            
                            </div>
                        </div>

                        <div class="form-group has-feedback"><label for="entidad_correo">Correo electrónico</label>
                            <input id='entidad_correo' name='entidad_correo' value="{{ old('entidad_correo') }}"  required type="text" class="form-control form-control-sm">
                            <span class="nav-icon fa fa-address-card-o form-control-feedback"></span>
                            <div class="invalid-feedback">                            
                            </div>
                        </div>
                        
                        @endif
                    </div>
                   </div>
                   <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary btn-sm btn-block" type="submit">Consultar</button>
                    </div>
                   </div>
                     {{--    <div class="row">         
                            <div class="col-md-12">
                                <table   class="table" id="myReportPdfList">
                                    <thead>
                                        <tr><th>
                                            Nombre 
                                        </th>
                                        <th style="text-align: right">
                                            Ver
                                        </th>
                                     </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($conGen->files as $file)

                                        <tr> 
                       
                                            <td>
                                            <i class="fa fa-file-pdf-o"> </i>                    
                                            {{$file->original_name}}                
                                            </td>
                                            <td>
                                           <a class="btn btn-warning btn-sm pull-right" target="_blank" href="/conciliaciones/download/file/{{$file->id}}">
                                            Vista previa </a> 
                                            </td>
                                        
                                                
                                            </tr>
                                        @endforeach


               
                    
                    </tbody>
                                </table>

                            </div>   
                            
                           

                        </div> --}}
                      
                </form> 
                <hr>
                 
                </div>
        </div> 
    </div>

</div>
@endsection

@push('scripts')

<script>

    $(document).ready(function () {
        $("#myFormConsultarDocConciliacion").on("submit",function (e) {           
            $("#myFormConsultarDocConciliacion button").prop('disabled',false)
            //e.preventDefault()
     });  
    })
     

    // firmarDocumento({});

    

    function firmarDocumento(request) {
    var route = "/firmar/ok";
    $.ajax({
        url: route,
        type: "POST", 
        datatype: "json",
        data: request,
        cache: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-CSRF-TOKEN", $("#token").attr("content"));
            $("#wait").show();
        },
        /*muestra div con mensaje de 'regristrado'*/
        success: function (res) {
            
           // $("#lbl_pfd_report_name").text(res.data.reporte.nombre_reporte);
            //$("#table_list_pdf_users tbody").html(res.view);
            Swal.fire({
				title: 'Se ha enviado un correo a '+res.user.email+' para su confirmación.',
				type: 'success', 				         
                //showDenyButton: true,
                confirmButtonText: 'Cerrar',
                //denyButtonText: `Don't save`,             
            })

          //  window.location.reload(true);
            $("#wait").hide();
        },
        error: function (xhr, textStatus, thrownError) {
            Swal.fire({
				title: 'Correo electrónico no encontrado!',
				type: 'info', 				         
                //showDenyButton: true,
                confirmButtonText: 'Cerrar',
                //denyButtonText: `Don't save`,             
            })
            $("#wait").hide();
        },
    });
}

</script>


@endpush