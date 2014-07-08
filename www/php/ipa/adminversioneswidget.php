<?php

if(!isset($_POST['widgetID']) || !isset($_POST['accion']) || !isset($_POST['token'])){
	exit;
}

session_start();
if(!isset($_SESSION['usuario'])){
	exit;
}


require_once __DIR__.'/../config.php';
require_once __DIR__.'/../funciones/genericas.php';
require_once __DIR__.'/../clases/DB.php';

$db = new DB();


// Esta api debe de llamarse solo por mi, no debe de funcionar llamándose desde algo que no sea la configuración de la web.
// Para controlar que no se haga nada raro se enviará un token y se bloqueará por referer.
// EL referer debe de ser la página de la que se puede configurar el valor en cuestión (Manejar mediante un array)
// El token se genera mediante un md5 de la variable que se va a cambiar, una contraseña y una variable que parta de la id del usuario (rnd).

/*
Acciones:
1 => marcar la versión como default
2 => Hacer versión pública oculta
3 => Hacer versión pública visible
4 => Publicar versión
*/

// Por continuar. Comprobar referer y de coincidir, recoger datos, comprobar hash y de coincidir de nuevo, cambiar datos.
// hash_ipa($_SESSION['usuario']['RND'], $widgetID, PASSWORD_TOKEN_IPA);

// Comprobar referer

$posibles_referers = array(
	'widgetedit.php'
);

foreach($posibles_referers as $referer_temp){
	foreach(array('http', 'https') as $protocolo){
		if(strpos($_SERVER['HTTP_REFERER'], $protocolo.'://'.WEB_PATH.$referer_temp) === 0){
			// Referer válido
			
			// Comprobar id
			if(isset($_POST['widgetID']) && isInteger($_POST['widgetID']) && $_POST['widgetID'] >= 0){
				// Comprobar token
				if($_POST['token'] === hash_ipa($_SESSION['usuario']['RND'], $_POST['widgetID'], PASSWORD_TOKEN_IPA)){
					switch($_POST['accion']){
						case '1':
							if(isset($_POST['widgetVersion']) && isInteger($_POST['widgetVersion']) && $_POST['widgetVersion'] >= 0){
								$db->versionWidgetDefault($_POST['widgetID'], $_POST['widgetVersion']);
							}
						break;
						case '2':
							if(isset($_POST['widgetVersion']) && isInteger($_POST['widgetVersion']) && $_POST['widgetVersion'] >= 0){
								$db->versionWidgetVisibilidad($_POST['widgetID'], $_POST['widgetVersion'], false);
							}
						break;
						case '3':
							if(isset($_POST['widgetVersion']) && isInteger($_POST['widgetVersion']) && $_POST['widgetVersion'] >= 0){
								$db->versionWidgetVisibilidad($_POST['widgetID'], $_POST['widgetVersion'], true);
							}
						break;
						case '4':
							if(isset($_POST['widgetVersion']) && isInteger($_POST['widgetVersion']) && $_POST['widgetVersion'] >= 0){
								$db->publicaWidgetVersion($_POST['widgetID'], $_POST['widgetVersion'], true);
							}
						break;
					}
				}
			}
			break 2;
		}
	}
}