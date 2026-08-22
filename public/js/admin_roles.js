class UIAdminRoles {

    constructor(id = '', name = '', display_name = '', description = '') {
        this.id = id;
        this.name = name;
        this.display_name = display_name;
        this.description = description;
        this.form = '';

    }

    loadFormEdit() {
        const form = $("#content_form form");
        const myform = this.form
        form.attr('id', myform);
        form[0]['id'].value = this.id;
        form[0]['name'].value = this.name;
        form[0]['display_name'].value = this.display_name;
        form[0]['description'].value = this.description;
        $(form[0]['button']).text('Actualizar');
        $(form[0]['button']).removeClass('btn-primary').addClass('btn-warning');
        $("#btn_save_cancel").show();
    }

    loadFormCreate() {
        const form = $("#content_form form");
        const myform = this.form
        form.attr('id', myform);
        form[0]['id'].value = this.id;
        form[0]['name'].value = this.name;
        form[0]['display_name'].value = this.display_name;
        form[0]['description'].value = this.description;
        $(form[0]['button']).text('Guardar');
        $(form[0]['button']).removeClass('btn-warning').addClass('btn-primary');
        $("#btn_save_cancel").hide()
    }


}




(function () {

    //Roles
    $("#content_form").on('submit', '#myFormCreateRole', function (e) {
        createRole();
        e.preventDefault();
    });


    $("#content_form").on('submit', '#myFormEditRole', function (e) {
        id = $("#id").val()
        updateRole(id);
        e.preventDefault();
    })

    $(".content_ajax").on('click', '.btn_edit_role', function (e) {
        e.preventDefault();
        id = $(this).attr('id').split('-')[1];
        searchRole(id);
    });

    $(".content_ajax").on('click', '.btn_delete_role', function (e) {
        id = $(this).attr('id').split('-')[1];
        url = '/admin/roles/' + id;
        sweetAlertDelete(url);
    });


    //end-roles


    //Permisos
    $("#content_form").on('submit', '#myFormCreatePermission', function (e) {
        createPermission();
        e.preventDefault();
    });

    $(".content_ajax").on('click', '.btn_edit_permission', function (e) {
        e.preventDefault();
        id = $(this).attr('id').split('-')[1];
        searchPermission(id);
    });

    $("#content_form").on('submit', '#myFormEditPermission', function (e) {
        id = $("#id").val()
        updatePermission(id);
        e.preventDefault();
    });

    $(".content_ajax").on('click', '.btn_delete_permission', function (e) {
        id = $(this).attr('id').split('-')[1];
        url = '/admin/permisos/' + id;
        sweetAlertDelete(url);
    });

    //end-permission
    $("#content_form #btn_save_cancel").click(function (e) {
        role = new UIAdminRoles();
        role.form = $(this).attr('data-form');
        role.loadFormCreate();
    });

    $(".content_ajax").on('click', '.pagination a', function (e) {
        e.preventDefault();
        url = $(this).attr('href');
        index_page_role(url);
        window.history.pushState(null, '', url);
    });

    $("#table_list").on('click', '.check_permis_role', function (e) {
        var id = $(this).attr('id');
        var permission = getIdAttrRoles(id, '-', 0);
        var permission_id = getIdAttrRoles(permission, '_', 1);

        var role = getIdAttrRoles(id, '-', 1);
        var role_id = getIdAttrRoles(role, '_', 1);


        if ($(this).is(':checked')) {
            var fire = 'create';
        } else {
            var fire = 'delete';
        }
        syncPermissionRole(role_id, permission_id, fire);

    });

    $("#table_list").on('change', '.btn_check_selall', function (e) {
        check_role = $(this);
        this_role_id = getIdAttrRoles($(this).attr('id'), '-', 1);
        $("#table_list .check_permis_role").each(function (index, obj) {
            var id = $(this).attr('id');
            var permission = getIdAttrRoles(id, '-', 0);
            var permission_id = getIdAttrRoles(permission, '_', 1);
            var role = getIdAttrRoles(id, '-', 1);
            var role_id = getIdAttrRoles(role, '_', 1);
            if (this_role_id == role_id) {
                if (check_role.is(':checked')) {
                    $(this).prop('checked', true);
                    type_s = 'create';
                } else {
                    $(this).prop('checked', false);
                    type_s = 'delete';
                }

            }

        });

        change_permissions(type_s, this_role_id);
        //console.log(this_id)

    });




})();

//funcion que carga las vistas list con ajax
function index_page_role(route, request = {}) {



    $.ajax({
        url: route,
        type: 'GET',
        datatype: 'json',
        data: request,
        cache: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));

        },
        /*muestra div con mensaje de 'regristrado'*/
        success: function (res) {
            console.log(res)
            //alert('')
            $(".content_ajax").html(res);
        },
        error: function (xhr, textStatus, thrownError) {
            alert("Hubo un error con el servidor ERROR::" + thrownError, textStatus);
        }
    });
}
//funcion para obtener el id del rol-permiso
function getIdAttrRoles(id, separador, orientation) {

    value = id.split(separador)[orientation];
    return value;
}
//


function createRole() {

    var url = '/admin/roles';
    var errors = [];//validateForm('myFormCreateRole');
    if (errors.length <= 0) {
        var data = $("#myFormCreateRole").serialize();
        $.ajax({
            url: url,
            type: 'POST',
            cache: false,
            data: data,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
            },
            success: function (respu) {
                document.getElementById("myFormCreateRole").reset();
                var url = window.location.href;
                index_page_role(url)
            },
            error: function (xhr, textStatus, thrownError) {
            }
        });
    }

}

function updateRole(id) {

    var url = '/admin/roles/' + id;
    var errors = [];//validateForm('myFormCreateRole');
    if (errors.length <= 0) {
        var data = $("#myFormEditRole").serialize();
        $.ajax({
            url: url,
            type: 'PUT',
            cache: false,
            data: data,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
            },
            success: function (respu) {
                role = new UIAdminRoles();
                role.form = 'myFormCreateRole';
                role.loadFormCreate();
                var url = window.location.href;
                index_page_role(url)
            },
            error: function (xhr, textStatus, thrownError) {
            }
        });
    }

}

function searchRole(id) {

    var url = '/admin/roles/' + id;
    var errors = [];//validateForm('myFormCreateRole');
    if (errors.length <= 0) {
        var data = {};
        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            data: data,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
            },
            success: function (respu) {
                role = new UIAdminRoles(respu.id, respu.name, respu.display_name, respu.description);
                role.form = 'myFormEditRole';
                role.loadFormEdit();
            },
            error: function (xhr, textStatus, thrownError) {
            }
        });
    }
}

function searchPermission(id) {

    var url = '/admin/permisos/' + id;
    var errors = [];//validateForm('myFormCreateRole');
    if (errors.length <= 0) {
        var data = {};
        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            data: data,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
            },
            success: function (respu) {
                permiso = new UIAdminRoles(respu.id, respu.name, respu.display_name, respu.description);
                permiso.form = 'myFormEditPermission';
                permiso.loadFormEdit();
            },
            error: function (xhr, textStatus, thrownError) {
            }
        });
    }
}

function createPermission() {
    var url = '/admin/permisos';
    var errors = [];//validateForm('myFormCreateRole');
    if (errors.length <= 0) {
        var data = $("#myFormCreatePermission").serialize();
        $.ajax({
            url: url,
            type: 'POST',
            cache: false,
            data: data,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
            },
            success: function (respu) {
                document.getElementById("myFormCreatePermission").reset();
                var url = window.location.href;
                index_page_role(url)
            },
            error: function (xhr, textStatus, thrownError) {
            }
        });
    }
}

function updatePermission(id) {
    var url = '/admin/permisos/' + id;
    var errors = [];//validateForm('myFormCreateRole');
    if (errors.length <= 0) {
        var data = $("#myFormEditPermission").serialize();
        $.ajax({
            url: url,
            type: 'PUT',
            cache: false,
            data: data,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
            },
            success: function (respu) {
                permiso = new UIAdminRoles();
                permiso.form = 'myFormCreatePermission';
                permiso.loadFormCreate();
                var url = window.location.href;
                index_page_role(url)
            },
            error: function (xhr, textStatus, thrownError) {
            }
        });
    }

}
function getRolesPermissions() {
    url = '/admin/asig';
    $.ajax({
        url: url,
        type: 'GET',
        cache: false,
        //data: {'role_id':role_id,'permission_id':permission_id,'fire':fire},                
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
            //$("#preloader").show();
            //showPreloader();
        },
        success: function (respu) {
            //console.log(respu)
            llenarTablaListRole(respu);
            getPermissionsRole();
            /*   llenartablapermissions(respu.permissions);
              llenartablaroles(respu.roles);
              getPermissionsRole();
              getUsers(); */
            //llenartablapermissions(respu)	              	

        },
        error: function (xhr, textStatus, thrownError) {
        }
    });
}

function llenarTablaListRole(respu) {

    $("#table_list tbody").html('');
    $("#table_list thead").html('');

    var head = '';

    // ==========================================
    // PRIMERA FILA
    // ==========================================



head += '<tr class="tabla-header">';

// Permisos ocupa las dos filas
head += '<th rowspan="2" class="permiso-header">';
head += 'Permisos';
head += '</th>';

// Título Roles
head += '<th colspan="' + respu.roles.length + '" class="roles-header">';
head += 'Roles';
head += '</th>';

head += '</tr>';


// Segunda fila: roles
head += '<tr class="fila_roles">';

for (var i = respu.roles.length - 1; i >= 0; i--) {

    head += '<th class="rol-header">';

    head += '<div class="rol-nombre">';
    head += respu.roles[i].display_name;
    head += '</div>';

    head += '<label class="marcar-todo">';
    head += '<input ';
    head += 'type="checkbox" ';
    head += 'class="btn_check_selall" ';
    head += 'id="check_selall-' + respu.roles[i].id + '">';
    head += '<span>Marcar todo</span>';
    head += '</label>';

    head += '</th>';
}

head += '</tr>';

$("#table_list thead").html(head);


    // ==========================================
    // PERMISOS
    // ==========================================

    var row = '';

    for (var i = respu.permissions.length - 1; i >= 0; i--) {

        row += '<tr>';

        row += '<td class="permiso-cell">';

        row += '<div class="permiso-nombre">';
        row += respu.permissions[i].display_name;
        row += '</div>';

        row += '</td>';


        for (var j = respu.roles.length - 1; j >= 0; j--) {

            row += '<td class="check-cell">';

            row += '<input ';
            row += 'type="checkbox" ';
            row += 'class="check_permis_role" ';
            row += 'id="permission_' +
                respu.permissions[i].id +
                '-role_' +
                respu.roles[j].id +
                '">';

            row += '</td>';
        }

        row += '</tr>';
    }


    $("#table_list tbody").html(row);
}

function getPermissionsRole() {

    url = '/admin/get/sync/permissions';
    $.ajax({
        url: url,
        type: 'POST',
        cache: false,
        //data: {'role_id':role_id,'permission_id':permission_id,'fire':fire},                
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
            //$("#preloader").show();
            //showPreloader();
        },
        success: function (respu) {

            for (var i = respu.length - 1; i >= 0; i--) {
                $("#table_list .check_permis_role").each(function (index, obj) {
                    var id = $(this).attr('id');
                    var permission = getIdAttrRoles(id, '-', 0);
                    var permission_id = getIdAttrRoles(permission, '_', 1);
                    var role = getIdAttrRoles(id, '-', 1);
                    var role_id = getIdAttrRoles(role, '_', 1);
                    if (respu[i].id == role_id) {
                        for (var j = respu[i].permissions.length - 1; j >= 0; j--) {
                            if (respu[i].permissions[j].id == permission_id) {
                                $(this).prop('checked', true);
                            }
                        }

                    }

                });
            }

        },
        error: function (xhr, textStatus, thrownError) {
        }
    });
}

function syncPermissionRole(role_id, permission_id, fire) {
    url = '/admin/sync/permission';
    $.ajax({
        url: url,
        type: 'POST',
        cache: false,
        data: { 'role_id': role_id, 'permission_id': permission_id, 'fire': fire },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));

        },
        success: function (respu) {

        },
        error: function (xhr, textStatus, thrownError) {
        }
    });
}

function change_permissions(type_s, role_id) {
    url = '/admin/permissions/change';
    $.ajax({
        url: url,
        type: 'POST',
        cache: false,
        data: { 'type_s': type_s, 'role_id': role_id },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
        },
        success: function (respu) {

        },
        error: function (xhr, textStatus, thrownError) {
        }
    });
}


function sweetAlertDelete(url) {
    //const ipAPI = 'https://api.ipify.org?format=json'
    Swal.fire({
        title: '¿Está seguro de eliminar el registro',
        text: "Recuerda que no se podrá recuperar el registro!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar!',
        cancelButtonText: 'No, cancelar!',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            var data = {};
            $.ajax({
                url: url,
                type: 'DELETE',
                cache: false,
                data: data,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
                },
                success: function (respu) {
                    var url = window.location.href;
                    index_page_role(url);
                    Swal.fire(
                        'Eliminado!',
                        'El registro ha sido eliminado con éxito.',
                        'success'
                    )

                },
                error: function (xhr, textStatus, thrownError) {
                }
            });
        }
    })/* .then((result) => {
        if (result.value) {
            Swal.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
            )
        }
    }) */
}