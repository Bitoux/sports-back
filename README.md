# Server start :
php bin/console server:run

# Clear cache :
php bin/console cache:clear --no-warmup --env=dev

# Création de la base
php bin/console do:da:cr

# Création du schéma de données
php bin/console do:sc:cr

# Générations des fixtures
php bin/console doctrine:fixtures:load

# Deployer sur Heroku
git push heroku master

# Lancer l'app sur le wifi
php bin/console server:run 192.168.0.50:8000