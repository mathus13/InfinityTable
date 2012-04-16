<?php
if($_SERVER['REMOTE_ADDR'] != '192.168.1.75'){
	if(!array_key_exists('login',$_SESSION)
	||!array_key_exists('init',$_SESSION)
	||!array_key_exists('co',$_SESSION)
	||!array_key_exists('name',$_SESSION)){
		header('Location:http://'.$_SERVER['HTTP_HOST']);
	}elseif(!in_array($_SESSION['co_acct'],$allowed_accounts)){
		header("Location: /clients/".$_SESSION['co_acct']."/index.php");
	}
}
?>