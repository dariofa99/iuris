export class UserService{

    async login (request)  {
        const response = await fetch(BASE_URL+'login', {
            method: 'POST',
            headers: {    
                "Content-Type": "application/json",
                "Accept": "application/json", 
                "X-Requested-With": "XMLHttpRequest",         
                "X-CSRF-Token": $("#token").attr("content"),      
                       
            },            
            body: (request),
            mode: 'cors'
        });
        if (!response.ok) {
            const message = `An error has occured: ${response.status}`;
            console.log(response);
            throw new Error(message);
        }
        const topics = await response.json();
        return topics;

    }
    async findUserByIdnumber (request)  {
        const response = await fetch(BASE_URL+'usuarios/buscar/persona?'+ new URLSearchParams(request),{
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
            $("#wait").hide()
            throw new Error(message);
        }
        const topics = await response.json();
        return topics;

    }

 async registrar (request)  {
        const response = await fetch(BASE_URL+'usuarios', {
            method: 'POST',
            headers: {                
                "Content-Type": "application/json",
                //'Content-Type': 'application/x-www-form-urlencoded',
                "Accept": "application/json", 
                "X-Requested-With": "XMLHttpRequest",         
                "X-CSRF-Token": $("#token").attr("content"),            
            },            
            body: JSON.stringify(request)
        });
        if (!response.ok) {
            const message = `An error has occured: ${response.status}`;
            console.log(response);
            throw new Error(message);
        }
        const topics = await response.json();
        return topics;

    }
    async update (request)  {
        const response = await fetch(BASE_URL+'usuarios/'+request.id, {
            method: 'PUT',
            headers: {                
                "Content-Type": "application/json",
                //'Content-Type': 'application/x-www-form-urlencoded',
                "Accept": "application/json", 
                "X-Requested-With": "XMLHttpRequest",         
                "X-CSRF-Token": $("#token").attr("content"),            
            },            
            body: JSON.stringify(request)
        });
        if (!response.ok) {
            const message = `An error has occured: ${response.status}`;
            console.log(response);
            throw new Error(message);
        }
        const topics = await response.json();
        return topics;

    }
    async alertValidateUser(lastidnumber,form) {
        if(lastidnumber!='' && $("select[name='tipodoc_id']").val()!='' ){          
            Swal.fire({
            title: 'Vuelve a ingresar el número de documento',
            input: 'text',
            inputAttributes: {
              autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Validar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: async (idnumber) => {
              if(lastidnumber==idnumber){
               let request = {
                  "tipodoc_id":$("#"+form+" select[name='tipodoc_id']").val(),
                  "idnumber":idnumber
               }
                let response = await this.findUserByIdnumber(request);
                if(response.encontrado){
                  $("#"+form+" input[name='id']").remove();
                  $("#"+form).append($('<input>',{
                    type:"hidden", 
                    name:"id",
                    value:response.user.id
                  }));
                  $("#"+form+" input[name='idnumber']").val(response.user.idnumber).prop("disabled",true);
                  $("#"+form+" input[name='name']").val(response.user.name).prop("disabled",true);
                  $("#"+form+" input[name='lastname']").val(response.user.lastname).prop("disabled",true);
                  $("#"+form+" input[name='tel1']").val(response.user.tel1).prop("disabled",true)
                  $("#"+form+" input[name='address']").val(response.user.address).prop("disabled",true);
                  $("#"+form+" select[name='tipopers_id']").val(response.user.tipopers_id).prop("disabled",true)
                  $("#"+form+" input[name='email']").val(response.user.email).prop("disabled",true)
                  $("#"+form+" select[name='genero_id']").val(response.user.genero_id).prop("disabled",true)
                }else{
                  $("#"+form+" input[name='id']").remove();
                  $("#"+form+" input[name='idnumber']").val(lastidnumber);
                  $("#"+form+" input[name='name']").val("");
                  $("#"+form+" input[name='lastname']").val("");
                  $("#"+form+" input[name='tel1']").val("");
                  $("#"+form+" input[name='address']").val("");
                }
              }else{
                toastr.info("Los valores no coinciden", "!Atención", {
                  positionClass: "toast-top-right",
                  timeOut: "4000"});
                  $("#"+form+" input[name='id']").remove();
                  $("#"+form+" input[name='name']").val("");
                  $("#"+form+" input[name='lastname']").val("");
                  $("#"+form+" input[name='tel1']").val("");
                  $("#"+form+" input[name='address']").val("");
              }
              
            },
            allowOutsideClick: () => !Swal.isLoading()
          });
        }
    }
   
    async findUserByNameOrLastNameAndRole (request)  {
      const response = await fetch(BASE_URL+'usuarios/find/by/name?'+ new URLSearchParams(request),{
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
          throw new Error(message);
      }
      const res = await response.json();
      return res;

  }

  async addSede (request)  {
    const response = await fetch(BASE_URL+'usuarios/add/sede', {
        method: 'POST',
        headers: {                
            "Content-Type": "application/json",
            //'Content-Type': 'application/x-www-form-urlencoded',
            "Accept": "application/json", 
            "X-Requested-With": "XMLHttpRequest",         
            "X-CSRF-Token": $("#token").attr("content"),            
        },            
        body: JSON.stringify(request)
    });
    if (!response.ok) {
        const message = `An error has occured: ${response.status}`;
        console.log(response);
        throw new Error(message);
    }
    const topics = await response.json();
    return topics;

}
}


