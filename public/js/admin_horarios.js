import { HorariosService } from "./services/turnos.js";
import { UserService } from "./services/users.js";

const horariosService = new HorariosService();
const usersSerice = new UserService();

var v_users = [];
$(document).ready(function () {

    $("#myFormCalendar").submit(async function (e) {
        e.preventDefault()


        var filas = $("#contencalendarid tr").length;
        //console.log(filas);
        if (filas > 0) {
            var errors = validateForm('myFormCalendar');
            if (errors.length <= 0) {
                var data = convertFormToJSON('myFormCalendar');
               // console.log(data);
            } else {
                toastr.error("Ups! Parece hay campos obligatorios sin marcar. ", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
            }
            $("#wait").show();
             let response = await horariosService.store_asistencia(data);
            $("#wait").hide();
            $("#mymodal").modal("hide");
            toastr.success("Guardado con éxito!. ", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
        } else {
            if (filas <= 0) {
                toastr.error("Ups! Parece que no hay información.", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
            }

        }


        //return false;


    });

    $("#addest").click(async function () {
        //console.log(v_users) 
        if (v_users.length <= 0) {
            let response = await usersSerice.getUsersByRole({ 'role': 'estudiante', 'active': 1 });
            if (response.encontrado) {
                if ($("#contencalendarid tr").length == 0) {
                    $("#contencalendarid").html("")
                }
                $(response.users).each(function (key, value) {
                    var user = {
                        "idnumber": value.idnumber,
                        "full_name": value.full_name,
                        "ref_nombre_curso": value.curso.ref_nombre
                    }
                    v_users.push(user);
                    //console.log(value.idnumber+'---'+value.full_name);
                });
                //console.log(v_users);
                llenarDatos();
            }
            //getEstudiantes();
            console.log(response)
        } else {
            if ($("#contencalendarid tr").length == 0) {
                $("#contencalendarid").html("")
            }
            //$("#contencalendarid").html("")
            llenarDatos();
        }

    });

    $('#myFormCalendar').on('change', '.select_users', async function (e) {
        let name = $(this).val();
        if (name != "") {
            var position = $(this).attr('id').split('-')[1];
            let user = await v_users.find((user) => user.idnumber == name);
            $("#lbl_ref_c-" + position).text(user.ref_nombre_curso);
        }

    });

});/////////////////////////////////////////////////////

function llenarDatos() {
    var idest = $("#tbl_turnos_list .lbl_index").length;
    var idestu = idest + 1;
    $('#contencalendarid').append('<tr id="row_' + idest + '">' +
        '<td><span class="lbl_index" id="lbl_index-' + idest + '">' + idestu +
        '</span><input type="hidden" name="idasis[]"></td>' +
        '<td><select class="form-control form-control-sm required selectpicker estselectest2 select_users" data-live-search="true" id="idnumberestasis-' +
        idest + '" name="idnumberestasis[]" required>' +
        '<option value="">Seleccione el estudiante...</option>' +
        '</select></td>' +
        '<td><span id="lbl_ref_c-' + idest + '"></span></td>' +
        '<td><select class="form-control form-control-sm required" id="idasisestasis' + idest +
        '" name="idasisestasis[]">' +
        '<option value="125">Reposición</option>' +
        '<option value="126">Falta reposición</option>' +
        '<option value="127">Turno extenporaneo</option>' +
        '<option value="128">Turno fijo</option>' +
        '</select></td>' +
        '<td><select class="form-control form-control-sm required" id="idlugarestasis' + idest +
        '" name="idlugarestasis[]"  >' +
        '<option value="130">Consultorios</option>' +
        '<option value="131">C.J. Virtuales</option>' +
        '<option value="132">Of. Desplazados</option>' +
        '<option value="133">Externo</option>' +
        '<option value="134">Otro</option>' +
        '</select></td>' +
        '<td><textarea class="form-control form-control-sm required" rows="2" id="comentarioestasis' + idest +
        '" name="comentarioestasis[]" style="height: 35px;min-height: 33px;max-height: 150px;" required>.</textarea>' +
        '</td>' +
        '<td><button class="btn btn-danger btn_delete_row" type="button" id="btn_delete_row-' +
        idest + '"><i class="fa fa-minus-circle"></i></button></td>' +
        '</tr>');
    var option = '';
    $(v_users).each(function (key, value) {
        option += '<option value="' + value.idnumber + '">' + value.full_name.toUpperCase() +
            '</option>';
    });
    $("#idnumberestasis-" + idest).append(option); //coloca una nueva opcion
    $(".estselectest2").selectpicker("refresh"); //refresca el select
    $('#idestlistcal').val(''); //borra contenido contador lista estudiantes calendario
    $('#idestlistcal').val(parseInt(idest +
        1)); //coloca el contador de la lista de estudiantes calendario
}

