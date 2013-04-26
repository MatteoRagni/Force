<?php
	
	session_start();
	include_once("globals.php");
		
	$_SESSION['home'] = null;
	
	$_SESSION['home'] = ssh2_exec2($_POST['username'],$_POST['password'],'echo $HOME');
	
	if( isset($_SESSION['home']) ) { 
	
		// ----> I know this is not secure, but I need to perform login every time
		//       To prevent spoofing i've constrained all site on ssl. Session is
		//		 safer than cookies.
		$_SESSION['password'] = $_POST['password'];
		$_SESSION['username'] = $_POST['username'];
		
		$redirect = "https://".$_SERVER['HTTP_HOST']."/mainmenu.php";
		header("Location:$redirect");
		}
	else { 
		session_destroy();
		$redirect = "https://".$_SERVER['HTTP_HOST']."/index.php";
		header("Location:$redirect");
	}
?>
		
	
	