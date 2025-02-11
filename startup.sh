cp /home/site/wwwroot/nginx.conf /etc/nginx/sites-available/default
cp /home/site/wwwroot/nginx.conf /etc/nginx/sites-enabled/default
service nginx reload

echo "🔄 Mise à jour des paquets..."
apt-get update -y

echo "🔧 Installation temporaire de Composer et npm..."
apt-get install -y curl unzip nodejs npm composer

echo "📂 Déplacement vers le dossier de l'application..."
cd site/wwwroot || exit

echo "⚙️ Installation des dépendances..."
composer install --no-dev --prefer-dist
npm ci
php bin/console cache:clear --no-warmup

echo "🧹 Suppression de Composer et npm pour libérer l'espace..."
apt-get remove -y composer nodejs npm
apt-get autoremove -y
rm -rf /var/lib/apt/lists/*

echo "✅ Déploiement terminé !"
