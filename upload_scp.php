<?php
	session_start();
	
	include_once('globals.php');
	$redirect = "https://".$_SERVER['HTTP_HOST']."/index.php";
	if (!isset($_SESSION['username'])) { header("Location:$redirect"); }
	if (!isset($_SESSION['password'])) { header("Location:$redirect"); }
	if (!isset($_SESSION['home'])) { header("Location:$redirect"); }
	
	$error_msg = array("OK", "exceed max_file_uppload", "exceed max_file_size", "partial upload", "no file uploaded", "write to disk failed", "temp dir missing", "unknown extension error");
	
	echo "<h3>Uploading files...</h3>";
	
	foreach ($_FILES["filesToUpload"]["error"] as $key => $error) {
		if ($error == UPLOAD_ERR_OK) {
			$tmp_name = $_FILES["filesToUpload"]["tmp_name"][$key];
			$name = $_FILES["filesToUpload"]["name"][$key];
			move_uploaded_file($tmp_name, $world['upload_temp'] . "/" . $name);
			$cp_string = "cp " . $world['upload_temp'] . "/" . $name . " " . $_SESSION['home'];
			ssh2_exec2($_SESSION['username'],$_SESSION['password'], $cp_string);
			unlink($world['upload_temp'] . "/" . $name);
		}
	}
	
	$redirect = "https://".$_SERVER['HTTP_HOST']."/jsubmit.php";
	header("Location:$redirect");
	
	// Possibili miglioramenti: usare php_session_upload_progress per mostrare le 
	// barre di caricamento in caso di upload di grandi dimensioni
?>