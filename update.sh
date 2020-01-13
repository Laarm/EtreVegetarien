rm -R ./*
git clone https://gitlab.com/Laarm/arnaudjacques-fr.git
mv arnaudjacques-fr/* ./
rm -R arnaudjacques-fr/
chmod -R 777 ./*
chmod -R 777 ./
composer install
composer install --no-dev --optimize-autoloader
php bin/console cache:clear --env=prod --no-debug

rm -R ./*
git clone https://github.com/Laarm/EtreVegetarien.git
mv EtreVegetarien/* ./
rm -R EtreVegetarien/
chmod -R 777 ./*
chmod -R 777 ./
composer install
composer install --no-dev --optimize-autoloader
php bin/console cache:clear --env=prod --no-debug