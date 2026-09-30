class NotasService {
    async getNotas(request,tbl_id)  {
        const response = await fetch(BASE_URL+"/notas/" + tbl_id + "/edit?"+ new URLSearchParams(request),{
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
            $("#wait").hide()
            throw new Error(message);
        }
        const topics = await response.json();
        return topics;
    }
    
}
export {NotasService}