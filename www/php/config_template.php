<?php

// MAKE A COPY OF THIS FILE, FILL IT AND RENAME IT AS "config.php"
// HAZ UNA COPIA DE ESTE ARCHIVO, RELLÉNALO Y RENÓMBRALO COMO "config.php"

# MYSQL
define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'root');
define('MYSQL_PASSWORD', 'password');
define('MYSQL_DATABASE', 'database_name');

# Semilla para el hash de las contraseñas de los usuarios. Es necesario reiniciar las contraseñas para cambiar esta variable.
# Seed to generate the hash of the user's password. A password reset is necessary to change this variable.
define('USER_PASSWORD_HMAC_SEED', 'write random characters here');

# Semilla para el hash de los token.
# Seed to generate the hash of the token.
define('PASSWORD_TOKEN_IPA', 'write random characters here');

# Ruta de la web con / final, empezando desde el subdominio (de tener) y sin protocolo.
# path to the web with / in the end, starting with the subdomain (if there is) and without protocol.
define('WEB_PATH', 'www.mywebhere.com/folder/to/site/');

# Tamaño máximo para los archivos subidos para los widgets. 500Kb
define('TAM_BYTES_ARCHIVOS_MAX', '512000');

define('NICK_MAX_LENGTH', '15');
define('PASSWORD_MAX_LENGTH', '30');