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
        $sql = "INSERT INTO facultad (nombre_facultad) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nombre_facultad);
    } elseif ($tipo === 'carrera') {
        $nombre_carrera = $_POST['nombre_carrera'];
        $id_facultad = $_POST['id_facultad'];
        $modalidad = $_POST['modalidad'];
        $sql = "INSERT INTO carrera (nombre_carrera, id_facultad, modalidad) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $nombre_carrera, $id_facultad, $modalidad);
    } elseif ($tipo === 'ciclo') {
        $nombre_ciclo = $_POST['nombre_ciclo'];
        $id_carrera = $_POST['id_carrera'];
        $sql = "INSERT INTO ciclo (nombre_ciclo, id_carrera) VALUES (?, ?)";
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

            $sql = "INSERT INTO periodo (fecha_inicio, fecha_fin) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $fecha_inicio_formato, $fecha_fin_formato);
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

        $sql = "INSERT INTO estudiante (id_cedula, nombre_estudiante, celular, correo_institucional, fotografia) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $id_cedula, $nombre_estudiante, $celular, $correo_institucional, $fotoPath);
    } elseif ($tipo === 'matricula') {
        $id_periodo = $_POST['id_periodo'];
        $id_carrera = $_POST['id_carrera'];
        $id_ciclo = $_POST['id_ciclo'];
        $id_cedula = $_POST['id_cedula'];

        $sql = "INSERT INTO matricula (id_periodo, id_carrera, id_ciclo, id_cedula) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $id_periodo, $id_carrera, $id_ciclo, $id_cedula);
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        header("Location: principal.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
