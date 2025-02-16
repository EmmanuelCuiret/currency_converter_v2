# Utiliser l'image officielle PHP avec Apache
FROM php:8.1-apache

# Copier tous les fichiers du projet dans le dossier de l’hôte Apache
COPY . /var/www/html/

# Exposer le port 80 pour que Render puisse y accéder
EXPOSE 80

# Démarrer Apache en mode foreground
CMD ["apache2-foreground"]
