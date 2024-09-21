<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMULARIO DE MATRICULA</title>
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
            <div class="top-text">FORMULARIO DE MATRICULA</div>
            <div class="bottom-text">CARNET DIGITAL</div>
        </div>
    </div>

    <div class="container">
       <!-- Formulario para Matricula -->
       <div id="form-matricula" class="form-container">
            <h2>Matricular Estudiante</h2>
            <form action="procesar.php" method="POST">
                <input type="hidden" name="tipo" value="matricula">
                <div class="form-group">
                    <label for="id_cedula">CI del Estudiante:</label>
                    <input type="text" id="id_cedula" name="id_cedula" pattern="^\d{10}$" title="La cédula debe tener exactamente 10 dígitos" required>
                </div>
                <div class="form-group">
                    <label for="id_carrera">Carrera:</label>
                    <select id="id_carrera" name="id_carrera" required>
                        <option value="">Seleccione</option>
                        <?php
                        include 'config/database.php';
                        $sql = "SELECT id_carrera, nombre_carrera FROM carrera";
                        $stmt = $conn->query($sql);
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value=\"" . htmlspecialchars($row['id_carrera']) . "\">" . htmlspecialchars($row['nombre_carrera']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_ciclo">Ciclo:</label>
                    <select id="id_ciclo" name="id_ciclo" required>
                        <option value="">Seleccione</option>
                        <?php
                        include 'config/database.php';
                        $sql = "SELECT id_ciclo, nombre_ciclo FROM ciclo";
                        $stmt = $conn->query($sql);
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value=\"" . htmlspecialchars($row['id_ciclo']) . "\">" . htmlspecialchars($row['nombre_ciclo']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_periodo">Periodo:</label>
                    <select id="id_periodo" name="id_periodo" required>
                        <option value="">Seleccione</option>
                        <?php
                        include 'config/database.php';
                        $sql = "SELECT id_periodo, fecha_inicio, fecha_fin FROM periodo";
                        $stmt = $conn->query($sql);
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $inicio = new DateTime($row['fecha_inicio']);
                            $fin = new DateTime($row['fecha_fin']);
                            $periodo = $inicio->format('F Y') . " - " . $fin->format('F Y');
                            echo "<option value=\"" . htmlspecialchars($row['id_periodo']) . "\">" . htmlspecialchars($periodo) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="submit-btn">Matricular Estudiante</button>
            </form>
        </div>

    </div>
</body>
</html>