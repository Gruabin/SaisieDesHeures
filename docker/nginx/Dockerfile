FROM node AS symfony_assets_builder

WORKDIR  /var/www/html

RUN mkdir public

COPY app/package.json ./

RUN npm install

COPY app/assets/ assets/
COPY app/webpack.config.js ./
COPY app/tailwind.config.js ./

RUN npm run build

RUN cd public/build/ && ls -l

FROM nginx

# Configurez le répertoire de travail
WORKDIR /var/www/html

# Supprimez la configuration par défaut de Nginx
RUN rm /etc/nginx/conf.d/default.conf

# Copiez la configuration de Nginx
COPY --link ./docker/nginx/nginx.conf /etc/nginx/conf.d

# Installez App
COPY --link ./app/public /var/www/html/public

COPY --from=symfony_assets_builder /var/www/html/public/build /var/www/html/public/build

RUN ls -l /var/www/html/public/build

EXPOSE 80

# Démarrez Nginx lors du démarrage du conteneur
CMD ["nginx", "-g", "daemon off;"]
