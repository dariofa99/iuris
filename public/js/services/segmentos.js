class SegmentosService {
    async store(request) {
        const response = await fetch(BASE_URL + 'periodos', {
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
    async changeState(id){
        const response = await fetch(BASE_URL + "segmentos/change/state/" + id, {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": $("#token").attr("content"),
            },
            body: JSON.stringify({})
        });
        if (!response.ok) {
            const message = `An error has occured: ${response.status}`;
            console.log(response);
            throw new Error(message);
        }
        const topics = await response.json();
        return topics;
    }
    async edit(id){
        const response = await fetch(BASE_URL + "segmentos/" + id, {
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
    async update(request,id){
        const response = await fetch(BASE_URL + "segmentos/" + id, {
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
    
}
export {SegmentosService}