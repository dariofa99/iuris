export class SolicitudesService{

    async solicitar (request)  {
           const response = await fetch(BASE_URL+'solicitudes', {
               method: 'POST',
               headers: {        
                   "Content-Type": "application/json",
                   "Accept": "application/json", 
                   "X-Requested-With": "XMLHttpRequest",         
                   "X-CSRF-Token": $("#token").attr("content"),             
               },            
               body: JSON.stringify(request)
           });
           if (response.status === 422) {
            const errorData = await response.json();
            if(errorData.errors){   
                Swal.close();                       
                errorData.errors.forEach(error => {            
                    toastr.error(error, "", {
                        positionClass: "toast-top-right",
                        timeOut: "4000",
                    });          
            });            
            }
            console.log("Errores de validación:", errorData.errors);
            // Puedes manejar los errores de validación según tus necesidades, como mostrar mensajes de error al usuario.
          } else if (response.status === 400) {
            // Error de solicitud (código 400)
            console.error('Error de solicitud:', response.statusText);
            // Puedes realizar acciones adicionales en caso de error, como mostrar un mensaje de error al usuario.
          }
        
           if (!response.ok) {
               const message = `An error has occured: ${response.status}`;
               console.log(response);
               throw new Error(message);
           }
           const topics = await response.json();
           return topics;
   
       }
       
   
   }