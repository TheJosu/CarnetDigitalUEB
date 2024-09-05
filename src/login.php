<?php
// Conectar a la base de datos
include 'config/database.php';

// No existe "connect_error" en PDO, así que este chequeo se elimina
// Verifica si la conexión fue exitosa
if (!$conn) {
    die("Conexión fallida: No se pudo establecer la conexión a la base de datos.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ci = trim($_POST['CI']);

    // Validar y escapar entrada
    $ci = htmlspecialchars($ci);

    // Consultar si la cédula existe
    $sql = "SELECT * FROM estudiante WHERE id_cedula = :ci";
    $stmt = $conn->prepare($sql);
    // PDO usa bindParam con un formato diferente, aquí se usa ":ci" en lugar de ?
    $stmt->bindParam(':ci', $ci, PDO::PARAM_INT);  // Cambiar a entero

    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Redirigir a la página de opciones
        header("Location: opciones.php?ci=" . urlencode($ci));
        exit();
    } else {
        // Redirigir con mensaje de error
        header("Location: ../INDEX.html?error=" . urlencode("Número de cédula no está registrado."));
        exit();
    }

    $stmt = null;  // Cerrar el statement
}

$conn = null;  // Cerrar la conexión
?>
