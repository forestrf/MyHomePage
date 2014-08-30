<?php
header('Content-Type: text/html; charset=UTF-8');

require_once 'php/functions/generic.php';
$db = open_db_session();
if(isset($_POST['submit'])){
	if($_POST['nick'] == DEFAULT_USER_NICK && !DEFAULT_USER_ACCESSIBLE){
		exit;
	}
	$valid = $db -> check_nick_password($_POST['nick'], $_POST['password']);
	
	if($valid !== false){
		$_SESSION['user'] = &$valid;
		$accessTokens = $db->getAllAccessToken();
		foreach($accessTokens as $service => $accessToken){
			$_SESSION['user'][$service] = $accessToken;
		}
	}
	else{
		echo 'Invalid login<br>';
	}
}

var_dump($_SESSION);

if(isset($_SESSION['user'])){
	exit;
}
?>

<form method="POST" action="">
	<input type="text" name="nick" placeholder="nick"><br>
	<input type="password" name="password" placeholder="password"><br>
	<input name="submit" type="submit" value="Login">
</form>
