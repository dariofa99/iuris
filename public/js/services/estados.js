class EstadosService {
    async getEstadosForExpediente (request)  {
        const response = await fetch(BASE_URL+'obtener/estados/expedientes?'+ new URLSearchParams(request),{
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
    
}
export {EstadosService}