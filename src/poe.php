<?php
// Conectar a la base de datos
include 'config/database.php';
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener la lista de carreras y ciclos
$sql_carreras = "SELECT id_carrera, nombre_carrera FROM carrera";
$sql_ciclos = "SELECT id_ciclo, nombre_ciclo FROM ciclo";
$result_carreras = $conn->query($sql_carreras);
$result_ciclos = $conn->query($sql_ciclos);

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

    if ($id_cedula != "") {
        $where_clauses[] = "e.id_cedula = '$id_cedula'";
    }
    if ($id_carrera != "") {
        $where_clauses[] = "m.id_carrera = $id_carrera";
    }
    if ($id_ciclo != "") {
        $where_clauses[] = "m.id_ciclo = $id_ciclo";
    }

    if (!empty($where_clauses)) {
        $sql .= " WHERE " . implode(" AND ", $where_clauses);
    }

    $result = $conn->query($sql);
}
$conn->close();
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
        <a href="../src/formulario.php" width="50px;"><img src="img/logotipo-ueb2.png" alt="Logotipo UEB"></a>
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
                    if ($result_carreras->num_rows > 0) {
                        while($row = $result_carreras->fetch_assoc()) {
                            echo "<option value='" . $row["id_carrera"] . "'>" . $row["nombre_carrera"] . "</option>";
                        }
                    }
                    ?>
                </select>
                <label for="id_ciclo">Ciclo:</label>
                <select id="id_ciclo" name="id_ciclo">
                    <option value="">Seleccione un ciclo</option>
                    <?php
                    if ($result_ciclos->num_rows > 0) {
                        while($row = $result_ciclos->fetch_assoc()) {
                            echo "<option value='" . $row["id_ciclo"] . "'>" . $row["nombre_ciclo"] . "</option>";
                        }
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
                if ($result->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th># Cédula</th><th>Nombre</th><th>Correo Institucional</th><th>Carrera</th><th>Ciclo</th><th>Acción</th></tr>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id_cedula"] . "</td>";
                        echo "<td>" . $row["nombre_estudiante"] . "</td>";
                        echo "<td>" . $row["correo_institucional"] . "</td>";
                        echo "<td>" . $row["nombre_carrera"] . "</td>";
                        echo "<td>" . $row["nombre_ciclo"] . "</td>";
                        echo "<td><a href='editar_alumno.php?id=" . $row["id_cedula"] . "' target='_blank'>Editar</a></td>";
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
