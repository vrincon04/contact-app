# Contact App

Esta aplicación web le permite a los usuarios montar archivos de contactos en formato CSV y procesarlos con el proposito de tener un inventario unificado de todos los contactos.

Esta Web App támbien permite visualizar el listado de todos los archivos que el usuario a montado con sus respectivos estatus de procesamiento.

## Instalación de la Aplicación Usando Docker Compose

Descargar E Instalar Contact App

Lo primero es clonar el código de la aplicación contact-app.

```bash
git clone https://github.com/vrincon04/contact-app.git
cd contact-app
```

Luego de hacer eso usaremos la imagen de composer y ejecutamos el siguiente comando para asegurar de que la carpeta vendor haya sido creada.

```bash
docker run --rm -v $(pwd):/app composer install
```

y por ultimo nos adueñamos del directorio
```bash
sudo chown -R $USER:$USER ~/contact-app
```

Ya tenemos el proyecto de contact app instalado, ahora nos falta crear y configurar nuestro archivo docker-compose.ym el cual va a tener todas las instrucciones para nuestro contenedor.

```bash
nano contact-app/docker-compose.yml
```
### Creamos el Docker Compose

Aqui vamos a definir nuestros 3 principales servicios [app, servidor web, y base de datos] y copie y pegue el siguiente codigo.

```yml
services:
 
  #PHP
  app:
    build:
      context: .
      dockerfile: Dockerfile
    image: mi-contact-app
    container_name: app
    restart: unless-stopped
    tty: true
    environment:
      SERVICE_NAME: app
      SERVICE_TAGS: dev
    working_dir: /var/www
    networks:
      - app-network
 
  #Nginx
  webserver:
    image: nginx:alpine
    container_name: webserver
    restart: unless-stopped
    tty: true
    ports:
      - "80:80"
      - "443:443"
    networks:
      - app-network
 
  #MySQL Service
  db:
    image: mysql:5.7.22
    container_name: db
    restart: unless-stopped
    tty: true
    ports:
      - "3306:3306"
    environment:
      MYSQL_DATABASE: contact
      MYSQL_ROOT_PASSWORD: root
      SERVICE_TAGS: dev
      SERVICE_NAME: mysql
    networks:
      - app-network
 
#Redes
networks:
  app-network:
    driver: bridge
```

Luego de tener nuestro docker-compose vamos a configurar nuestra persistencia de datos, ya que si lo dejamos así como esta cada vez que el contenedor se reinicie (o se caiga) toda la data se perderá asi que vamos agregar un volumen al servicio db (Base de datos) de nuestro contenedor.

```yml
db:
    volumes:
      - dbdata:/var/lib/mysql
      - ./mysql/my.cnf:/etc/mysql/my.cnf
    networks:
      - app-network
```

Con esto le decimos que todo lo que esta en /var/lib/mysql será replicado en la carpeta dbdata de nuestro sistema anfitrión. ademas persistimos la configuración de la base de datos en /etc/mysql/my.cnf.

El resultado final deberá ser un docker-compose como este:

```yml
services:
 
  #PHP
  app:
    build:
      context: .
      dockerfile: Dockerfile
    image: mi-contact-app
    container_name: app
    restart: unless-stopped
    tty: true
    environment:
      SERVICE_NAME: app
      SERVICE_TAGS: dev
    working_dir: /var/www
    networks:
      - app-network
 
  #Nginx
  webserver:
    image: nginx:alpine
    container_name: webserver
    restart: unless-stopped
    tty: true
    ports:
      - "80:80"
      - "443:443"
    networks:
      - app-network
 
  #MySQL Service
  db:
    image: mysql:5.7.22
    container_name: db
    restart: unless-stopped
    tty: true
    ports:
      - "3306:3306"
    environment:
      MYSQL_DATABASE: short
      MYSQL_ROOT_PASSWORD: root
      SERVICE_TAGS: dev
      SERVICE_NAME: mysql
    volumes:
      - dbdata:/var/lib/mysql/
      - ./mysql/my.cnf:/etc/mysql/my.cnf
    networks:
      - app-network
 
#Redes
networks:
  app-network:
    driver: bridge
```
## Dockerfile

Ahora para que el docker-compose que recién creamos funcione como es debido debemos crear un Dockerfile que definirá la imagen que nombramos como mi-contact-app. y configura php ademas todos los directorios y puertos de la app.

```bash
FROM php:7.2-fpm
 
# Copiar composer.lock y composer.json
COPY composer.lock composer.json /var/www/
 
# Configura el directorio raiz
WORKDIR /var/www
 
# Instalamos dependencias
RUN apt-get update && apt-get install -y \
    build-essential \
    mysql-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl
 
# Borramos cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
 
# Instalamos extensiones
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd
 
# Instalar composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
 
# agregar usuario para la aplicación contact-app
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
 
# Copiar el directorio existente a /var/www
COPY . /var/www
 
# copiar los permisos del directorio de la aplicación
COPY --chown=www:www . /var/www
 
# cambiar el usuario actual por www
USER www
 
# exponer el puerto 9000 e iniciar php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
```

## Configurando PHP

Para esto vamos a crear un directorio que sera usado por los volúmenes que definimos en el servicio de la app, luego vamos a entrar al directorio creado el archivo local.ini.

```bash
mkdir contact-app/php
nano contact-app/php/local.ini
```
Incluimos algunas configuraciones básicas

```code
upload_max_filesize=100M
post_max_size=100M
```

## Configurando Nginx

Creamos el directorio y el archivo de configuración que establecimos en el volumen del servicio webserver

```bash
mkdir -p contact-app/nginx/conf.d
nano contact-app/nginx/conf.d/app.conf
```

Agreamos las configuraciones de lugar

```code
server {
    listen 80;
    index index.php index.html;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root /var/www/public;
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
    location / {
        try_files $uri $uri/ /index.php?$query_string;
        gzip_static on;
    }
}
```

## Configurando MySQL

Creamos el directorio y el archivo de configuración de MySQL

```bash
mkdir contact-app/mysql
nano contact-app/mysql/my.cnf
```
Agreamos las configuraciones a nuestro archivo.
```code
[mysqld]
general_log = 1
general_log_file = /var/lib/mysql/general.log
```

Ahora vamos a crear nuestras variables de entorno de nuestra aplicación contact-app en el archivo .env:

```bash
cp .env.example .env
```

Y le configuramos las variables que hacen sentido con nuestro contenedor:

```code
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=contact
DB_USERNAME=root
DB_PASSWORD=root

AWS_ACCESS_KEY_ID={AWS_KEY} // Cambiar
AWS_SECRET_ACCESS_KEY={AWS_SECRET} // Cambiar
AWS_DEFAULT_REGION=us-west-2
AWS_BUCKET={BUKET_NAME} // Cambiar
```

Luego de haber realizado todas estas configuraciones ejecutamos nuestro contenedor.

```bash
docker-compose up -d
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan config:cache
```
Los 2 ultimos comando son para agregar una llave de encripción generada para este contenedor, y cacheamos nuestra configuración.

# Ejecutando las Migraciones

Lo primero que debemos hacer es acceder a la base de datos y crear el usuario root que escribimos en nuestra configuración del archivo .env

```bash
docker-compose exec db bash
mysql -u root -p <root>
```

Una vez dendro desde la consola a nuestro motor de base de datos vamos a crear el usuario a nuestra base de datos short que fue la que nombramos en nuestro archivo docker-composer, esto lo hacemos ejecutando el siquiente Query o Consulta

```bash
mysql> GRANT ALL ON short.* TO 'root'@'%' IDENTIFIED BY 'root';
mysql> FLUSH PRIVILEGES;
mysql> EXIT;
```
Por ultimo salimos de nuestro contenedor y ejecutamos las migraciones.

```bash
docker-compose exec app php artisan migrate --seed
```

Luego de haber ejecutado cada uno de los pasos anteriores ya puedemos usar nuestra aplicación para guardar tus contactos.

## Usuario de prueba
```code
email = 'v.rincon@contact-app.local'
pass = 'password'
```

## Como usar la aplicación

Atrabes de un naveagador web entre al dominio que configuro y le va a mostar de inicio en la cual se encuentra el login para acceder al sistema coloque las credenciales de prueba mensionada mas arriba.

## License

The Laravel framework is open-sourced software licensed under the MIT license.