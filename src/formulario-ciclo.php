<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMULARIO DE CICLO</title>
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
            <div class="top-text">FORMULARIO CICLO</div>
            <div class="bottom-text">CARNET DIGITAL</div>
        </div>
    </div>

    <div class="container">
        <!-- Formulario para Ciclo -->
        <div id="form-ciclo" class="form-container">
            <h2>Agregar Ciclo</h2>
            <form action="procesar.php" method="POST">
                <input type="hidden" name="tipo" value="ciclo">
                <div class="form-group">
                    <label for="nombre_ciclo">Nombre del Ciclo:</label>
                    <input type="text" id="nombre_ciclo" name="nombre_ciclo" required>
                </div>
                <div class="form-group">
                    <label for="id_carrera">Carrera:</label>
                    <select id="id_carrera" name="id_carrera" required>
                        <option value="">Seleccione</option>
                        <?php
                        include 'config/database.php';
                        if ($conn->connect_error) {
                            die("Conexión fallida: " . $conn->connect_error);
                        }
                        $sql = "SELECT id_carrera, nombre_carrera FROM carrera";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value=\"" . $row['id_carrera'] . "\">" . $row['nombre_carrera'] . "</option>";
                            }
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <button type="submit" class="submit-btn">Agregar Ciclo</button>
            </form>
        </div>


    </div>
</body>
</html>