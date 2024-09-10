# Usar una imagen oficial de PHP con Apache
FROM php:8.1-apache

# Configurar el ServerName para evitar advertencias
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Instalar extensiones de PostgreSQL
RUN apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copiar el contenido del proyecto al contenedor
COPY . /var/www/html/

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Dar permisos de escritura si es necesario
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80
EXPOSE 80
