<?php
// Conectar a la base de datos
include 'config/database.php';

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ci = trim($_POST['CI']);

        // Validar y escapar entrada
        $ci = htmlspecialchars($ci);

        // Verificar si el número de cédula es la de la secretaria
        if ($ci == '1751611292') {
            header("Location: principal.php");
            exit();
        }

        // Consultar si la cédula existe
        $sql = "SELECT * FROM estudiante WHERE id_cedula = :ci";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ci', $ci, PDO::PARAM_STR);
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
    }
} catch (PDOException $e) {
    // Captura cualquier error de la base de datos y muestra el mensaje
    die("Error de conexión o consulta: " . $e->getMessage());
}

// Cerrar la conexión
$conn = null;
?>
