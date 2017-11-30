# Server start :
php bin/console server:run

# Clear cache :
php bin/console cache:clear --no-warmup --env=dev

# Création de la base
bin/console do:da:cr

# Création du schéma de données
bin/console do:sc:cr

# Générations des fixtures
bin/console doctrine:fixtures:load