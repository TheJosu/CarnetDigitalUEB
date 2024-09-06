# Usar una imagen oficial de PHP con Apache
FROM php:8.1-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \  # Agregar esta línea para instalar las bibliotecas de PostgreSQL
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_pgsql  # Instalar extensiones de PostgreSQL

# Copia el contenido del proyecto al contenedor
COPY . /var/www/html/

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Dar permisos de escritura si es necesario
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80 (o el que necesites)
EXPOSE 80