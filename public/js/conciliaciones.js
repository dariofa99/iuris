export class ConciliacionService{

 async registrar_conciliacion (request)  {
        const response = await fetch(BASE_URL+'conciliaciones', {
            method: 'POST',
            headers: {        
                "Content-Type": "application/json",
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
    async addUser (request)  {
        const response = await fetch(BASE_URL+'conciliaciones/add/user', {
            method: 'POST',
            headers: {        
                "Content-Type": "application/json",
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

    async deleteConciliacionUser (request) {
        const response = await fetch(BASE_URL+'/conciliacion/delete/user?'+ new URLSearchParams(request),{
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

    async addAditionalData (request)  {
        const response = await fetch(BASE_URL+'conciliaciones/insert/data', {
            method: 'POST',
            headers: {        
                "Content-Type": "application/json",
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

    async addHechosPretenciones (request)  {
        const response = await fetch(BASE_URL+'conciliaciones/hechos/pretenciones', {
            method: 'POST',
            headers: {        
                "Content-Type": "application/json",
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

    async addFile (request)  {
        const response = await fetch(BASE_URL+'conciliaciones/store/anexo', {
            method: 'POST',
            headers: {        
                'Accept': 'application/json, application/xml, text/plain, text/html, *.*',
                "X-CSRF-Token": $("#token").attr("content"),             
            },            
            body: (request)
        });
        if (!response.ok) {
            const message = `An error has occured: ${response.status}`;
            console.log(response);
            throw new Error(message);
        }
        const topics = await response.json();
        return topics;

    }

    async deleteFile (id)  {
        const response = await fetch(BASE_URL+'conciliaciones/hechos/pretenciones/'+id, {
            method: 'DELETE',
            headers: {        
                "Content-Type": "application/json",
                "Accept": "application/json", 
                "X-Requested-With": "XMLHttpRequest",         
                "X-CSRF-Token": $("#token").attr("content")             
            }            
            
        });
        if (!response.ok) {
            const message = `An error has occured: ${response.status}`;
            console.log(response);
            throw new Error(message);
        }
        const topics = await response.json();
        return topics;

    }

    async editHechoPretension(id) {
        const response = await fetch(BASE_URL+'conciliaciones/hechos/pretenciones/'+id+'/edit',{
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

    async updateHechosPretensiones (request,id)  {
        const response = await fetch(BASE_URL+"conciliaciones/hechos/pretenciones/"+id, {
            method: 'PUT',
            headers: {        
                "Content-Type": "application/json",
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

    async deleteAnexo (request)  {
        const response = await fetch(BASE_URL+"conciliaciones/delete/anexo?"+ new URLSearchParams(request), {
            method: 'GET',
            headers: {        
                "Content-Type": "application/json",
                "Accept": "application/json", 
                "X-Requested-With": "XMLHttpRequest",         
                "X-CSRF-Token": $("#token").attr("content"),             
            }         
           
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


