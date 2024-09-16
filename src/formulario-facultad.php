<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMULARIO DE FACULTAD</title>
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
            <div class="top-text">FORMULARIO FACULTAD</div>
            <div class="bottom-text">CARNET DIGITAL</div>
        </div>
    </div>

    <div class="container">
        <!-- Formulario para Facultad -->
        <div id="form-facultad" class="form-container">
            <h2>Agregar Facultad</h2>
            <form action="procesar.php" method="POST">
                <input type="hidden" name="tipo" value="facultad">
                <div class="form-group">
                    <label for="nombre_facultad">Nombre de la Facultad:</label>
                    <input type="text" id="nombre_facultad" name="nombre_facultad" required>
                </div>
                <button type="submit" class="submit-btn">Agregar Facultad</button>
            </form>
        </div>
    </div>
</body>
</html>