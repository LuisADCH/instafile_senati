// upload.js
function uploadFile() {
    var fileInput = document.getElementById('archivo');
    var files = fileInput.files;
    var progressBar = document.getElementById('progressBar');
    var formData = new FormData();

    for (var i = 0; i < files.length; i++) {
        formData.append('archivo[]', files[i]);
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '', true);
    
    xhr.upload.onprogress = function(event) {
        if (event.lengthComputable) {
            var percentComplete = (event.loaded / event.total) * 100;
            progressBar.style.width = percentComplete + '%';
            console.log('Progreso de carga:', percentComplete + '%');
        }
    };
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Archivos subidos con éxito');
            // Actualiza la interfaz o realiza otras acciones si es necesario
        } else {
            console.error('Error al subir archivos');
        }
    };
    
    xhr.onerror = function() {
        console.error('Error de red al subir archivos');
    };
    
    xhr.send(formData);
}
