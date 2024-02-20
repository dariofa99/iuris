const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});
$(document).ready(function () {
    $("#table_list_model").on("click", ".pagination a", async function (e) {
        e.preventDefault();
        var page = $(this).attr("href");
        $("#wait").show();
        await index_pagination(page);
        $("#wait").hide();
        // window.history.pushState(null, "", page);
    });
    $('.is_tooltip').tooltip();
    $('.onlynumber').keyup(function () {
        this.value = (this.value + '').replace(/[^0-9]/g, '');
    });

    $(':text[title]').tooltip({
        placement: "right",
        trigger: "focus"
    });

    $("body").on("blur", ".validate_email", function (e) {
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

function validateEmail(obj) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($(obj).val()) || $(obj).val() == '') {
        return (true)
    }
    toastr.error("Formato de correo invalido!", "", {
        positionClass: "toast-top-right",
        timeOut: "3000",
    });
    $(obj).focus()
    return (false)
}
$(".urlactive").on("click", function () {
    var target = $(this).attr("href")
    var url = window.location.href;
    url = url.split("#")[0] + target;
    history.pushState({}, "", url)
});

function set_tab() {
    var url = window.location.href;
    var activeTab = url.substring(url.indexOf("#") + 1);
    var elementoA = $("a[href='#" + activeTab + "']");
    if (activeTab) elementoA.click();

}
function round(num, decimales = 1) {
    var signo = (num >= 0 ? 1 : -1);
    num = num * signo;
    if (decimales === 0) //con 0 decimales
        return signo * Math.round(num);
    // round(x * 10 ^ decimales)
    num = num.toString().split('e');
    num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
    // x * 10 ^ (-decimales)
    num = num.toString().split('e');
    return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
}

//Funcion para validar un formulario
function validateForm(form) {
    var errors = [];

    $("#" + form + " .required").each(function (index, obj) {
       
        if ($(obj).attr('disabled') != 'disabled' && !$(obj).is("input[type='hidden']")) {
            if ($(obj).is("input, select, textarea") && !$(this).is("div") && $(this).val() == '') {
                $(obj).show().css({"background":"red"});
                errors.push('El campo ' + $(obj).attr('name') + ' es obligatorio');
                $(obj).css({ 'background': '#EC7063', 'border': '1px solid #EAEDED', 'color': "black" }).addClass("placeholdercolor");
                $(obj).attr('placeholder', 'Este campo es obligatorio');                //
                if ($(this).hasClass("selectpicker")) {
                    $(this).selectpicker("setStyle", "btn-danger")
                        .selectpicker();
                }
            } else if ($(this).is("input, select, textarea") && !$(this).is("div") && $(this).val() != '') {
                $(this).css({ 'background': '#fff', 'border': '1px solid #EAEDED' })
                .removeClass("placeholdercolor");
                if ($(this).hasClass("selectpicker")) {
                    $(this).selectpicker('refresh');
                }
            }
        }
          
    });
    errors = [... new Set(errors)];
    return errors
}

function validateTypeDoc(form) {
    var form = $(form.target)[0].form;
    var form = $(form).attr("id");
    if ($("#" + form + " select[name='tipodoc_id']").val() == '') {
        toastr.error("Primero selecciona un tipo de documento", "Atención!", {
            positionClass: "toast-top-right",
            timeOut: "4000"
        });
        $(this).trigger("blur");
    }
}

function convertFormToJSON(form) {
    return $("#" + form)
        .serializeArray()
        .reduce(function (json, { name, value }) {
            if (name.split('')[name.length - 2] == '[' && name.split('')[name.length - 1] == ']') {
                let _name = name.replace('[]', '');
                if (json[_name] == undefined) json[_name] = [];
                json[_name].push(value);
            } else {
                json[name] = value;
            }
            return json;
        }, {});
}

function existeFecha(fecha) {

    try {
        fecha_limit = moment(fecha).format('YYYY');
        var today = moment().format('YYYY')
        var limit = moment().add(1, 'years').format('YYYY');
        console.log(fecha_limit, today, limit);
        if (fecha_limit < today || fecha_limit > limit) {
            return false
        }
        return true

    } catch (err) {
        alert("Error fechas");
    }
}
function resetForm(form) {
    $("#" + form + " input[name='id']").val("").prop("disabled", false);
    $("#" + form + " input[type='text']").val("").prop("disabled", false);
    $("#" + form + " input[type='email']").val("").prop("disabled", false);
    $("#" + form + " input[type='number']").val("").prop("disabled", false);
    $("#" + form + " select").val("").prop("disabled", false);
    $("#" + form + " input[type='radio']").prop("disabled", false);
    $("#" + form + " input[type='checkbox']").prop("disabled", false);

}
function resetDisabledForm(form) {
    $("#" + form + " input[type='text']").prop("disabled", false);
    $("#" + form + " input[type='email']").prop("disabled", false);
    $("#" + form + " input[type='number']").prop("disabled", false);
    $("#" + form + " select").prop("disabled", false);
    $("#" + form + " input[type='radio']").prop("disabled", false);
    $("#" + form + " input[type='checkbox']").prop("disabled", false);
    $("#" + form + " textarea").prop("disabled", false);

}
function disabledForm(form) {
    $("#" + form + " input[type='text']").prop("disabled", true);
    $("#" + form + " input[type='email']").prop("disabled", true);
    $("#" + form + " input[type='number']").prop("disabled", true);
    $("#" + form + " select").prop("disabled", true);
    $("#" + form + " input[type='radio']").prop("disabled", true);
    $("#" + form + " input[type='checkbox']").prop("disabled", true);
    $("#" + form + " textarea").prop("disabled", true);
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
async function index_pagination(route) {
    const page = route;
    const response = await fetch(page, {
        method: 'GET',
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-Token": $("#token").attr("content"),
        },

    });
    if (!response.ok) {
        const message = `An error has occured: ${response.status}`;
        console.log(response);
        throw new Error(message);
    }
    const topics = await response.json();
    if(topics.view){
        $("#table_list_model").html(topics.view);
    }else{
        $("#table_list_model").html(topics);
    }
    if(topics.view_count){
        $("#content_count_asesorias_inlist").html(topics.view_count);
    }
    window.history.pushState(null, "", route);
    return topics;
}

async function index_page(route, request) {
    const page = BASE_URL + route + "?" + new URLSearchParams(request);
    const response = await fetch(page, {
        method: 'GET',
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-Token": $("#token").attr("content"),
        },

    });
    if (!response.ok) {
        const message = `An error has occured: ${response.status}`;
        console.log(response);
        throw new Error(message);
    }
    const topics = await response.json();
    console.log(topics);
    if(topics.view){
        $("#table_list_model").html(topics.view);
    }else{
        $("#table_list_model").html(topics);
    }
    if(topics.view_count){
        $("#content_count_asesorias_inlist").html(topics.view_count);
    }
    
    window.history.pushState(null, "", page); 
    return topics;
}
function calcularProximosDiasHabiles(fechaActual, n) {
    var fechaActual = moment(fechaActual); // Fecha actual  
    var proximosDiasHabiles = [];
    while (proximosDiasHabiles.length < n) {
        fechaActual.add(1, 'days'); // Agregar un día a la fecha actual
        if (esDiaHabil(fechaActual)) {
            proximosDiasHabiles.push(fechaActual.format('YYYY-MM-DD'));
        }
    }
    return proximosDiasHabiles;
}
function esDiaHabil(fecha) {
    // Verificar si es fin de semana (sábado o domingo)
    if (fecha.day() === 0 || fecha.day() === 6) {
        return false;
    }
    // Verificar si es un feriado o día no laborable específico de Colombia
    // Aquí deberías agregar la lógica para verificar los días no laborables
    // Puedes tener una lista de feriados y días festivos en un array o cualquier otra estructura de datos.
    var feriados = getFestivos();
    var valid = true;
    feriados.filter(function (feriado) {
        //console.log(moment(feriado.date).format('YYYY-MM-DD 00:00:00'));
        if (moment(feriado.date).format('YYYY-MM-DD 00:00:00') == fecha.format("YYYY-MM-DD 00:00:00")) {
            valid = false;
        }
    });
    return valid;

}
function getFestivos() {
    return [
        {
            "date": "2023-01-01 00:00:00",
            "start": "2023-01-01T05:00:00.000Z",
            "end": "2023-01-02T05:00:00.000Z",
            "name": "Año Nuevo",
            "type": "public",
            "rule": "01-01"
        },
        {
            "date": "2023-01-09 00:00:00",
            "start": "2023-01-09T05:00:00.000Z",
            "end": "2023-01-10T05:00:00.000Z",
            "name": "Día de los Reyes Magos",
            "type": "public",
            "rule": "monday after 01-06"
        },
        {
            "date": "2023-03-20 00:00:00",
            "start": "2023-03-20T05:00:00.000Z",
            "end": "2023-03-21T05:00:00.000Z",
            "name": "San José",
            "type": "public",
            "rule": "monday after 03-19"
        },
        {
            "date": "2023-04-02 00:00:00",
            "start": "2023-04-02T05:00:00.000Z",
            "end": "2023-04-03T05:00:00.000Z",
            "name": "Domingo de Ramos",
            "type": "observance",
            "rule": "easter -7"
        },
        {
            "date": "2023-04-06 00:00:00",
            "start": "2023-04-06T05:00:00.000Z",
            "end": "2023-04-07T05:00:00.000Z",
            "name": "Jueves Santo",
            "type": "public",
            "rule": "easter -3"
        },
        {
            "date": "2023-04-07 00:00:00",
            "start": "2023-04-07T05:00:00.000Z",
            "end": "2023-04-08T05:00:00.000Z",
            "name": "Viernes Santo",
            "type": "public",
            "rule": "easter -2"
        },
        {
            "date": "2023-04-09 00:00:00",
            "start": "2023-04-09T05:00:00.000Z",
            "end": "2023-04-10T05:00:00.000Z",
            "name": "Pascua",
            "type": "public",
            "rule": "easter"
        },
        {
            "date": "2023-05-01 00:00:00",
            "start": "2023-05-01T05:00:00.000Z",
            "end": "2023-05-02T05:00:00.000Z",
            "name": "Día del trabajador",
            "type": "public",
            "rule": "05-01"
        },
        {
            "date": "2023-05-22 00:00:00",
            "start": "2023-05-22T05:00:00.000Z",
            "end": "2023-05-23T05:00:00.000Z",
            "name": "La Asunción",
            "type": "public",
            "rule": "easter 43"
        },
        {
            "date": "2023-06-12 00:00:00",
            "start": "2023-06-12T05:00:00.000Z",
            "end": "2023-06-13T05:00:00.000Z",
            "name": "Corpus Christi",
            "type": "public",
            "rule": "easter 64"
        },
        {
            "date": "2023-06-19 00:00:00",
            "start": "2023-06-19T05:00:00.000Z",
            "end": "2023-06-20T05:00:00.000Z",
            "name": "Sagrado Corazón de Jesús",
            "type": "public",
            "rule": "easter 71"
        },
        {
            "date": "2023-07-03 00:00:00",
            "start": "2023-07-03T05:00:00.000Z",
            "end": "2023-07-04T05:00:00.000Z",
            "name": "San Pedro y San Pablo",
            "type": "public",
            "rule": "monday after 06-29"
        },
        {
            "date": "2023-07-20 00:00:00",
            "start": "2023-07-20T05:00:00.000Z",
            "end": "2023-07-21T05:00:00.000Z",
            "name": "Día de la Independencia",
            "type": "public",
            "rule": "07-20"
        },
        {
            "date": "2023-08-07 00:00:00",
            "start": "2023-08-07T05:00:00.000Z",
            "end": "2023-08-08T05:00:00.000Z",
            "name": "Batalla de Boyacá",
            "type": "public",
            "rule": "08-07"
        },
        {
            "date": "2023-08-21 00:00:00",
            "start": "2023-08-21T05:00:00.000Z",
            "end": "2023-08-22T05:00:00.000Z",
            "name": "Asunción",
            "type": "public",
            "rule": "monday after 08-15"
        },
        {
            "date": "2023-10-16 00:00:00",
            "start": "2023-10-16T05:00:00.000Z",
            "end": "2023-10-17T05:00:00.000Z",
            "name": "Día de la Raza",
            "type": "public",
            "rule": "monday after 10-12"
        },
        {
            "date": "2023-11-06 00:00:00",
            "start": "2023-11-06T05:00:00.000Z",
            "end": "2023-11-07T05:00:00.000Z",
            "name": "Todos los Santos",
            "type": "public",
            "rule": "1st monday in November"
        },
        {
            "date": "2023-11-13 00:00:00",
            "start": "2023-11-13T05:00:00.000Z",
            "end": "2023-11-14T05:00:00.000Z",
            "name": "Independencia de Cartagena",
            "type": "public",
            "rule": "monday after 11-11"
        },
        {
            "date": "2023-12-08 00:00:00",
            "start": "2023-12-08T05:00:00.000Z",
            "end": "2023-12-09T05:00:00.000Z",
            "name": "La inmaculada concepción",
            "type": "public",
            "rule": "12-08"
        },
        {
            "date": "2023-12-25 00:00:00",
            "start": "2023-12-25T05:00:00.000Z",
            "end": "2023-12-26T05:00:00.000Z",
            "name": "Navidad",
            "type": "public",
            "rule": "12-25"
        }
    ]
}

var myVar = setInterval(myTimer, 1000);
function myTimer() {
    var d = new Date();
    var dia = d.getDate();
    var mes = d.getMonth() + 1;
    if (mes <= 9) {
        mes = "0" + mes;
    }
    if (dia <= 9) {
        dia = "0" + dia;
    }
    var anio = d.getFullYear();
    var cadena =
        "" + " " + mes + "/" + dia + "/" + anio + " " + d.toLocaleTimeString();
    if(document.getElementById("fecha_sistema")) document.getElementById("fecha_sistema").innerHTML = cadena;
    //document.getElementById("demo").innerHTML =
}

function validateNotas(form){	
	var errors = [];
	$("#"+form+" .val_nota").each(function(index,obj){
		if ($(this).attr('disabled')!='disabled') {
			if ($(this).val() !='' && $(this).val()>5) {
	  			errors.push('El campo '+$(this).attr('name')+' es mayor que 5');
	  			$(this).css({'background':'#FDEDEC','border':'1px solid #33FF90'});
	  			$(this).attr('placeholder','Requerido');
	  			//console.log($(this));
	  		}else if ($(this).val() !='' && isNaN($(this).val())) {	  			
	  			$(this).css({'background':'#fff','border':'1px solid #33FF90'});
				errors.push('El campo esta mal diligenciado');
				$(this).css({'background':'#FDEDEC','border':'1px solid #33FF90'});
	  			$(this).attr('placeholder','Requerido');	  		
	  		}	
		}
  		  			
  	});
  	return errors 	
} 