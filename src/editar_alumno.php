<?php
// Conectar a la base de datos
include 'config/database.php';

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Obtener los datos del alumno a editar
$id_estudiante = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_estudiante > 0) {
    $sql = "SELECT id_cedula, fotografia, nombre_estudiante, celular, correo_institucional 
            FROM estudiante 
            WHERE id_cedula = :id_estudiante";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_estudiante', $id_estudiante, PDO::PARAM_INT);
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

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
            SET fotografia = :fotoPath, nombre_estudiante = :nombre, celular = :telefono, correo_institucional = :correo
            WHERE id_cedula = :id_estudiante";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':fotoPath', $fotoPath, PDO::PARAM_STR);
    $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindValue(':telefono', $telefono, PDO::PARAM_STR);
    $stmt->bindValue(':correo', $correo, PDO::PARAM_STR);
    $stmt->bindValue(':id_estudiante', $id_estudiante, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: poe.php');
    } else {
        echo "Error al actualizar los datos del alumno: " . $conn->errorInfo()[2];
    }
}

$conn = null;  // Cerrar la conexión
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
