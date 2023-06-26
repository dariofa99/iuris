@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
       

      <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <label >Código: {{Request::get('codigo')}}</label>
                </div>

                <div class="card-body">    
                <form id="myFormRevocarFirmaConciliacion" action="/" method="POST">   
                    @csrf 

                    <input type="hidden" name="email">
                    <input type="hidden" name="token_firma" value="{{Request::get('token')}}">
                    <input type="hidden" name="codigo" value="{{Request::get('codigo')}}">
                        <div class="row">         
                            <div class="col-md-12">
                                <table class="table" id="myReportPdfList">
                                    <thead>
                                        <tr><th>
                                            Nombre 
                                        </th>
                                        <th>
                                            Ver
                                        </th>
                                     </tr>
                                    </thead>
                                    <tbody>
                                    <tr> 
                       
                        <td>
                        <i class="fa fa-file-pdf-o"> </i>                    
                        {{$pdf_report->reporte->nombre_reporte}}                
                        </td>
                        <td>
                       <a class="btn btn-warning btn-sm pull-right" target="_blank" href="/pdf/reportes/generate/{{$user->conciliacion_id}}/{{$pdf_report->reporte_id}}/{{$pdf_report->status_id}}">
                        Vista previa </a> 
                        </td>
                    
                            
                        </tr>
               
                    
                    </tbody>
                                </table>

                            </div>   
                            
                           

                        </div>
                        <div class="row">        
                            <div class="col-md-12">
                            <div class="form-group">
                            <button type="submit" id="btn_sendForm" class="btn btn-warning btn-sm btn-block">
                            Aceptar revocación de firma</button>
                            </div>
                            </div>    
                        </div>
                </form> 
                <hr>
                <div id="respu_firma" style="display: none" class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong id="lbl_respu_firma">Warning!</strong> 
                  </div>
                 
                </div>
        </div> 
    </div>

</div>
@endsection

@push('scripts')

<script>

    $(document).ready(function () {
        $("#myFormRevocarFirmaConciliacion").on("submit",function (e) {  
            var request = $(this).serialize();         
            getStatus(request)
            e.preventDefault()
     });  
    })
     

    // firmarDocumento({});

    function getStatus(request) {
    var route = "/firmar/revocar/get/status";
    $.ajax({
        url: route,
        type: "GET", 
        datatype: "json",
        data: request,
        cache: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-CSRF-TOKEN", $("#token").attr("content"));
            $("#wait").show();
        },
        /*muestra div con mensaje de 'regristrado'*/
        success: function (res) {
            
            if(res.revocado==0){
            Swal.fire({
                title: 'Esta seguro que desea aceptar la revocación de firma del documento?',         
                              
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, aceptar!',
                cancelButtonText: 'No, cancelar!'
            }).then((result) => {
                if (result.value) {    
                   
                        revocarFirma(request);   
                    
                          
                }
            }); 
          }else{
            Swal.fire({
				title: 'La revocación ya fue aceptada!',
				type: 'info', 				         
                //showDenyButton: true,
                confirmButtonText: 'Cerrar',
                //denyButtonText: `Don't save`,             
            })
          }
          //  window.location.reload(true);
            $("#wait").hide();
        },
        error: function (xhr, textStatus, thrownError) {
            
            $("#wait").hide();
        },
    });
}

    function revocarFirma(request) {
    var route = "/firmar/revocar/ok";
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
				title: 'Se ha revocado con éxito.',
				type: 'success', 				         
                //showDenyButton: true,
                confirmButtonText: 'Cerrar',
                //denyButtonText: `Don't save`,             
            })
            $("#lbl_respu_firma")
            .html('Se ha revocado con éxito.')
            .show()
            $("#respu_firma").show()
          //  window.location.reload(true);
            $("#wait").hide();
            //window.location.reload()
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