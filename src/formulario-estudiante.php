<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMULARIO DE ESTUDIANTE</title>
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
            <div class="top-text">FORMULARIO ESTUDIANTE</div>
            <div class="bottom-text">CARNET DIGITAL</div>
        </div>
    </div>

    <div class="container">
       <!-- Formulario para Estudiante -->
       <div id="form-estudiante" class="form-container">
            <h2>Agregar Estudiante</h2>
            <form action="procesar.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="tipo" value="estudiante">
                <div class="form-group">
                    <label for="id_cedula">ID de Cédula:</label>
                    <input type="number" id="id_cedula" name="id_cedula" required>
                </div>
                <div class="form-group">
                    <label for="fotografia">Fotografía:</label>
                    <input type="file" name="fotografia" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="nombre_estudiante">Nombre:</label>
                    <input type="text" id="nombre_estudiante" name="nombre_estudiante" required>
                </div>
                <div class="form-group">
                    <label for="celular">Celular:</label>
                    <input type="text" id="celular" name="celular">
                </div>
                <div class="form-group">
                    <label for="correo_institucional">Correo Institucional:</label>
                    <input type="email" id="correo_institucional" name="correo_institucional">
                </div>
                <button type="submit" class="submit-btn">Agregar Estudiante</button>
            </form>
        </div>


    </div>
</body>
</html>