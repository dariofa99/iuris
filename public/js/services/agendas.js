class AgendasService {

    async searchCitasOfDay() {
        const response = await fetch(BASE_URL + "search/citas/of/day", {
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
            throw new Error(message);
        }
        const topics = await response.json();
        return topics;
    }

    async agendarTurnoDocente(request) {
        const response = await fetch(BASE_URL + "agendar/turnos/estudiantes", {
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
            throw new Error(message);
        }
        const topics = await response.json();
        return topics;
    }


    async notificarTurnoDocente(request) {
        const response = await fetch(BASE_URL + "notificar/turnos/estudiantes", {
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
            throw new Error(message);
        }
        const topics = await response.json();
        return topics;
    }


    async eliminarTurnoDocente(id) {
        const response = await fetch(BASE_URL + "eliminar/turnos/estudiantes/" + id, {
            method: 'DELETE',
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": $("#token").attr("content"),
            }
        });
        if (!response.ok) {
            const message = `An error has occured: ${response.status}`;
            throw new Error(message);
        }
        const topics = await response.json();
        return topics;
    }

    async actualizarTurnoDocente(request) {
        const response = await fetch(BASE_URL + "actualizar/turnos/estudiantes/" + request.turno_id, {
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
            throw new Error(message);
        }
        const topics = await response.json();
        return topics;
    }
}
export { AgendasService }