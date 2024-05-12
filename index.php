<?php
$carpetaNombre = isset($_GET['nombre']) ? $_GET['nombre'] : ''; // Eliminamos esta línea
$carpetaRuta = "./descarga/" . $carpetaNombre;

try {
    if (!file_exists($carpetaRuta)) {
        mkdir($carpetaRuta, 0755, true);
        $mensaje = "Carpeta '$carpetaNombre' creada con éxito.";
    } else {
        $mensaje = "La carpeta '$carpetaNombre' ya existe.";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verificar si se enviaron archivos
        if (!empty($_FILES['archivo']['name'][0])) {
            // Iterar sobre cada archivo enviado
            foreach ($_FILES['archivo']['tmp_name'] as $key => $tmp_name) {
                $archivo = $_FILES['archivo'];

                // Verificar si se pudo mover el archivo al destino deseado
                if (move_uploaded_file($archivo['tmp_name'][$key], $carpetaRuta . '/' . $archivo['name'][$key])) {
                    $subido = true;
                    $mensaje = "Archivos subidos con éxito.";
                } else {
                    throw new Exception("Error al subir el archivo.");
                }
            }
        }

        // Verificar si se envió un nombre de archivo para eliminar
        if (isset($_POST['eliminarArchivo'])) {
            $archivoAEliminar = $_POST['eliminarArchivo'];
            $archivoRutaAEliminar = $carpetaRuta . '/' . $archivoAEliminar;

            // Verificar si es un archivo y eliminarlo
            if (is_file($archivoRutaAEliminar)) {
                if (unlink($archivoRutaAEliminar)) {
                    $mensaje = "Archivo '$archivoAEliminar' eliminado con éxito.";
                } else {
                    throw new Exception("Error al eliminar el archivo.");
                }
            } else {
                throw new Exception("El elemento '$archivoAEliminar' no es un archivo válido.");
            }
        }
    }
} catch (Exception $e) {
    $mensaje = "Error: " . htmlspecialchars($e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compartir archivos</title>
    <script src="parametro.js"></script>
    <link rel="stylesheet" href="estilo.css">
</head>

<body> 
    <h1>Compartir archivos <sup class="beta">BETA</sup></h1>
    <div class="content">
    <h3>Sube tus archivos y comparte este enlace temporal:<span style="background-color:;">ibu.pe/?nombre=<?php echo $carpetaNombre;?></span></h3></span>
        <div class="container">
            <div class="drop-area" id="drop-area">
            <form action="" id="form" method="POST" enctype="multipart/form-data">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" style="fill:#0730c5;transform: ;msFilter:;"><path d="M13 19v-4h3l-4-5-4 5h3v4z"></path><path d="M7 19h2v-2H7c-1.654 0-3-1.346-3-3 0-1.404 1.199-2.756 2.673-3.015l.581-.102.192-.558C8.149 8.274 9.895 7 12 7c2.757 0 5 2.243 5 5v1h1c1.103 0 2 .897 2 2s-.897 2-2 2h-3v2h3c2.206 0 4-1.794 4-4a4.01 4.01 0 0 0-3.056-3.888C18.507 7.67 15.56 5 12 5 9.244 5 6.85 6.611 5.757 9.15 3.609 9.792 2 11.82 2 14c0 2.757 2.243 5 5 5z"></path></svg> <br>
                <input type="file" class="file-input" name="archivo[]" id="archivo" onchange="document.getElementById('form').submit()" multiple>
                <label> Arrastra tus archivos aquí<br>o</label>
                <p><b>Abre el explorador</b></p> 
            </form>
            <div class="progress">
                <div class="bar" id="progressBar"></div>
            </div>

            </div>

            <div class="container2">
               

                <div id="file-list" class="pila">
                    <?php
                    $targetDir = $carpetaRuta;

                    $files = scandir($targetDir);
                    $files = array_diff($files, array('.', '..'));

                    if (count($files) > 0) {
                        echo " <h3 style='margin-bottom:10px;'>Archivos Subidos:</h3>";

                        foreach ($files as $file) {
                            $nombreArchivo = str_replace(' ', '_', $file);
                            echo "<div class='archivos_subidos'>
                                <div><a href='$carpetaRuta/$file' download class='boton-descargar'>$nombreArchivo</a></div>
                                <div>
                                <form action='' method='POST' style='display:inline;'>
                                    <input type='hidden' name='eliminarArchivo' value='$file'>
                                    <button type='submit' class='btn_delete'>
                                        <svg xmlns='http://www.w3.org/2000/svg' class='icon icon-tabler icon-tabler-trash' width='24' height='24' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none' stroke-linecap='round' stroke-linejoin='round'>
                                            <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                                            <path d='M4 7l16 0' />
                                            <path d='M10 11l0 6' />
                                            <path d='M14 11l0 6' />
                                            <path d='M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12' />
                                            <path d='M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3' />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            </div>";
                        }
                    } else {
                        echo "No se han subido archivos.";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript para obtener el nombre de la carpeta almacenado localmente
        document.addEventListener('DOMContentLoaded', function() {
            const carpetaNombre = localStorage.getItem('carpetaNombre');
            const spanCarpetaNombre = document.getElementById('carpeta-nombre');
            if (carpetaNombre) {
                spanCarpetaNombre.textContent = carpetaNombre;
            }
        });
    </script>

</body>

</html>