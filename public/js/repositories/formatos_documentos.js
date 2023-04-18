export class FormatosService{

    
       async getReportes (request) {
           const response = await fetch(BASE_URL+'pdf/reportes/get?'+ new URLSearchParams(request),{
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
           return topics;
       }
   
      
   }
   
   
   