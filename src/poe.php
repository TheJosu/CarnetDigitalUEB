<?php
// Conectar a la base de datos
include 'config/database.php';
try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}

// Obtener la lista de carreras y ciclos
$sql_carreras = "SELECT id_carrera, nombre_carrera FROM carrera";
$sql_ciclos = "SELECT id_ciclo, nombre_ciclo FROM ciclo";
$result_carreras = $conn->query($sql_carreras)->fetchAll(PDO::FETCH_ASSOC);
$result_ciclos = $conn->query($sql_ciclos)->fetchAll(PDO::FETCH_ASSOC);

// Procesar el formulario de filtro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cedula = $_POST["id_cedula"];
    $id_carrera = $_POST["id_carrera"];
    $id_ciclo = $_POST["id_ciclo"];

    // Construir la consulta SQL
    $sql = "SELECT e.id_cedula, e.nombre_estudiante, e.correo_institucional, c.nombre_carrera, ci.nombre_ciclo 
            FROM estudiante e
            JOIN matricula m ON e.id_cedula = m.id_cedula
            JOIN carrera c ON m.id_carrera = c.id_carrera
            JOIN ciclo ci ON m.id_ciclo = ci.id_ciclo";

    $where_clauses = array();

    if (!empty($id_cedula)) {
        $where_clauses[] = "e.id_cedula = :id_cedula";
    }
    if (!empty($id_carrera)) {
        $where_clauses[] = "m.id_carrera = :id_carrera";
    }
    if (!empty($id_ciclo)) {
        $where_clauses[] = "m.id_ciclo = :id_ciclo";
    }

    if (!empty($where_clauses)) {
        $sql .= " WHERE " . implode(" AND ", $where_clauses);
    }

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros
    if (!empty($id_cedula)) {
        $stmt->bindParam(':id_cedula', $id_cedula, PDO::PARAM_STR);
    }
    if (!empty($id_carrera)) {
        $stmt->bindParam(':id_carrera', $id_carrera, PDO::PARAM_INT);
    }
    if (!empty($id_ciclo)) {
        $stmt->bindParam(':id_ciclo', $id_ciclo, PDO::PARAM_INT);
    }

    // Ejecutar la consulta
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alumnos</title>
    <link rel="stylesheet" href="../public/css/editar.css">
</head>
<body>
    <!-- Agregar el logotipo de la UEB -->
    <div class="header-logo">
        <a href="../src/formulario.php" width="50px;">
            <img src="img/logotipo-ueb2.png" alt="Logotipo UEB">
        </a>
        <div class="text-container">
            <div class="top-text">ALUMNOS DEL</div>
            <div class="bottom-text">CARNET DIGITAL</div>
        </div>
    </div>

    <div class="content-container">
        <!-- Filtros -->
        <div class="filter-container">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <label for="id_cedula">Número de Cédula:</label>
                <input type="text" id="id_cedula" name="id_cedula">
                
                <label for="id_carrera">Carrera:</label>
                <select id="id_carrera" name="id_carrera">
                    <option value="">Seleccione una carrera</option>
                    <?php
                    foreach ($result_carreras as $row) {
                        echo "<option value='" . htmlspecialchars($row["id_carrera"]) . "'>" . htmlspecialchars($row["nombre_carrera"]) . "</option>";
                    }
                    ?>
                </select>

                <label for="id_ciclo">Ciclo:</label>
                <select id="id_ciclo" name="id_ciclo">
                    <option value="">Seleccione un ciclo</option>
                    <?php
                    foreach ($result_ciclos as $row) {
                        echo "<option value='" . htmlspecialchars($row["id_ciclo"]) . "'>" . htmlspecialchars($row["nombre_ciclo"]) . "</option>";
                    }
                    ?>
                </select>

                <input type="submit" value="Filtrar">
            </form>
        </div>

        <!-- Resultados -->
        <div class="results-container">
            <?php
            if (isset($result)) {
                if (!empty($result)) {  // Verifica si hay resultados
                    echo "<table>";
                    echo "<tr><th># Cédula</th><th>Nombre</th><th>Correo Institucional</th><th>Carrera</th><th>Ciclo</th><th>Acción</th></tr>";
                    foreach ($result as $row) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["id_cedula"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["nombre_estudiante"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["correo_institucional"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["nombre_carrera"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["nombre_ciclo"]) . "</td>";
                        echo "<td><a href='editar_alumno.php?id=" . htmlspecialchars($row["id_cedula"]) . "' target='_blank'>Editar</a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No se encontraron alumnos.";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
