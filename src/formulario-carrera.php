<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMULARIO DE CARRERA</title>
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
            <div class="top-text">FORMULARIO CARRERA</div>
            <div class="bottom-text">CARNET DIGITAL</div>
        </div>
    </div>

    <div class="container">
        <!-- Formulario para Carrera -->
        <div id="form-carrera" class="form-container">
            <h2>Agregar Carrera</h2>
            <form action="procesar.php" method="POST">
                <input type="hidden" name="tipo" value="carrera">
                <div class="form-group">
                    <label for="nombre_carrera">Nombre de la Carrera:</label>
                    <input type="text" id="nombre_carrera" name="nombre_carrera" required>
                </div>
                <div class="form-group">
                    <label for="id_facultad">Facultad:</label>
                    <select id="id_facultad" name="id_facultad" required>
                        <option value="">Seleccione</option>
                        <?php
                        include 'config/database.php';
                        if ($conn->connect_error) {
                            die("Conexión fallida: " . $conn->connect_error);
                        }
                        $sql = "SELECT id_facultad, nombre_facultad FROM facultad";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value=\"" . $row['id_facultad'] . "\">" . $row['nombre_facultad'] . "</option>";
                            }
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="modalidad">Modalidad:</label>
                    <select id="modalidad" name="modalidad" required>
                        <option value="">Seleccione</option>
                        <option value="Presencial">Presencial</option>
                        <option value="Linea">Linea</option>
                        <option value="Hibrida">Hibrida</option>
                    </select>
                </div>
                <button type="submit" class="submit-btn">Agregar Carrera</button>
            </form>
        </div>
    </div>
</body>
</html>