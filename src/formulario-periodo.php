<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMULARIO DE PERIODO</title>
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
            <div class="top-text">FORMULARIO PERIODO</div>
            <div class="bottom-text">CARNET DIGITAL</div>
        </div>
    </div>

    <div class="container">
        <!-- Formulario para Periodo -->
        <div id="form-periodo" class="form-container">
            <h2>Agregar Periodo</h2>
            <form action="procesar.php" method="POST">
                <input type="hidden" name="tipo" value="periodo">
                <div class="form-group">
                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" required>
                </div>
                <div class="form-group">
                <label for="fecha_fin">Fecha de Fin:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" required>
                </div>
                <button type="submit" class="submit-btn">Agregar Periodo</button>
            </form>
        </div>


    </div>
</body>
</html>