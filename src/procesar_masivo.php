<?php
require 'vendor/autoload.php'; // Incluir PhpOffice autoload

use PhpOffice\PhpSpreadsheet\IOFactory;
session_start(); // Iniciar sesión

// Incluir la configuración de la base de datos
include 'config/database.php';

try {
    // La conexión ya está establecida en database.php
    // Configuración de PDO para que lance excepciones en caso de error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
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
            
                // Verificar si la cédula tiene 10 dígitos, si no, agregar el 0 al inicio
                if (strlen($id_cedula) < 10) {
                    $id_cedula = '0' . $id_cedula;
                }
            
                $primer_apellido = $sheet->getCell('AR' . $row->getRowIndex())->getValue();
                $segundo_apellido = $sheet->getCell('AS' . $row->getRowIndex())->getValue();
                $nombres = $sheet->getCell('AT' . $row->getRowIndex())->getValue();
                $carrera = $sheet->getCell('H' . $row->getRowIndex())->getValue();
                $ciclo = $sheet->getCell('BI' . $row->getRowIndex())->getValue();
            
                $nombre_estudiante = trim($primer_apellido) . ' ' . trim($segundo_apellido) . ' ' . trim($nombres);
            
                // Verificar si el estudiante está registrado en la tabla de estudiantes
                $stmt = $conn->prepare('SELECT id_cedula FROM estudiante WHERE id_cedula = :id_cedula');
                $stmt->bindValue(':id_cedula', $id_cedula, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
                $id_carrera = null;
                $id_ciclo = null;
            
                if ($result) {
                    // Verificar si el estudiante está registrado en la tabla de matrícula
                    $stmt = $conn->prepare('SELECT id_matricula FROM matricula WHERE id_cedula = :id_cedula');
                    $stmt->bindValue(':id_cedula', $id_cedula, PDO::PARAM_STR);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
                    if ($result) {
                        // El estudiante ya está registrado, continuar
                        continue;
                    } else {
                        // Obtener ID de la carrera
                        $stmt = $conn->prepare('SELECT id_carrera FROM carrera WHERE nombre_carrera = :carrera');
                        $stmt->bindValue(':carrera', $carrera, PDO::PARAM_STR);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        $id_carrera = $result['id_carrera'] ?? null;
            
                        // Obtener ID del ciclo
                        $stmt = $conn->prepare('SELECT id_ciclo FROM ciclo WHERE nombre_ciclo = :ciclo');
                        $stmt->bindValue(':ciclo', $ciclo, PDO::PARAM_STR);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        $id_ciclo = $result['id_ciclo'] ?? null;
            
                        // Insertar en la tabla de matrícula si los valores son válidos
                        if ($id_carrera && $id_ciclo) {
                            $stmt = $conn->prepare('INSERT INTO matricula (id_cedula, id_carrera, id_ciclo, id_periodo) VALUES (:id_cedula, :id_carrera, :id_ciclo, 1)');
                            $stmt->bindValue(':id_cedula', $id_cedula, PDO::PARAM_STR);
                            $stmt->bindValue(':id_carrera', $id_carrera, PDO::PARAM_INT);
                            $stmt->bindValue(':id_ciclo', $id_ciclo, PDO::PARAM_INT);
                            $stmt->execute();
            
                            $new_records_inserted = true;
                        } else {
                            echo "Error: Carrera o ciclo no encontrados para cédula " . $id_cedula;
                        }
                    }
                } else {
                    // Insertar en la tabla de estudiantes
                    $stmt = $conn->prepare('INSERT INTO estudiante (nombre_estudiante, id_cedula, fotografia, correo_institucional, celular) VALUES (:nombre_estudiante, :id_cedula, NULL, NULL, NULL)');
                    $stmt->bindValue(':nombre_estudiante', $nombre_estudiante, PDO::PARAM_STR);
                    $stmt->bindValue(':id_cedula', $id_cedula, PDO::PARAM_STR);
                    $stmt->execute();
            
                    // Obtener ID de la carrera
                    $stmt = $conn->prepare('SELECT id_carrera FROM carrera WHERE nombre_carrera = :carrera');
                    $stmt->bindValue(':carrera', $carrera, PDO::PARAM_STR);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $id_carrera = $result['id_carrera'] ?? null;
            
                    // Obtener ID del ciclo
                    $stmt = $conn->prepare('SELECT id_ciclo FROM ciclo WHERE nombre_ciclo = :ciclo');
                    $stmt->bindValue(':ciclo', $ciclo, PDO::PARAM_STR);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $id_ciclo = $result['id_ciclo'] ?? null;
            
                    if ($id_carrera && $id_ciclo) {
                        // Insertar en la tabla de matrícula
                        $stmt = $conn->prepare('INSERT INTO matricula (id_cedula, id_carrera, id_ciclo, id_periodo) VALUES (:id_cedula, :id_carrera, :id_ciclo, 1)');
                        $stmt->bindValue(':id_cedula', $id_cedula, PDO::PARAM_STR);
                        $stmt->bindValue(':id_carrera', $id_carrera, PDO::PARAM_INT);
                        $stmt->bindValue(':id_ciclo', $id_ciclo, PDO::PARAM_INT);
                        $stmt->execute();
            
                        $new_records_inserted = true;
                    } else {
                        echo "Error: Carrera o ciclo no encontrados para cédula " . $id_cedula;
                    }
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
                    header('Location: formulario-masivo.php');
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

// Cerrar la conexión
$conn = null;
?>

