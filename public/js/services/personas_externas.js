class PersonasExternasService {
    questions = [];
    constructor() {
        if (localStorage.getItem("questions")) {
            var ques = JSON.parse(localStorage.getItem("questions"));
            if (ques.length > 0) {
                this.questions = ques
            }
        }

    }

    async store(request) {
        const response = await fetch(BASE_URL + "personas/externas/store", {
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

    async update(request, id) {
        const response = await fetch(BASE_URL + "encuesta/general/update/" + id, {
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

    getQuestions() {
        return this.questions
    }

    setQuestion(question) {
        this.questions.push(question);
        localStorage.setItem("questions", JSON.stringify(this.questions))
    }

    async getQuestionsById(id) {
        const response = await fetch(BASE_URL + "personas/externas/preguntas/" + id, {
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

    async storeReferencesData(request) {
        const response = await fetch(BASE_URL + 'personas/externas/insert/categoria', {
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

    async buscarConciliaciones(request) {
        const response = await fetch(BASE_URL + "conciliacion/evaluar/buscar?" + new URLSearchParams(request), {
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

    async storeEncuSatisf(request) {
        const response = await fetch(BASE_URL + "conciliacion/evaluar/store", {
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

    async updateEncuSatisf(request) {
        const response = await fetch(BASE_URL + "conciliacion/evaluar/update", {
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

    async updateEncuSatisfExp(request) {
        const response = await fetch(BASE_URL + "expedientes/evaluar/update", {
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

    async index_pagination(route) {
        const page = route;
        const response = await fetch(page, {
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
    async findUser(request) {
        const response = await fetch(BASE_URL + "encuestas/find/user?" + new URLSearchParams(request), {
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
    async getChartDataExp(request) {
        const response = await fetch(BASE_URL + "expedientes/evaluar/data/chart?" + new URLSearchParams(request), {
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
    async getChartData(request) {
        const response = await fetch(BASE_URL + "conciliacion/evaluar/data/chart?" + new URLSearchParams(request), {
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

    async storeExpEncuSatisf(request) {
        const response = await fetch(BASE_URL + "expedientes/evaluar/store", {
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
    async addPreguntasEncuesta(request) {
        const response = await fetch(BASE_URL + "encuesta/add/preguntas", {
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

    async deleteReferencesDataInSurvey(request, id) {
        const response = await fetch(BASE_URL + 'encuesta/delete/pregunta/' + id, {
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
export { PersonasExternasService }