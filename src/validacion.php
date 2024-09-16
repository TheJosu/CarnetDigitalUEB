<?php
$ci = filter_var($_GET['ci'], FILTER_SANITIZE_STRING); // Sanitizar el parámetro GET

include 'config/database.php';

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL corregida
$sql = "SELECT e.*, c.nombre_carrera, c.modalidad, f.nombre_facultad, cl.nombre_ciclo, p.fecha_inicio, p.fecha_fin 
        FROM estudiante e
        JOIN matricula m ON e.id_cedula = m.id_cedula
        JOIN carrera c ON m.id_carrera = c.id_carrera
        JOIN facultad f ON c.id_facultad = f.id_facultad
        JOIN ciclo cl ON m.id_ciclo = cl.id_ciclo
        JOIN periodo p ON m.id_periodo = p.id_periodo
        WHERE e.id_cedula = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ci);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
    
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
    // Si no se encuentra el estudiante
    echo "Estudiante no encontrado.";
    exit;
}

$stmt->close();
$conn->close();
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
                    <img src="<?php echo $fotoPath; ?>" alt="Fotografía del Estudiante" style="width: 150px; height: 180px;">
                <?php else: ?>
                    <p>No se encontró la fotografía del estudiante.</p>
                <?php endif; ?>
            </div>

            <!-- Información del estudiante -->
            <div style="flex: 1;">
                <p>Nombre: <?php echo htmlspecialchars($student['nombre_estudiante']); ?></p>
                <p>Cédula: <?php echo htmlspecialchars($student['id_cedula']); ?></p>
                <p>Rol: Estudiante</p> <!-- Rol siempre será "Estudiante" -->
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
