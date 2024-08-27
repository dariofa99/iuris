var previewNode = document.querySelector("#template_3");
previewNode.id = "";
var previewTemplate = previewNode.parentNode.innerHTML;
previewNode.parentNode.removeChild(previewNode);
var CSRF_TOKEN = $("#token").attr('content');
var clickableId;
document.querySelectorAll(".fileinputclickable").forEach(function (element) {
  element.addEventListener("click", function () {
    clickableId = element;
  });
});

var myDropzone_log = new Dropzone("div#cont_files", { // Make the whole body a dropzone
  url: "/conciliaciones/store/anexo", // Set the url
 //url:"/solicitudes/store/documento",
 thumbnailWidth: 80,
  thumbnailHeight: 80,
  paramName: "conciliacion_file",
  parallelUploads: 20,
  previewTemplate: previewTemplate,
  acceptedFiles: "application/pdf",
  headers: {
    'x-csrf-token': CSRF_TOKEN,
  },
  //autoProcessQueue: false,
  autoQueue: false, // Make sure the files aren't queued until manually added
  previewsContainer: "#previews_logs", // Define the container to display the previews
  clickable: ".fileinputclickable", // Define the element that should be used as click trigger to select files.
  dictRemoveFileConfirmation: 'Esta seguro...'
});



myDropzone_log.on("addedfile", function (file) {
  // Hookup the start button
  //myDropzone_log.addedfile(file)
  console.log(file);

  //$("#actions_upload_logs .cancel").prop("disabled", true);
  $("#actions_upload_logs .start").prop("disabled",false).show();
  if (file.type == 'application/pdf') {
    newimage = "/dist/img/dropzone_file.png";
    file.previewElement.querySelector("img").src = newimage;
    file.previewElement.querySelector("input").value = clickableId.getAttribute('data-text');
    $("#actions_upload_logs .start").prop("disabled", false);
    if (clickableId.id != 'otro') {
      $(clickableId).removeClass('fileinputclickable').hide()
    } else if (clickableId.id == 'otro') {
      $(file.previewElement.querySelector("input")).prop("readonly", false).val("").trigger('focus')
    }
    $(file.previewElement.querySelector("img")).css({ 'height': '70px', 'width': '80px' });
    $(file.previewElement.querySelector("button")).attr("data-clickeable", clickableId.id)
    $(file.previewElement.querySelector("button")).attr("data-category", clickableId.id)
 
  } else {
    Swal.fire({
      title: 'Suba ur archivo en formato .pdf',
      icon: 'info'
    })
  }

  //file.previewElement.querySelector("img").width = '80';

  //
  file.previewElement.querySelector(".cancel").onclick = function () {
    var id = $(this).attr('data-clickeable')
    Swal.fire({
      title: 'Esta seguro de eliminar el archivo?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, eliminar!',
      cancelButtonText: 'No, cancelar'
    }).then((result) => {
      if (result.value) {
        myDropzone_log.removeFile(file);
        $("#"+id).addClass('fileinputclickable').show()


      }
    });

    return false;
  };
  //
  //



  // myDropzone_log.enqueueFiles(myDropzone_log.getFilesWithStatus(Dropzone.ADDED))
});

// Update the total progress bar
myDropzone_log.on("totaluploadprogress", function (progress) {
  //document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
});

myDropzone_log.on("sending", function (file, xhr, formData) {
  // Show the total progress bar when upload starts
  //$("#actions .start").prop("disabled",true);
  //document.querySelector('.fileupload-process').style.display = 'block';
  //document.querySelector("#total-progress").style.opacity = "1";
  //formData.append("type_category_id", $("#type_category_payment_id").val());

  formData.append("concept", $(file.previewElement.querySelector("input")).val());
  formData.append("conciliacion_id", $("#conciliacion_id").val());
  formData.append("category_id", $("#anexo_category_id").val())
  formData.append("view_template", $("#view_template").val())
  // And disable the start button

});

myDropzone_log.on("success", function (file, response) {

  //$("#payment_files").html(response.image_list);
  //myDropzone_log.removeFile(file);
  //file.previewElement.querySelector(".cancel").setAttribute("disabled", "disabled");
  $(file.previewElement.querySelector(".cancel")).removeClass('btn-warning').addClass('btn-success').prop('disabled', true)
  $(file.previewElement.querySelector(".cancel")).children().removeClass('fa-minus-circle').addClass('fa-check')
 console.log($("#view_template").val());
  if (response.view || response.view == "") {
    if($("#view_template").val()=='anexos_ajax'){
      $("#table_anexos_list tbody").html(response.view);
    }else{
      $("#tablelistardocumentosgen tbody").html(response.view);
    }
    
  }

  //$("#actions .start").prop("disabled",true);
});

// Hide the total progress bar when nothing's uploading anymore
myDropzone_log.on("queuecomplete", function (progress) {
  $("#actions_upload_logs .cancel").prop("disabled", false);
  myDropzone_log.removeAllFiles(true);

  $("#myModal_create_document").modal("hide")
  $("#wait").hide();
  //document.querySelector("#total-progress").style.opacity = "0";
});


// Setup the buttons for all transfers
// The "add files" button doesn't need to be setup because the config
// `clickable` has already been specified.
document.querySelector("#actions_upload_logs .start").onclick = function () {
  var files = $(".fileinputclickable").length;
  var vacio = false;
  $(".form-control-dropzone").each(function (key, element) {
    if (element.value == ""){
       vacio = true;
       $(element).focus()
    } 
  });
  if (files == 1 && vacio == false) {
     myDropzone_log.enqueueFiles(myDropzone_log.getFilesWithStatus(Dropzone.ADDED));
    console.log("sss");
  } else {
    if(vacio == true){
      Swal.fire({
        title: 'Hay campos que son obligatorios',
        icon: 'error'
      })
    }else if(files > 1){
      Swal.fire({
        title: 'Hay archivos que son obligatorios',
        icon: 'error'
      })
    }
    
  }

  //$("#actions_upload_logs .start").prop("disabled", true);
  // $("#actions_upload_logs .cancel").prop("disabled", true);
  // myDropzone_log.enqueueFiles(myDropzone_log.getFilesWithStatus(Dropzone.ADDED));
};
/* document.querySelector("#actions_upload_logs .cancel").onclick = function () {
  myDropzone_log.removeAllFiles(true);
  $("#content_list_support_file").show();
  $("#content_form_support_file").hide();
  $("#myModal_create_bill #type_category_payment_id").val("").change();
}; */