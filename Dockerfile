FROM php:8.2-apache

# Instala extensões necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-install pdo pdo_mysql gd

# Habilita o mod_rewrite para URLs amigáveis no Laravel
RUN a2enmod rewrite

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia os arquivos da aplicação Laravel para dentro do container
COPY . .

# Define permissões corretas para o armazenamento e cache do Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expõe a porta 80 para acesso via HTTP
EXPOSE 80

# Comando para iniciar o Apache
CMD ["apache2-foreground"]
