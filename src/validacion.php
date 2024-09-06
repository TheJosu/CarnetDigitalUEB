<?php
// Sanitizar el parámetro GET usando FILTER_SANITIZE_FULL_SPECIAL_CHARS en lugar de FILTER_SANITIZE_STRING
$ci = filter_var($_GET['ci'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

include 'config/database.php';

// No hay connect_error en PDO, así que no se necesita esta comprobación
try {
    // Preparar y ejecutar la consulta
    $sql = "SELECT e.*, f.nombre_facultad, c.nombre_carrera, cl.nombre_ciclo, p.fecha_inicio, p.fecha_fin 
            FROM estudiante e
            JOIN facultad f ON e.id_facultad = f.id_facultad
            JOIN carrera c ON e.id_carrera = c.id_carrera
            JOIN ciclo cl ON e.id_ciclo = cl.id_ciclo
            JOIN periodo p ON e.id_periodo = p.id_periodo
            WHERE e.id_cedula = :id_cedula";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_cedula', $ci, PDO::PARAM_INT);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        // Función para obtener el nombre del mes en español
        function getMesEnEspanol($mes) {
            $meses = array(
                1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            );
            return $meses[(int)$mes];
        }

        // Formatear el periodo académico en español
        $fecha_inicio = new DateTime($student['fecha_inicio']);
        $fecha_fin = new DateTime($student['fecha_fin']);
        $mes_inicio = getMesEnEspanol($fecha_inicio->format('n')); // 'n' da el mes sin ceros a la izquierda
        $mes_fin = getMesEnEspanol($fecha_fin->format('n'));
        $periodo_academico = $mes_inicio . " " . $fecha_inicio->format('Y') . " - " . $mes_fin . " " . $fecha_fin->format('Y');
        
        // Obtener el nombre de la fotografía desde la base de datos
        $fotografia = $student['fotografia'];

        if (!empty($fotografia)) {
            $fotoPath = 'uploads/' . $fotografia; // Ruta completa a la imagen
        } else {
            // Mostrar una página de error amigable o redirigir
            echo "Estudiante no encontrado.";
            exit; // Asegúrate de detener la ejecución en caso de error
        }
    } else {
        echo "Estudiante no encontrado.";
        exit; // Asegúrate de detener la ejecución en caso de error
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validación del Carnet Digital</title>
    <link rel="stylesheet" href="../public/css/validacionueb.css">
</head>
<body>
    <!-- Agregar el logotipo de la UEB -->
    <div class="header-logo">
        <a href="../INDEX.html" width="150px";><img src="img/logotipo-ueb2.png" alt="Logotipo UEB"></a>
        <div class="text-container">
            <div class="top-text">VALIDACIÓN DE</div>
            <div class="bottom-text">CARNET DIGITAL</div>
        </div>
    </div>

    <div class="container">
        <center><h2>Información del Carnet Digital</h2></center>

        <div style="display: flex; align-items: center;">
            <!-- Foto del estudiante -->
            <div style="flex: 0 0 150px; margin-right: 20px;">
                <?php if (!empty($fotografia) && file_exists($fotoPath)): ?>
                    <img src="<?php echo htmlspecialchars($fotoPath); ?>" alt="Fotografía del Estudiante" style="width: 150px; height: 180px;">
                <?php else: ?>
                    <p>No se encontró la fotografía del estudiante.</p>
                <?php endif; ?>
            </div>

            <!-- Información del estudiante -->
            <div style="flex: 1;">
                <p>Nombre: <?php echo htmlspecialchars($student['nombre_estudiante']); ?></p>
                <p>Cédula: <?php echo htmlspecialchars($student['id_cedula']); ?></p>
                <p>Rol: <?php echo htmlspecialchars($student['rol']); ?></p>
                <p>Celular: <?php echo htmlspecialchars($student['celular']); ?></p>
                <p>Correo: <?php echo htmlspecialchars($student['correo_institucional']); ?></p>
                <p>Modalidad: <?php echo htmlspecialchars($student['modalidad']); ?></p>
                <p>Facultad: <?php echo htmlspecialchars($student['nombre_facultad']); ?></p>
                <p>Carrera: <?php echo htmlspecialchars($student['nombre_carrera']); ?></p>
                <p>Ciclo: <?php echo htmlspecialchars($student['nombre_ciclo']); ?></p>
                <p>Periodo Académico: <?php echo htmlspecialchars($periodo_academico); ?></p>
            </div>
        </div>
    </div>
</body>
</html>
