cp /home/site/wwwroot/nginx.conf /etc/nginx/sites-available/default
cp /home/site/wwwroot/nginx.conf /etc/nginx/sites-enabled/default
service nginx reload

echo "🔄 Mise à jour des paquets..."
apt-get update -y

echo "➕ Ajout des dépôts requis..."
apt-get install -y software-properties-common curl

echo "🔧 Installation temporaire de PHP, Composer et npm..."
apt-get install -y php-cli unzip curl nodejs npm

# Installation manuelle de Composer (au cas où le paquet composer pose problème)
if ! command -v composer &> /dev/null
then
    echo "📥 Téléchargement de Composer..."
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
fi

echo "📂 Déplacement vers le dossier de l'application..."
cd /home/site/wwwroot || exit

echo "⚙️ Installation des dépendances PHP et JS..."
composer install --no-dev --prefer-dist
npm ci
php bin/console cache:clear --no-warmup

echo "🧹 Suppression des outils temporaires pour libérer l'espace..."
apt-get remove -y software-properties-common php-cli unzip curl nodejs npm
apt-get autoremove -y
rm -rf /var/lib/apt/lists/*

echo "✅ Déploiement terminé !"

