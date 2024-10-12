<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];

    // Incluir el archivo de configuración
    include 'config/database.php';

    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Conexión fallida: " . $e->getMessage());
    }

    if ($tipo === 'facultad') {
        $nombre_facultad = $_POST['nombre_facultad'];
        $sql = "INSERT INTO facultad (nombre_facultad) VALUES (:nombre_facultad)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nombre_facultad', $nombre_facultad, PDO::PARAM_STR);
    } elseif ($tipo === 'carrera') {
        $nombre_carrera = $_POST['nombre_carrera'];
        $id_facultad = $_POST['id_facultad'];
        $modalidad = $_POST['modalidad'];
        $sql = "INSERT INTO carrera (nombre_carrera, id_facultad, modalidad) VALUES (:nombre_carrera, :id_facultad, :modalidad)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nombre_carrera', $nombre_carrera, PDO::PARAM_STR);
        $stmt->bindValue(':id_facultad', $id_facultad, PDO::PARAM_INT);
        $stmt->bindValue(':modalidad', $modalidad, PDO::PARAM_STR);
    } elseif ($tipo === 'ciclo') {
        $nombre_ciclo = $_POST['nombre_ciclo'];
        $id_carrera = $_POST['id_carrera'];
        $sql = "INSERT INTO ciclo (nombre_ciclo, id_carrera) VALUES (:nombre_ciclo, :id_carrera)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nombre_ciclo', $nombre_ciclo, PDO::PARAM_STR);
        $stmt->bindValue(':id_carrera', $id_carrera, PDO::PARAM_INT);
    } elseif ($tipo === 'periodo') {
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];

        $fecha_inicio = DateTime::createFromFormat('Y-m-d', $fecha_inicio);
        $fecha_fin = DateTime::createFromFormat('Y-m-d', $fecha_fin);

        if ($fecha_inicio && $fecha_fin) {
            $fecha_inicio_formato = $fecha_inicio->format('Y-m-d');
            $fecha_fin_formato = $fecha_fin->format('Y-m-d');

            $sql = "INSERT INTO periodo (fecha_inicio, fecha_fin) VALUES (:fecha_inicio, :fecha_fin)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':fecha_inicio', $fecha_inicio_formato, PDO::PARAM_STR);
            $stmt->bindValue(':fecha_fin', $fecha_fin_formato, PDO::PARAM_STR);
        } else {
            echo "Formato de fecha incorrecto.";
            exit();
        }
    } elseif ($tipo === 'estudiante') {
        $id_cedula = $_POST['id_cedula'];
        $nombre_estudiante = $_POST['nombre_estudiante'];
        $celular = $_POST['celular'];
        $correo_institucional = $_POST['correo_institucional'];

       // Procesamiento de la fotografía
        $fotografia = $_FILES['fotografia']['name'];
        $fotoPath = '';

        if ($fotografia) {
            $fotografia = basename($fotografia);  // Asegura que solo tenga el nombre base.
            
            // Usar el número de cédula como el nombre del archivo
            $extension = pathinfo($fotografia, PATHINFO_EXTENSION);
            $nombreArchivo = $_POST['id_cedula'] . '.' . $extension;
            
            $target_dir = "uploads/";  // Define la carpeta donde se guardará el archivo.
            $target_file = $target_dir . $nombreArchivo;  // Construye la ruta final del archivo.

            // Mover el archivo subido desde su ubicación temporal a la carpeta final.
            if (move_uploaded_file($_FILES['fotografia']['tmp_name'], $target_file)) {
                $fotoPath = $nombreArchivo;  // Guarda el nombre del archivo en $fotoPath.
            } else {
                echo "Error al mover el archivo.";  // Mensaje de error en caso de fallo.
                exit();  // Detiene la ejecución si ocurre un error.
            }
        }

        

        $sql = "INSERT INTO estudiante (id_cedula, nombre_estudiante, celular, correo_institucional, fotografia) 
        VALUES (:id_cedula, :nombre_estudiante, :celular, :correo_institucional, :fotografia)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_cedula', $id_cedula, PDO::PARAM_INT);
        $stmt->bindValue(':nombre_estudiante', $nombre_estudiante, PDO::PARAM_STR);
        $stmt->bindValue(':celular', $celular, PDO::PARAM_STR);
        $stmt->bindValue(':correo_institucional', $correo_institucional, PDO::PARAM_STR);
        $stmt->bindValue(':fotografia', $fotoPath, PDO::PARAM_STR);
    } elseif ($tipo === 'matricula') {
        $id_periodo = $_POST['id_periodo'];
        $id_carrera = $_POST['id_carrera'];
        $id_ciclo = $_POST['id_ciclo'];
        $id_cedula = $_POST['id_cedula'];

        $sql = "INSERT INTO matricula (id_periodo, id_carrera, id_ciclo, id_cedula) VALUES (:id_periodo, :id_carrera, :id_ciclo, :id_cedula)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_periodo', $id_periodo, PDO::PARAM_INT);
        $stmt->bindValue(':id_carrera', $id_carrera, PDO::PARAM_INT);
        $stmt->bindValue(':id_ciclo', $id_ciclo, PDO::PARAM_INT);
        $stmt->bindValue(':id_cedula', $id_cedula, PDO::PARAM_INT);
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        header("Location: principal.php");
        exit();
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }

    $stmt->closeCursor();
    $conn = null;
}
?>
