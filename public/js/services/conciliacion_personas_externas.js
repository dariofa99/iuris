class ConciliacionPersonasExternasService {
    questions = [];
    constructor() {

    }

    async getByRefDataFilter(request) {
        const response = await fetch(BASE_URL + "conciliacion/personas/externas/get/categorias/by/filter/?" + new URLSearchParams(request), {
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


     async addPreguntasForm(request) {
        const response = await fetch(BASE_URL + "conciliacion/personas/externas/add/preguntas", {
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


}

export { ConciliacionPersonasExternasService };