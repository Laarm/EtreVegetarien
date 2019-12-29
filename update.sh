rm -R ./*
git clone https://github.com/Laarm/EtreVegetarien.git
mv EtreVegetarien/* ./
rm -R EtreVegetarien/
chmod -R 777 ./*
chmod -R 777 ./
composer install
composer install --no-dev --optimize-autoloader
php bin/console cache:clear --env=prod --no-debug