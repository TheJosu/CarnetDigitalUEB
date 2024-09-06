<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Ingreso de Datos</title>
    <!--<link rel="stylesheet" href="https://carnetdigitalueb.onrender.com/public/css/formulario2.css">-->
</head>
<body>
    <header>
        <div class="logo">
            <img src="img/logotipo-ueb2.png" alt="Logotipo UEB">
        </div>
        
        <div class="menu-toggle">
            <span class="menu-icon"></span>
        </div>
    </header>
    <nav class="nav-menu">
            <ul>
                <li><a href="#" data-form="form-facultad">Facultad</a></li>
                <li><a href="#" data-form="form-carrera">Carrera</a></li>
                <li><a href="#" data-form="form-ciclo">Ciclo</a></li>
                <li><a href="#" data-form="form-periodo">Periodo</a></li>
                <li><a href="#" data-form="form-estudiante">Estudiante</a></li>
            </ul>
        </nav>
    <main>
        <!-- Formularios -->

        <!-- Formulario para Facultad -->
        <div id="form-facultad" class="form-container">
            <h2>Agregar Facultad</h2>
            <form action="procesar.php" method="POST">
                <input type="hidden" name="tipo" value="facultad">
                <div class="form-group">
                    <label for="nombre_facultad">Nombre de la Facultad:</label>
                    <input type="text" id="nombre_facultad" name="nombre_facultad" required>
                </div>
                <button type="submit" class="submit-btn">Agregar Facultad</button>
            </form>
        </div>

        <!-- Formulario para Carrera -->
        <div id="form-carrera" class="form-container">
            <h2>Agregar Carrera</h2>
            <form action="procesar.php" method="POST">
            <input type="hidden" name="tipo" value="carrera">
                <div class="form-group">
                    <label for="nombre_carrera">Nombre de la Carrera:</label>
                    <input type="text" id="nombre_carrera" name="nombre_carrera" required>
                </div>
                <div class="form-group">
                    <label for="id_facultad">Facultad:</label>
                    <select id="id_facultad" name="id_facultad" required>
                        <option value="">Seleccione</option>
                        <?php
                        $conn = new mysqli("localhost", "root", "", "alumnosueb");
                        if ($conn->connect_error) {
                            die("Conexión fallida: " . $conn->connect_error);
                        }
                        $sql = "SELECT id_facultad, nombre_facultad FROM facultad";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value=\"" . $row['id_facultad'] . "\">" . $row['nombre_facultad'] . "</option>";
                            }
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <button type="submit">Agregar Carrera</button>
            </form>
        </div>

        <!-- Formulario para Ciclo -->
        <div id="form-ciclo" class="form-container">
            <h2>Agregar Ciclo</h2>
            <form action="procesar.php" method="POST">
                <input type="hidden" name="tipo" value="ciclo">
                <div class="form-group">
                    <label for="nombre_ciclo">Nombre del Ciclo:</label>
                    <input type="text" id="nombre_ciclo" name="nombre_ciclo" required>
                </div>
                <div class="form-group">
                    <label for="id_carrera">Carrera:</label>
                    <select id="id_carrera" name="id_carrera" required>
                        <option value="">Seleccione</option>
                        <?php
                        $conn = new mysqli("localhost", "root", "", "alumnosueb");
                        if ($conn->connect_error) {
                            die("Conexión fallida: " . $conn->connect_error);
                        }
                        $sql = "SELECT id_carrera, nombre_carrera FROM carrera";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value=\"" . $row['id_carrera'] . "\">" . $row['nombre_carrera'] . "</option>";
                            }
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <button type="submit" class="submit-btn">Agregar Ciclo</button>
            </form>
        </div>

        <!-- Formulario para Periodo -->
        <div id="form-periodo" class="form-container">
            <h2>Agregar Periodo</h2>
            <form action="procesar.php" method="POST">
                <input type="hidden" name="tipo" value="periodo">
                <div class="form-group">
                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" required>
                </div>
                <div class="form-group">
                <label for="fecha_fin">Fecha de Fin:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" required>
                </div>
                <button type="submit" class="submit-btn">Agregar Periodo</button>
            </form>
        </div>

        <!-- Formulario para Estudiante -->
        <div id="form-estudiante" class="form-container">
            <h2>Agregar Estudiante</h2>
            <form action="procesar.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="tipo" value="estudiante">
                <div class="form-group">
                    <label for="id_cedula">ID de Cédula:</label>
                    <input type="number" id="id_cedula" name="id_cedula" required>
                </div>
                <div class="form-group">
                    <label for="fotografia">Fotografía:</label>
                    <input type="file" name="fotografia" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="nombre_estudiante">Nombre:</label>
                    <input type="text" id="nombre_estudiante" name="nombre_estudiante" required>
                </div>
                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select id="rol" name="rol" required>
                        <option value="">Seleccione</option>
                        <option value="ESTUDIANTE">ESTUDIANTE</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="celular">Celular:</label>
                    <input type="text" id="celular" name="celular">
                </div>
                <div class="form-group">
                    <label for="correo_institucional">Correo Institucional:</label>
                    <input type="email" id="correo_institucional" name="correo_institucional">
                </div>
                <div class="form-group">
                    <label for="id_facultad_estudiante">Facultad:</label>
                    <select id="id_facultad_estudiante" name="id_facultad" required>
                        <option value="">Seleccione</option>
                        <?php
                        $conn = new mysqli("localhost", "root", "", "AlumnosUEB");
                        if ($conn->connect_error) {
                            die("Conexión fallida: " . $conn->connect_error);
                        }
                        $sql = "SELECT id_facultad, nombre_facultad FROM Facultad";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value=\"" . $row['id_facultad'] . "\">" . $row['nombre_facultad'] . "</option>";
                            }
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_carrera_estudiante">Carrera:</label>
                    <select id="id_carrera_estudiante" name="id_carrera" required>
                        <option value="">Seleccione</option>
                        <?php
                        $conn = new mysqli("localhost", "root", "", "AlumnosUEB");
                        if ($conn->connect_error) {
                            die("Conexión fallida: " . $conn->connect_error);
                        }
                        $sql = "SELECT id_carrera, nombre_carrera FROM Carrera";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value=\"" . $row['id_carrera'] . "\">" . $row['nombre_carrera'] . "</option>";
                            }
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_ciclo_estudiante">Ciclo:</label>
                    <select id="id_ciclo" name="id_ciclo" required>
                        <option value="">Seleccione</option>
                        <?php
                        // Obtener ciclos de la base de datos
                        $conn = new mysqli("localhost", "root", "", "AlumnosUEB");
                        if ($conn->connect_error) {
                            die("Conexión fallida: " . $conn->connect_error);
                        }
                        $sql = "SELECT id_ciclo, nombre_ciclo FROM Ciclo";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value=\"" . $row['id_ciclo'] . "\">" . $row['nombre_ciclo'] . "</option>";
                            }
                        }
                        $conn->close();
                        ?>
                    </select>

                </div>
                <div class="form-group">
                    <label for="id_periodo_estudiante">Periodo:</label>
                    <select id="id_periodo_estudiante" name="id_periodo" required>
                        <option value="">Seleccione</option>
                        <?php
                        $conn = new mysqli("localhost", "root", "", "AlumnosUEB");
                        if ($conn->connect_error) {
                            die("Conexión fallida: " . $conn->connect_error);
                        }
                        $sql = "SELECT id_periodo, fecha_inicio, fecha_fin FROM Periodo";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $inicio = new DateTime($row['fecha_inicio']);
                                $fin = new DateTime($row['fecha_fin']);
                                $periodo = $inicio->format('F Y') . " - " . $fin->format('F Y');
                                echo "<option value=\"" . $row['id_periodo'] . "\">" . $periodo . "</option>";
                            }
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="modalidad">Modalidad:</label>
                    <select id="modalidad" name="modalidad" required>
                        <option value="">Seleccione</option>
                        <option value="PRESENCIAL">PRESENCIAL</option>
                        <option value="VIRTUAL">VIRTUAL</option>
                        <option value="HIBRIDA">HIBRIDA</option>
                    </select>

                </div>
                <button type="submit" class="submit-btn">Agregar Estudiante</button>
            </form>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Apply flatpickr only to date fields in the "Agregar Periodo" form
        document.querySelectorAll('#form-periodo .date-picker').forEach(input => {
            flatpickr(input, {
                dateFormat: "d/m/Y",
                minDate: "today"
            });
        });

        // Handle navigation
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.form-container').forEach(form => form.style.display = 'none');
                document.getElementById(this.dataset.form).style.display = 'block';
            });
        });

        document.querySelector('.nav-menu a').click(); // Show the first form by default
    </script>                    
    <!--<script src="https://carnetdigitalueb.onrender.com/public/js/formulario2.js"></script>-->
</body>
</html>