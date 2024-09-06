<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];

    // Conectar a la base de datos
    include 'config/database.php';

    // Preparar la consulta y ejecutar según el tipo de formulario
    if ($tipo === 'facultad') {
        $nombre_facultad = $_POST['nombre_facultad'];
        $sql = "INSERT INTO Facultad (nombre_facultad) VALUES (:nombre_facultad)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre_facultad', $nombre_facultad, PDO::PARAM_STR);

    } elseif ($tipo === 'carrera') {
        $nombre_carrera = $_POST['nombre_carrera'];
        $id_facultad = $_POST['id_facultad'];
        $sql = "INSERT INTO Carrera (nombre_carrera, id_facultad) VALUES (:nombre_carrera, :id_facultad)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre_carrera', $nombre_carrera, PDO::PARAM_STR);
        $stmt->bindParam(':id_facultad', $id_facultad, PDO::PARAM_INT);

    } elseif ($tipo === 'ciclo') {
        $nombre_ciclo = $_POST['nombre_ciclo'];
        $id_carrera = $_POST['id_carrera'];
        $sql = "INSERT INTO Ciclo (nombre_ciclo, id_carrera) VALUES (:nombre_ciclo, :id_carrera)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre_ciclo', $nombre_ciclo, PDO::PARAM_STR);
        $stmt->bindParam(':id_carrera', $id_carrera, PDO::PARAM_INT);

    } elseif ($tipo === 'periodo') {
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];

        // Asegúrate de que las fechas se reciban en el formato adecuado
        $fecha_inicio = DateTime::createFromFormat('Y-m-d', $fecha_inicio);
        $fecha_fin = DateTime::createFromFormat('Y-m-d', $fecha_fin);

        if ($fecha_inicio && $fecha_fin) {
            $fecha_inicio_formato = $fecha_inicio->format('Y-m-d');
            $fecha_fin_formato = $fecha_fin->format('Y-m-d');

            $sql = "INSERT INTO Periodo (fecha_inicio, fecha_fin) VALUES (:fecha_inicio, :fecha_fin)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio_formato, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_fin', $fecha_fin_formato, PDO::PARAM_STR);
        } else {
            echo "Formato de fecha incorrecto.";
            exit();
        }

    } elseif ($tipo === 'estudiante') {
        $id_cedula = $_POST['id_cedula'];
        $nombre_estudiante = $_POST['nombre_estudiante'];
        $rol = $_POST['rol'];
        $celular = $_POST['celular'];
        $correo_institucional = $_POST['correo_institucional'];
        $id_facultad = $_POST['id_facultad'];
        $id_carrera = $_POST['id_carrera'];
        $id_periodo = $_POST['id_periodo'];
        $id_ciclo = $_POST['id_ciclo'];
        $modalidad = $_POST['modalidad'];

        // Procesamiento de la fotografía
        $fotografia = $_FILES['fotografia']['name'];
        $fotoPath = '';

        if ($fotografia) {
            $target_dir = "uploads/";
            // Asegúrate de generar un nombre único para evitar conflictos
            $fotografia = uniqid() . "_" . basename($fotografia);
            $target_file = $target_dir . $fotografia;
    
            // Mueve el archivo subido a la carpeta de destino
            if (move_uploaded_file($_FILES['fotografia']['tmp_name'], $target_file)) {
                $fotoPath = $fotografia; // Solo guardar el nombre del archivo en la base de datos
            } else {
                echo "Error al mover el archivo.";
                exit();
            }
        }

        $sql = "INSERT INTO Estudiante (id_cedula, nombre_estudiante, rol, celular, correo_institucional, id_facultad, id_carrera, id_periodo, id_ciclo, modalidad, fotografia) 
                VALUES (:id_cedula, :nombre_estudiante, :rol, :celular, :correo_institucional, :id_facultad, :id_carrera, :id_periodo, :id_ciclo, :modalidad, :fotografia)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_cedula', $id_cedula, PDO::PARAM_INT);
        $stmt->bindParam(':nombre_estudiante', $nombre_estudiante, PDO::PARAM_STR);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);
        $stmt->bindParam(':celular', $celular, PDO::PARAM_STR);
        $stmt->bindParam(':correo_institucional', $correo_institucional, PDO::PARAM_STR);
        $stmt->bindParam(':id_facultad', $id_facultad, PDO::PARAM_INT);
        $stmt->bindParam(':id_carrera', $id_carrera, PDO::PARAM_INT);
        $stmt->bindParam(':id_periodo', $id_periodo, PDO::PARAM_INT);
        $stmt->bindParam(':id_ciclo', $id_ciclo, PDO::PARAM_INT);
        $stmt->bindParam(':modalidad', $modalidad, PDO::PARAM_STR);
        $stmt->bindParam(':fotografia', $fotoPath, PDO::PARAM_STR);
    }

    // Ejecutar la consulta
    try {
        if ($stmt->execute()) {
            header("Location: formulario.php");
            exit();
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $stmt->closeCursor(); // No es estrictamente necesario con PDO, pero puedes usarlo para liberar recursos
}
?>
