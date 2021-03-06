<?php

if(!isset($_POST['widgetID']) || !isset($_POST['action']) || !isset($_POST['token'])){
	exit;
}



/*
Actions:
1 => Upload a file
2 => Delete a file
3 => Rename a file
4 => Reupload a file
*/


// Check referer

$possibles_referrer = array(
	'widgeteditversion'
);

foreach($possibles_referrer as $referer_temp){
	foreach(array('http', 'https') as $protocol){
		if(strpos($_SERVER['HTTP_REFERER'], $protocol.'://'.WEB_PATH.$referer_temp) === 0){
			// Valid referer
			
			// check widget ID
			if(isset($_POST['widgetID']) && isInteger($_POST['widgetID']) && $_POST['widgetID'] >= 0){
				// Check token
				if($_POST['token'] === hash_ipa(G::$SESSION->get_user_random(), $_POST['widgetID'], PASSWORD_TOKEN_IPA)){
					if(isset($_POST['widgetVersion']) && isInteger($_POST['widgetVersion']) && $_POST['widgetVersion'] >= 0){
						switch($_POST['action']){
							case '1':
								if(isset($_FILES['file']) && $_FILES['file']['error'] === 0){
									file_upload_widget_version($db, $_POST['widgetID'], $_POST['widgetVersion'], $_FILES['file']);
								}
							break;
							case '2':
								if(isset($_POST['hash'])){
									$db->delete_widget_version_file($_POST['widgetID'], $_POST['widgetVersion'], $_POST['hash']);
								}
							break;
							case '3':
								if(isset($_POST['hash'])){
									if(isset($_POST['name'])){
										$name = truncate_filename($_POST['name'], FILENAME_MAX_LENGTH);
										$db->rename_widget_version_file($_POST['widgetID'], $_POST['widgetVersion'], $_POST['hash'], $name);
									}
								}
							break;
							case '4':
								if(isset($_POST['hash'])){
									if(isset($_FILES['file']) && $_FILES['file']['error'] === 0){
										$db->delete_widget_version_file($_POST['widgetID'], $_POST['widgetVersion'], $_POST['hash']);
										file_upload_widget_version($db, $_POST['widgetID'], $_POST['widgetVersion'], $_FILES['file']);
									}
								}
							break;
						}
					}
				}
			}
			break 2;
		}
	}
}
