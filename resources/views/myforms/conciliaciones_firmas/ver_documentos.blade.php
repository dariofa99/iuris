@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
       

      <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <label >Consulta de documentos </label>
                </div> 

                <div class="card-body">    
                <form id="myFormFirmarDocConciliacion" action="{{route("store.rpdescargado")}}" method="POST">   
                    @csrf 
                    <input type="hidden" name="token" value="{{Request::get('token')}}">
               
                        <div class="row">         
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
                            
                           

                        </div> 
                      
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
        $("#myFormFirmarDocConciliacion").on("submit",function (e) {           
            Swal.fire({
                title: 'Esta seguro que desea firmar el documento?',         
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off',
                    placeholder:'Confirmar correo electrónico.'
                },
                preConfirm: (email) => {
                    $("#myFormFirmarDocConciliacion input[name=email]").val(email);                    
                },               
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Firmar!',
                cancelButtonText: 'No, cancelar!'
            }).then((result) => {
                if (result.value) {    
                    var request = $(this).serialize();
                    firmarDocumento(request);          
                }
            }); 
            e.preventDefault()
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
console.log(res);
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