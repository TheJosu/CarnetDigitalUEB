<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];

    // Conectar a la base de datos
    include 'config/database.php';
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    if ($tipo === 'facultad') {
        $nombre_facultad = $_POST['nombre_facultad'];
        $sql = "INSERT INTO Facultad (nombre_facultad) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nombre_facultad);
    } elseif ($tipo === 'carrera') {
        $nombre_carrera = $_POST['nombre_carrera'];
        $id_facultad = $_POST['id_facultad'];
        $sql = "INSERT INTO Carrera (nombre_carrera, id_facultad) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nombre_carrera, $id_facultad);
    } elseif ($tipo === 'ciclo') {
        $nombre_ciclo = $_POST['nombre_ciclo'];
        $id_carrera = $_POST['id_carrera'];
        $sql = "INSERT INTO Ciclo (nombre_ciclo, id_carrera) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nombre_ciclo, $id_carrera);
    } elseif ($tipo === 'periodo') {
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];

        // Asegúrate de que las fechas se reciban en el formato adecuado
        $fecha_inicio = DateTime::createFromFormat('Y-m-d', $fecha_inicio);
        $fecha_fin = DateTime::createFromFormat('Y-m-d', $fecha_fin);

        if ($fecha_inicio && $fecha_fin) {
            $fecha_inicio_formato = $fecha_inicio->format('Y-m-d');
            $fecha_fin_formato = $fecha_fin->format('Y-m-d');

            $sql = "INSERT INTO Periodo (fecha_inicio, fecha_fin) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $fecha_inicio_formato, $fecha_fin_formato);
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
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssiiiss", $id_cedula, $nombre_estudiante, $rol, $celular, $correo_institucional, $id_facultad, $id_carrera, $id_periodo, $id_ciclo, $modalidad, $fotoPath);
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        header("Location: formulario.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
