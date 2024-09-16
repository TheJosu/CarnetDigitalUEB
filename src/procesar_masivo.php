<?php
require 'vendor/autoload.php'; // Incluir PhpOffice autoload

use PhpOffice\PhpSpreadsheet\IOFactory;
session_start(); // Iniciar sesión

// Conectar a la base de datos
include 'config/database.php';

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se ha subido un archivo
if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['archivo']['tmp_name'];
    $fileName = $_FILES['archivo']['name'];
    $fileSize = $_FILES['archivo']['size'];
    $fileType = $_FILES['archivo']['type'];

    // Mover el archivo subido a un directorio temporal
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $destPath = $uploadDir . $fileName;
    if (move_uploaded_file($fileTmpPath, $destPath)) {
        try {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($destPath);
            $sheet = $spreadsheet->getActiveSheet();

            $new_records_inserted = false;

            foreach ($sheet->getRowIterator(2) as $row) {
                $id_cedula = $sheet->getCell('AQ' . $row->getRowIndex())->getValue();
                $primer_apellido = $sheet->getCell('AR' . $row->getRowIndex())->getValue();
                $segundo_apellido = $sheet->getCell('AS' . $row->getRowIndex())->getValue();
                $nombres = $sheet->getCell('AT' . $row->getRowIndex())->getValue();
                $carrera = $sheet->getCell('H' . $row->getRowIndex())->getValue();
                $ciclo = $sheet->getCell('BI' . $row->getRowIndex())->getValue();

                $nombre_estudiante = trim($primer_apellido) . ' ' . trim($segundo_apellido) . ' ' . trim($nombres);

                // Verificar si el estudiante está registrado en la tabla de estudiantes
                $stmt = $conn->prepare('SELECT id_cedula FROM estudiante WHERE id_cedula = ?');
                $stmt->bind_param('s', $id_cedula);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Verificar si el estudiante está registrado en la tabla de matrícula
                    $stmt = $conn->prepare('SELECT id_matricula FROM matricula WHERE id_cedula = ?');
                    $stmt->bind_param('s', $id_cedula);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // El estudiante ya está registrado en ambas tablas, continuar al siguiente registro
                        continue;
                    } else {
                        // Obtener ID de la carrera
                        $stmt = $conn->prepare('SELECT id_carrera FROM carrera WHERE nombre_carrera = ?');
                        $stmt->bind_param('s', $carrera);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $id_carrera = $result->num_rows > 0 ? $result->fetch_assoc()['id_carrera'] : null;

                        // Obtener ID del ciclo
                        $stmt = $conn->prepare('SELECT id_ciclo FROM ciclo WHERE nombre_ciclo = ?');
                        $stmt->bind_param('s', $ciclo);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $id_ciclo = $result->num_rows > 0 ? $result->fetch_assoc()['id_ciclo'] : null;

                        // Insertar el nuevo registro en la tabla de matrícula
                        $stmt = $conn->prepare('INSERT INTO matricula (id_cedula, id_carrera, id_ciclo, id_periodo) VALUES (?, ?, ?, 1)');
                        $stmt->bind_param('sii', $id_cedula, $id_carrera, $id_ciclo);
                        $stmt->execute();

                        $new_records_inserted = true;
                    }
                } else {
                    // Insertar el nuevo registro en la tabla de estudiantes
                    $stmt = $conn->prepare('INSERT INTO estudiante (nombre_estudiante, id_cedula, fotografia, correo_institucional, celular) VALUES (?, ?, NULL, NULL, NULL)');
                    $stmt->bind_param('ss', $nombre_estudiante, $id_cedula);
                    $stmt->execute();

                    // Insertar el nuevo registro en la tabla de matrícula
                    $stmt = $conn->prepare('INSERT INTO matricula (id_cedula, id_carrera, id_ciclo, id_periodo) VALUES (?, ?, ?, 1)');
                    $stmt->bind_param('sii', $id_cedula, $id_carrera, $id_ciclo);
                    $stmt->execute();

                    $new_records_inserted = true;
                }
            }

            // Limpiar archivo subido
            unlink($destPath);

            // Verificar que no hay salida antes de redirigir
            if (!headers_sent()) {
                if ($new_records_inserted) {
                    header('Location: poe.php');
                    exit();
                } else {
                    header('Location: formulario-masivo.php'); // Redirigir a la página de inicio
                    exit();
                }
            } else {
                echo "Error: No se pueden enviar encabezados.";
            }
        } catch (Exception $e) {
            echo "Error al procesar el archivo: " . $e->getMessage();
        }
    } else {
        echo "No se pudo mover el archivo subido.";
    }
} else {
    echo "No se ha subido ningún archivo o ha ocurrido un error.";
}

$conn->close();
?>