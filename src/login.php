<?php
// Conectar a la base de datos
include 'config/database.php';

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ci = trim($_POST['CI']);

    // Validar y escapar entrada
    $ci = htmlspecialchars($ci);

    // Consultar si la cédula existe
    $sql = "SELECT * FROM estudiante WHERE id_cedula = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ci); // Cambiar el tipo a "i" para integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Redirigir a la página de opciones
        header("Location: opciones.php?ci=" . urlencode($ci));
        exit();
    } else {
        // Redirigir con mensaje de error
        header("Location: ../INDEX.html?error=" . urlencode("Número de cédula no está registrado."));
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
