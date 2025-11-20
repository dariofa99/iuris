export class IncidenciasService {

    async getByAsigCaso(id) {

        const response = await fetch(BASE_URL + 'incidencias/by/asignacion/' + id, {
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


    async store(request) {
        const response = await fetch(BASE_URL + 'incidencias', {
            method: 'POST',
            headers: {
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": $("#token").attr("content"),
                // ❌ NO pongas Content-Type aquí.
                // Fetch lo agrega automáticamente cuando es FormData.
            },
            body: request
        });
        if (!response.ok) {
            const message = `An error has occured: ${response.status}`;
            console.log(response);
            throw new Error(message);
        }
        const topics = await response.json();
        return topics;

    }

    async update(request) {
        const response = await fetch(BASE_URL + 'incidencias/' + request.id, {
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

