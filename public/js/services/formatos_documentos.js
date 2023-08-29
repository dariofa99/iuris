export class FormatosService {


    async getReportes(request) {
        const response = await fetch(BASE_URL + 'pdf/reportes/get?' + new URLSearchParams(request), {
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

    async storePdfReporte(request) {
        const response = await fetch(BASE_URL+'pdf/reportes', {
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

    async editPdfReporte(id){
        const response = await fetch(BASE_URL + "pdf/reportes/" + id + "/edit", {
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

    async updatePdfReporte(request, id){
        const response = await fetch(BASE_URL+"pdf/reportes/" + id, {
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
    async createPdfPreview(request, id){
        const response = await fetch(BASE_URL+"pdf/reportes/preview", {
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

    async asignarReporte(request){
        const response = await fetch(BASE_URL + 'pdf/reportes/asignar', {
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
}


