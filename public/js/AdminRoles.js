class AdminRoles {

    syncRolPermissions(request){
        var url = '/admin/sync/rol/permissions';
        $.ajax({
            url: url,
            type: 'POST',
            cache: false,
            data: request,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));    
            },
            success: function (respu) {    
            },
            error: function (xhr, textStatus, thrownError) {
            }
        });
    }

    
    updateRole(request){
        var url = '/admin/update/role';
        $.ajax({
            url: url,
            type: 'POST',
            cache: false,
            data: request,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));    
            },
            success: function (respu) {    
                if(respu.view || respu.view == '') {
                    $("#content_role_ajax").html(respu.view);
                    resetRoleForm();
                }
            },
            error: function (xhr, textStatus, thrownError) {
            }
        });
    }

    storeRole(request){
        var url = '/admin/store/role';
        $.ajax({
            url: url,
            type: 'POST',
            cache: false,
            data: request,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));    
            },
            success: function (respu) {   
                if(respu.view || respu.view == '') {
                    $("#content_role_ajax").html(respu.view);
                    resetRoleForm();
                }
            },
            error: function (xhr, textStatus, thrownError) {
            }
        });
    }

  deleteRole(role_id){
        var url = '/admin/delete/role';
        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            data: {'id':role_id},
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));    
            },
            success: function (respu) {   
                if(respu.view || respu.view == '') {
                    $("#content_role_ajax").html(respu.view);
                    resetRoleForm();
                }
            },
            error: function (xhr, textStatus, thrownError) {
            }
        });
    }
    /////////////////////

      
    updatePermission(request){
        var url = '/admin/update/permission';
        $.ajax({
            url: url,
            type: 'POST',
            cache: false,
            data: request,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));    
            },
            success: function (respu) {    
                if(respu.view || respu.view == '') {
                    $("#content_permission_ajax").html(respu.view);
                    resetPermissionForm();
                }
            },
            error: function (xhr, textStatus, thrownError) {
            }
        });
    }

    storePermission(request){
        var url = '/admin/store/permission';
        $.ajax({
            url: url,
            type: 'POST',
            cache: false,
            data: request,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));    
            },
            success: function (respu) {   
                if(respu.view || respu.view == '') {
                    $("#content_permission_ajax").html(respu.view);
                    resetPermissionForm();
                }
            },
            error: function (xhr, textStatus, thrownError) {
            }
        });
    }

  deletePermission(role_id){
        var url = '/admin/delete/permission';
        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            data: {'id':role_id},
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));    
            },
            success: function (respu) {   
                if(respu.view || respu.view == '') {
                    $("#content_permission_ajax").html(respu.view);
                    resetPermissionForm();
                }
            },
            error: function (xhr, textStatus, thrownError) {
            }
        });
    }

}

(function() {
    var adminRoles = new AdminRoles();  
    $(".check_permis_role").on("change",function (e) {
        var data_rol = $(this).attr("data-rol");
        var data_permission = $(this).attr("data-permission");
        var ismethod = 'delete';
        if($(this).is(":checked")) ismethod = 'insert';
        var request = {
            'role_id':data_rol,
            'permission_id':data_permission,
            'ismethod':ismethod
        }        
        adminRoles.syncRolPermissions(request);      
    });

    $("#content_admin_roles").on("submit",'#myFormCreateRole',function(e) {
        var request = $(this).serialize();
        adminRoles.storeRole(request);
        e.preventDefault();
    });
    $("#content_admin_roles").on("submit",'#myFormEditRole',function(e) {
        var request = $(this).serialize();
        adminRoles.updateRole(request);
        e.preventDefault();
    });
    $("#content_role_ajax").on("click",".btn_edit_rol",function (e) {
        var role_id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        $("#myFormCreateRole").attr("id","myFormEditRole");
        $("#myFormEditRole input[name=id]").val(role_id);
        $("#myFormEditRole input[name=name]").val(name);
        $("#myFormEditRole button[type=submit]").text('Actualizar').
        addClass("btn-warning").removeClass("btn-success");
        $("#myFormEditRole button[type=button]").show();        
    });

    $("#content_role_ajax").on("click",".btn_delete_rol",function (e) {  
         var role_id = $(this).attr('data-id');
        Swal.fire({
			title: 'Esta acción eliminará uno o mas registros! <br> ¿Está seguro que desea continuar?',
			text: "Los cambios no podrán ser revertidos!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si, eliminar!',
			cancelButtonText: 'No, cancelar!'
		  }).then((result) => {
			if (result.value) {
				adminRoles.deleteRole(role_id)
			}
		  });
    });

    $("#content_admin_roles").on("click","#btn_save_cancel",function (e) {
        resetRoleForm();
    });

    ///////

    $("#content_admin_permissions").on("submit",'#myFormCreatePermission',function(e) {
        var request = $(this).serialize();
        adminRoles.storePermission(request);
        e.preventDefault();
    });
    $("#content_admin_permissions").on("submit",'#myFormEditPermission',function(e) {
        var request = $(this).serialize();
        adminRoles.updatePermission(request);
        e.preventDefault();
    });
    $("#content_permission_ajax").on("click",".btn_edit_permission",function (e) {
        var role_id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        $("#myFormCreatePermission").attr("id","myFormEditPermission");
        $("#myFormEditPermission input[name=id]").val(role_id);
        $("#myFormEditPermission input[name=name]").val(name);
        $("#myFormEditPermission button[type=submit]").text('Actualizar').
        addClass("btn-warning").removeClass("btn-success");
        $("#myFormEditPermission button[type=button]").show();        
    });

    $("#content_permission_ajax").on("click",".btn_delete_permission",function (e) {  
         var role_id = $(this).attr('data-id');
        Swal.fire({
			title: 'Esta acción eliminará uno o mas registros! <br> ¿Está seguro que desea continuar?',
			text: "Los cambios no podrán ser revertidos!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si, eliminar!',
			cancelButtonText: 'No, cancelar!'
		  }).then((result) => {
			if (result.value) {
				adminRoles.deletePermission(role_id)
			}
		  });
    });

    $("#content_admin_permissions").on("click","#btn_save_cancel",function (e) {
        resetPermissionForm();
    });



})();

function resetRoleForm() {
    $("#myFormEditRole").attr("id","myFormCreateRole");
    $("#myFormCreateRole input[name=id]").val('');
    $("#myFormCreateRole input[name=name]").val('');
    $("#myFormCreateRole button[type=submit]").text('Guardar').
    addClass("btn-success").removeClass("btn-warning");
    $("#myFormCreateRole button[type=button]").hide();
}

function resetPermissionForm() {
    $("#myFormEditPermission").attr("id","myFormCreatePermission");
    $("#myFormCreatePermission input[name=id]").val('');
    $("#myFormCreatePermission input[name=name]").val('');
    $("#myFormCreatePermission button[type=submit]").text('Guardar').
    addClass("btn-success").removeClass("btn-warning");
    $("#myFormCreatePermission button[type=button]").hide();
}