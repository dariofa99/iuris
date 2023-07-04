const Toast = Swal.mixin({ 
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});
$(document).ready(function (){
	$("#table_list_model").on("click", ".pagination a", function (e) {
        e.preventDefault();
        page = $(this).attr("href");
        index_page(page);
        window.history.pushState(null, "", page);
    });
	$('.is_tooltip').tooltip();
    $('.onlynumber').keyup(function (){
      this.value = (this.value + '').replace(/[^0-9]/g, '');
    });

  $(':text[title]').tooltip({
      placement: "right",
      trigger: "focus"
  });

  $("body").on("blur",".validate_email",function (e){	
		validateEmail(this)
  	}  
  );

  $(".content_aditional_data").on(
	"change",
	".data_input_select",
	function (e) {
		let id = $(this).attr("data-id");
	   
		let status = $("#option_id-" + id + " option:selected").attr(
			"data-active_other"
		);
	
		if (status == 1) {
			$("#value_other_text-" + id).attr("type", "text").addClass("required");
			$("#lbl_other-" + id).show();
		} else {
			$("#value_other_text-" + id).attr("type", "hidden").removeClass("required");;
			$("#lbl_other-" + id).hide();
		}
	}
);

  });

  function validateEmail(obj){

	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($(obj).val()) || $(obj).val()=='')
	{
		return (true)
	}
	toastr.error("Formato de correo invalido!", "", {
		positionClass: "toast-top-right",
		timeOut: "3000",
	});
	$(obj).focus()
	return (false)
}

  //Funcion para validar un formulario
function validateForm(form){	
	var errors = [];
	$("#"+form+" .required").each(function(index,obj){
		if ($(this).attr('disabled')!='disabled') {
			if ($(this).val() =='') {
	  			errors.push('El campo '+$(this).attr('name')+' es obligatorio');
	  			$(this).css({'background':'#EC7063','border':'1px solid #EAEDED','color':"black"}).addClass("placeholdercolor");
	  			$(this).attr('placeholder','Este campo es obligatorio');
	  			//console.log($(this));
	  		}else if ($(this).val() !='') {
	  			//errors.push('El campo '+$(this).attr('data-name')+' es obligatorio');
	  			$(this).css({'background':'#fff','border':'1px solid #EAEDED'}).removeClass("placeholdercolor");
				 // $(this).attr('placeholder','');
	  			//$(this).attr('placeholder','Requerido');
	  			//console.log($(this).getAttribute('class'));
	  		}	
		}
  		  			
  	});
  	return errors	
} 

function validateTypeDoc(form) {
	console.log($(form.target)[0].form);
	var form = $(form.target)[0].form;	
	var form = $(form).attr("id");
	if($("#"+form+" select[name='tipodoc_id']").val()==''){
        toastr.error("Primero selecciona un tipo de documento", "Atención!", {
        positionClass: "toast-top-right",
        timeOut: "4000"}); 
        $(this).trigger("blur");
       }
}

function convertFormToJSON(form) {
	return $("#"+form)
	  .serializeArray()
	  .reduce(function (json, { name, value }) {		
		if(name.split('')[name.length - 2] == '[' && name.split('')[name.length - 1] == ']'){
			let _name = name.replace('[]', '');
			if(json[_name]==undefined) json[_name] = []	;
			json[_name].push(value);				
		}else{
			json[name] = value;
		}					
		return json;
	  }, {});
  }

  function existeFecha(fecha){
	
	try{        
		fecha_limit = moment(fecha).format('YYYY');
		var today = moment().format('YYYY')
		var limit = moment().add(1, 'years').format('YYYY');
		console.log(fecha_limit,today,limit);
		if(fecha_limit<today || fecha_limit > limit ){
			return false
		}
		return true
		
   }catch(err){  
	alert("Error fechas");    
}
}
function resetForm(form){
	$("#"+form+" input[name='id']").val("").prop("disabled",false);
	$("#"+form+" input[type='text']").val("").prop("disabled",false);
	$("#"+form+" input[type='email']").val("").prop("disabled",false);
	$("#"+form+" input[type='number']").val("").prop("disabled",false);
	$("#"+form+" select").val("").prop("disabled",false);
	$("#"+form+" input[type='radio']").prop("disabled",false);
	$("#"+form+" input[type='checkbox']").prop("disabled",false);
	
}
function resetDisabledForm(form){
	$("#"+form+" input[type='text']").prop("disabled",false);
	$("#"+form+" input[type='email']").prop("disabled",false);
	$("#"+form+" input[type='number']").prop("disabled",false);
	$("#"+form+" select").prop("disabled",false);
	$("#"+form+" input[type='radio']").prop("disabled",false);
	$("#"+form+" input[type='checkbox']").prop("disabled",false);
	
}

function getIdAttr(id, separador, orientacion) {
    ori = 1;
    if (orientacion != null) {
        ori = orientacion;
    }
    value = id.split(separador)[ori];
    return value;
}

function showElement(element, attrib) {
    if (attrib == null || attrib == "id") {
        $("#" + element).show();
    } else {
        $("." + element).show();
    }
}
function hideElement(element, attrib) {
    if (attrib == null || attrib == "id") {
        $("#" + element).hide();
    } else {
        $("." + element).hide();
    }
}
function enabledInput(input, attrib) {
    if (attrib == "id" || attrib == null) {
        $("#" + input).prop("disabled", false);
        $("#" + input).css({ background: "#FDFEFE" });
    } else {
        $("." + input).prop("disabled", false);
        $("." + input).css({ background: "#FDFEFE" });
    }
}
function disabledInput(input, attrib) {
    if (attrib == "id" || attrib == null) {
        $("#" + input).prop("disabled", true);
        $("#" + input).css({ background: "#EAEDED" });
    } else {
        $("." + input).prop("disabled", true);
        $("." + input).css({ background: "#EAEDED" });
    }
}

function setFechaToHumans(fecha) {
    var meses = {
        "01": "Enero",
        "02": "Febrero",
        "03": "Marzo",
        "04": "Abril",
        "05": "Mayo",
        "06": "Junio",
        "07": "Julio",
        "08": "Agosto",
        "09": "Septiembre",
        10: "Octubre",
        11: "Noviembre",
        12: "Diciembre",
    };
    var mes = getIdAttr(fecha, "-", 1);
    var mes = meses[mes];
    var dia = getIdAttr(fecha, "-", 2);
    var año = getIdAttr(fecha, "-", 0);
    var fecha = dia + " de " + mes + " del " + año;
    return fecha;
}
function index_page(route, request) {
    var route = route;
    $.ajax({
        url: route,
        type: "GET",
        datatype: "json",
        data: request,
        cache: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-CSRF-TOKEN", $("#token").attr("content"));
            $("#wait").css("display", "block");
        },
        /*muestra div con mensaje de 'regristrado'*/
        success: function (res) {
            $("#table_list_model").html(res);
            $("#wait").css("display", "none");
        },
        error: function (xhr, textStatus, thrownError) {
            alert(
                "Hubo un error con el servidor ERROR::" + thrownError,
                textStatus
            );
        },
    });
}