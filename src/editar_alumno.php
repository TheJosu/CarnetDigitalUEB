<?php
// Conectar a la base de datos
include 'config/database.php';

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener los datos del alumno a editar
$id_estudiante = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_estudiante > 0) {
    $sql = "SELECT id_cedula, fotografia, nombre_estudiante, celular, correo_institucional 
            FROM estudiante 
            WHERE id_cedula = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_estudiante);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        die("Alumno no encontrado.");
    }
} else {
    die("ID de estudiante inválido.");
}

// Procesar el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_estudiante = $_POST["id_estudiante"];
    $foto = $_FILES['foto']['name'];
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo"];

    // Procesamiento de la fotografía
    $fotoPath = $row['fotografia']; // Mantener la foto existente por defecto

    if ($foto) {
        $target_dir = "uploads/";
        $foto = uniqid() . "_" . basename($foto);
        $target_file = $target_dir . $foto;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            $fotoPath = $foto; // Solo guardar el nombre del archivo en la base de datos
        } else {
            echo "Error al mover el archivo.";
            exit();
        }
    }

    // Actualizar los datos del alumno
    $sql = "UPDATE estudiante 
            SET fotografia = ?, nombre_estudiante = ?, celular = ?, correo_institucional = ? 
            WHERE id_cedula = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $fotoPath, $nombre, $telefono, $correo, $id_estudiante);

    if ($stmt->execute()) {
        header('Location: poe.php');
    } else {
        echo "Error al actualizar los datos del alumno: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alumno</title>
    <link rel="stylesheet" href="../public/css/edit_estudiante.css">
</head>
<body>
    <!-- Agregar el logotipo de la UEB -->
    <div class="header-logo">
        <a href="../src/poe.php" width="50px;"><img src="img/logotipo-ueb2.png" alt="Logotipo UEB"></a>
        <div class="text-container">
            <div class="top-text">EDITAR ALUMNOS PARA EL</div>
            <div class="bottom-text">CARNET DIGITAL</div>
        </div>
    </div>
    <div class="container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id_estudiante; ?>" enctype="multipart/form-data">
            <label for="id_estudiante">ID de Estudiante:</label>
            <input type="text" id="id_estudiante" name="id_estudiante" value="<?php echo htmlspecialchars($row["id_cedula"]); ?>" readonly><br><br>
            <label for="foto">Fotografía:</label>
            <input type="file" id="foto" name="foto"><br><br>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($row["nombre_estudiante"]); ?>"><br><br>
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($row["celular"]); ?>"><br><br>
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($row["correo_institucional"]); ?>"><br><br>
            <input type="submit" value="Guardar Cambios">
        </form>
    </div>
</body>
</html>
