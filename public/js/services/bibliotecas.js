class BibliotecasService {

    async store(formData) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', BASE_URL + 'bibliotecas', true);
            xhr.setRequestHeader('X-CSRF-Token', $("#token").attr("content")); // Agrega la cabecera X-CSRF-Token
            xhr.upload.addEventListener('progress', (event) => {
                if (event.lengthComputable) {
                    const percentage = (event.loaded / event.total) * 100;
                    this.showProgress(percentage); 
                }
            });
            xhr.onload = () => {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        resolve(response);
                    } catch (error) {
                        reject(new Error('Error al analizar la respuesta JSON'));
                    }
                } else {
                    reject(new Error(`Upload failed with status: ${xhr.status}`));
                }
            };
            xhr.onerror = () => {
                reject(new Error('Upload failed'));
            };
            xhr.send(formData);
        });
    }

    async update(formData) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', BASE_URL + 'bibliotecas/update', true);
            xhr.setRequestHeader('X-CSRF-Token', $("#token").attr("content")); // Agrega la cabecera X-CSRF-Token
            xhr.upload.addEventListener('progress', (event) => {
                if (event.lengthComputable) {
                    const percentage = (event.loaded / event.total) * 100;
                    this.showProgress(percentage); 
                }
            });
            xhr.onload = () => {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        resolve(response);
                    } catch (error) {
                        reject(new Error('Error al analizar la respuesta JSON'));
                    }
                } else {
                    reject(new Error(`Upload failed with status: ${xhr.status}`));
                }
            };
            xhr.onerror = () => {
                reject(new Error('Upload failed'));
            };
            xhr.send(formData);
        });
    }

    async edit(id){
        const response = await fetch(BASE_URL + "bibliotecas/" + id + "/edit", {
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

    showProgress(percentage) {
        const progressDiv = document.getElementById('progressbarwait');
        $(progressDiv).show();
        progressDiv.textContent = `${parseInt(percentage)}%`;
        progressDiv.style.width = `${parseInt(percentage)}%`;
    
    }
    
}
export {BibliotecasService}