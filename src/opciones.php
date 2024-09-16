<?php
$ci = htmlspecialchars($_GET['ci']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opciones</title>
    <link rel="stylesheet" href="../public/css/opciones.css">
</head>
<body>
    <!-- Agregar el logotipo de la UEB -->
    <div class="header-logo">
        <a href="../INDEX.html" width="50px";><img src="img/logotipo-ueb2.png" alt="Logotipo UEB"></a>
        <div class="text-container">
            
            <div class="top-text">OPCIONES PARA EL</div>
            <div class="bottom-text">CARNET DIGITAL</div>
        </div>
    </div>
    
    <div class="container">
        <h2>Opciones de Carnet Digital</h2>
        <form action="generar_carnet.php" method="get" target="_blank">
            <input type="hidden" name="ci" value="<?php echo htmlspecialchars($ci); ?>">

            <div class="button-container">
                <!-- Botón Visualizar PDF -->
                <div class="icon-text">
                    <script src="https://cdn.lordicon.com/lordicon.js"></script>
                    <lord-icon
                        src="https://cdn.lordicon.com/vfczflna.json"
                        trigger="hover"
                        colors="primary:#0b283f,secondary:#e80a0a"
                        style="width:80px;height:80px">
                    </lord-icon>
                    <button type="submit" name="action" value="view">Visualizar PDF</button>
                </div>

                <!-- Botón Descargar PDF -->
                <div class="icon-text">
                    <script src="https://cdn.lordicon.com/lordicon.js"></script>
                    <lord-icon
                        src="https://cdn.lordicon.com/wzwygmng.json"
                        trigger="hover"
                        colors="primary:#0b283f,secondary:#e80a0a"
                        style="width:80px;height:80px">
                    </lord-icon>
                    <button type="submit" name="action" value="download">Descargar PDF</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>


