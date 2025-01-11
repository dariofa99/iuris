class AgendasService {

    async searchCitasOfDay(){
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
}
export {AgendasService}