<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMULARIO DE SUBIDA DE ARCHIVOS</title>
    <link rel="stylesheet" href="../public/css/formularios.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap" rel="stylesheet">
    
</head>
<body>
    <!-- Agregar el logotipo de la UEB -->
    <div class="header-logo">
        <a href="../src/principal.php"><img src="img/logotipo-ueb2.png" alt="Logotipo UEB"></a>
        <div class="text-container">
            <div class="top-text">SUBIDA DE ARCHIVOS</div>
            <div class="bottom-text">CARNET DIGITAL</div>
        </div>
    </div>

    <div class="container">
        <!-- Formulario para Subida de datos Masivamente -->
        <div id="form-masivo" class="form-container">
            <h2>Carga Masiva de Datos</h2>
            <form action="procesar_masivo.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="archivo">Archivo Excel (.xls/.xlsx):</label>
                    <input type="file" id="archivo" name="archivo" accept=".xls,.xlsx" required>
                </div>
                
                <button type="submit" class="submit-btn">Cargar Datos</button>
            </form>
        </div>

    </div>
</body>
</html>