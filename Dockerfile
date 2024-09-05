# Usa una imagen oficial de PHP con Apache
FROM php:8.1-apache

# Instala las dependencias necesarias para PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Instala las extensiones de PHP necesarias
RUN docker-php-ext-install pdo pdo_pgsql

# Copia el contenido del proyecto al contenedor
COPY . /var/www/html/

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Exponer el puerto 80 (puerto por defecto de Apache)
EXPOSE 80

# Configura Apache para escuchar en el puerto 80
CMD ["apache2-foreground"]
