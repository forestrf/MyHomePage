<?php
// A file can only be downloaded if the user is using the widget or is the owner of the widget.
// If the name of the file exists for the version asked or exists for the current version the php echoes the file and sends the mimetype header of the file.


session_start();
if(!isset($_SESSION['user'])){
	exit;
}


require_once 'php/config.php';
require_once 'php/class/DB.php';



// Ask for the variables to identify the file.
// widgetID
if(!isset($_GET['widgetID']) || !isInteger($_GET['widgetID']) || $_GET['widgetID'] < 0){
	exit;
}
$widgetID = &$_GET['widgetID'];

// name
if(!isset($_GET['name']) || strlen($_GET['name']) > FILENAME_MAX_LENGTH || strlen($_GET['name']) < 1){
	exit;
}
$name = &$_GET['name'];



$db = new DB();

// If the call comes from the api the widget version comes from a query to the database.
if(isset($_GET['api'])){
	// widgetVersion
	$widgetVersion = $db->get_using_widget_version_user($widgetID);
}
else{
	// widgetVersion
	if(!isset($_GET['widgetVersion']) || !isInteger($_GET['widgetVersion']) || $_GET['widgetVersion'] < 0){
		exit;
	}
	$widgetVersion = &$_GET['widgetVersion'];
}


$file = $db->get_widget_version_file($widgetID, $widgetVersion, $name);

if($file){
	$file = &$file[0];
	// var_dump($file);
	header('Content-type: '.$file['mimetype']);
	echo $file['data'];
}