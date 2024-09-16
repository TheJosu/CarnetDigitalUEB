<?php
// Sanitizar el parámetro GET
$ci = htmlspecialchars($_GET['ci'], ENT_QUOTES, 'UTF-8');

// Incluir el archivo de configuración de base de datos
include 'config/database.php';

try {
    // Conexión ya realizada en database.php, no es necesario crearla nuevamente
    // $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}

// Consulta SQL corregida
$sql = "SELECT e.*, c.nombre_carrera, c.modalidad, f.nombre_facultad, cl.nombre_ciclo, p.fecha_inicio, p.fecha_fin 
        FROM estudiante e
        JOIN matricula m ON e.id_cedula = m.id_cedula
        JOIN carrera c ON m.id_carrera = c.id_carrera
        JOIN facultad f ON c.id_facultad = f.id_facultad
        JOIN ciclo cl ON m.id_ciclo = cl.id_ciclo
        JOIN periodo p ON m.id_periodo = p.id_periodo
        WHERE e.id_cedula = :ci";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':ci', $ci, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    // Función para obtener el nombre del mes en español
    function getMesEnEspanol($mes) {
        $meses = array(
            1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        );
        return $meses[(int)$mes];
    }

    // Formatear el periodo académico en español
    $fecha_inicio = new DateTime($result['fecha_inicio']);
    $fecha_fin = new DateTime($result['fecha_fin']);
    $mes_inicio = getMesEnEspanol($fecha_inicio->format('n')); // 'n' da el mes sin ceros a la izquierda
    $mes_fin = getMesEnEspanol($fecha_fin->format('n'));
    $periodo_academico = $mes_inicio . " " . $fecha_inicio->format('Y') . " - " . $mes_fin . " " . $fecha_fin->format('Y');
    
    // Obtener el nombre de la fotografía desde la base de datos
    $fotografia = $result['fotografia'];

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

$stmt = null;
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
                    <img src="<?php echo $fotoPath; ?>" alt="Fotografía del Estudiante" style="width: 150px; height: 180px;">
                <?php else: ?>
                    <p>No se encontró la fotografía del estudiante.</p>
                <?php endif; ?>
            </div>

            <!-- Información del estudiante -->
            <div style="flex: 1;">
                <p>Nombre: <?php echo htmlspecialchars($result['nombre_estudiante']); ?></p>
                <p>Cédula: <?php echo htmlspecialchars($result['id_cedula']); ?></p>
                <p>Rol: Estudiante</p> <!-- Rol siempre será "Estudiante" -->
                <p>Celular: <?php echo htmlspecialchars($result['celular']); ?></p>
                <p>Correo: <?php echo htmlspecialchars($result['correo_institucional']); ?></p>
                <p>Modalidad: <?php echo htmlspecialchars($result['modalidad']); ?></p>
                <p>Facultad: <?php echo htmlspecialchars($result['nombre_facultad']); ?></p>
                <p>Carrera: <?php echo htmlspecialchars($result['nombre_carrera']); ?></p>
                <p>Ciclo: <?php echo htmlspecialchars($result['nombre_ciclo']); ?></p>
                <p>Periodo Académico: <?php echo htmlspecialchars($periodo_academico); ?></p>
            </div>
        </div>
    </div>
</body>
</html>
