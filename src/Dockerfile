# Utilisation d'une image PHP avec Apache
FROM php:8.2-apache

# Installation des extensions nécessaires
RUN apt-get update && apt-get install -y libxml2-dev \
    && docker-php-ext-install simplexml

# Copie des fichiers du projet dans le conteneur
WORKDIR /var/www/html
COPY . .

# Configuration des droits
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exposition du port
EXPOSE 80

# Démarrage d'Apache
CMD ["apache2-foreground"]