cd /home/veljko-bogdan/PhpstormProjects/symfony-obuka || exit
./bin/console cache:clear
php bin/console doctrine:fixtures:load --purge-exclusions=doctrine_migration_versions
