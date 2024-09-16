// Handle form navigation
document.querySelectorAll('.nav-menu a').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.form-container').forEach(form => form.classList.remove('active'));
        document.getElementById(this.dataset.form).classList.add('active');
    });
});

// Handle mobile menu toggle
const menuToggle = document.querySelector('.menu-toggle');
const navMenu = document.querySelector('.nav-menu');

menuToggle.addEventListener('click', () => {
    navMenu.classList.toggle('active');
});

// Show the first form by default
document.getElementById('id_cedula').addEventListener('blur', function() {
    const idCedula = this.value;
    
    if (idCedula) {
        fetch(`consultar_estudiante.php?id_cedula=${idCedula}`)
            .then(response => response.json())
            .then(data => {
                console.log(data); // Verifica que los datos están llegando correctamente
                if (data) {
                    document.getElementById('nombre_estudiante').value = data.nombre;
                    document.getElementById('celular').value = data.celular;
                    document.getElementById('correo_institucional').value = data.correo_institucional;
                    // Completa los otros campos según sea necesario
                } else {
                    alert('No se encontró el estudiante con esta cédula.');
                }
            })
            .catch(error => console.error('Error:', error));
    }
});
