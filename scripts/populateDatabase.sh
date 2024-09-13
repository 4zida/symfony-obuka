cd /home/veljko-bogdan/PhpstormProjects/symfony-obuka || exit
./bin/console cache:clear
php bin/console app:generate-companies
php bin/console app:generate-users
php bin/console app:generate-phones
php bin/console app:generate-ads