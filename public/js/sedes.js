(function() {
    $("#btn_new_sede").on("click",function (e) {
        $("#myFormEditSede").attr('id','myFormCreateSede');
        $("#myFormCreateSede button[type=submit]").removeClass('btn-warning').
        addClass("btn-primary").text("Crear")
        $("#myModal_sede_create").modal("show");
    });

    $("#content_form_cl").on("submit",'#myFormCreateSede',function(e) {
        var request = $(this).serialize();
        storeSede(request);
        e.preventDefault();
    });

    $("#content_table_sedes_list").on("click",'.btn_edit_sede',function (e) {
        var id = $(this).attr("data-id");
        editSede(id)
    });

    $("#content_table_sedes_list").on("click",'.btn_delete_sede',function (e) {
        var id = $(this).attr("data-id");        
        Swal.fire({
        title: 'Esta seguro de eliminar el registro?',     
        type: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.value) {        
                deleteSede(id) ;           
            }
        });             
    });

    $("#content_form_cl").on("submit",'#myFormEditSede',function(e) {
        var request = $(this).serialize();
       var id =  $("#myFormEditSede input[name=id]").val();
        updateSede(id,request);
        e.preventDefault();
    });

})();

function storeSede(request) {
    var route = "/sedes";
    $.ajax({
      url: route,
      type: 'POST',
      datatype: 'json',
      data: request,
      cache: false,
      beforeSend: function (xhr) {
        xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
        $("#wait").show();
      },
      success: function (res) {   
        if(res.view){
            $("#table_list_sedes tbody").html(res.view)
        }
        $("#myModal_sede_create").modal("hide");
        $("#wait").hide();
    
      },
      error: function (xhr, textStatus, thrownError) {
        alert("Hubo un error con el servidor, consulte con el administrador");
        $("#wait").css("display", "none");
      }
    });
}

function editSede(id) {
    var route = "/sedes/"+id+"/edit";
    $.ajax({
      url: route,
      type: 'GET',
      datatype: 'json',
      data: {},
      cache: false,
      beforeSend: function (xhr) {
        xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
        $("#wait").show();
      },
      success: function (res) {   
        $("#myFormCreateSede").attr('id','myFormEditSede');
        $("#myFormEditSede input[name=id]").val(res.id);
        $("#myFormEditSede input[name=nombre]").val(res.nombre)
        $("#myFormEditSede input[name=ubicacion]").val(res.ubicacion);
        $("#myFormEditSede button[type=submit]").removeClass('btn-primary').
        addClass("btn-warning").text("Actualizar");
        $("#myModal_sede_create").modal("show");
        $("#wait").hide();
    
      },
      error: function (xhr, textStatus, thrownError) {
        alert("Hubo un error con el servidor, consulte con el administrador");
        $("#wait").css("display", "none");
      }
    });
}

function updateSede(id,request) {
    var route = "/sedes/"+id;
    $.ajax({
      url: route,
      type: 'PUT',
      datatype: 'json',
      data: request,
      cache: false,
      beforeSend: function (xhr) {
        xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
        $("#wait").show();
      },
      success: function (res) {   
        if(res.view){           
            $("#table_list_sedes tbody").html(res.view)
        }
        $("#myModal_sede_create").modal("hide");
        $("#wait").hide();
    
      },
      error: function (xhr, textStatus, thrownError) {
        alert("Hubo un error con el servidor, consulte con el administrador");
        $("#wait").css("display", "none");
      }
    });
}

function deleteSede(id) {
    var route = "/sedes/"+id;
    $.ajax({
      url: route,
      type: 'DELETE',
      datatype: 'json',
      data:{},
      cache: false,
      beforeSend: function (xhr) {
        xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
        $("#wait").show();
      },
      success: function (res) {   
        if(res.view){           
            $("#table_list_sedes tbody").html(res.view)
        }
        $("#myModal_sede_create").modal("hide");
        $("#wait").hide();
    
      },
      error: function (xhr, textStatus, thrownError) {
        alert("Hubo un error con el servidor, consulte con el administrador");
        $("#wait").css("display", "none");
      }
    });
}