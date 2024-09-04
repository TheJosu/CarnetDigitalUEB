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
document.querySelector('.nav-menu a').click();
