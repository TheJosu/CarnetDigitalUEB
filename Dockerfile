# Usa una imagen oficial de PHP con Apache
FROM php:8.1-apache

# Instala extensiones de PHP necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copia el contenido del proyecto al contenedor
COPY . /var/www/html

# Expone el puerto 10000 para Render
EXPOSE 10000

# Inicia Apache en el puerto 10000
CMD ["php", "-S", "0.0.0.0:10000", "-t", "/var/www/html"]
