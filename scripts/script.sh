cd /home/veljko-bogdan/PhpstormProjects/symfony-obuka || exit
./bin/console cache:clear --env=test

php bin/console app:clean --env=test
php bin/phpunit

php bin/console app:clean --env=test --quiet