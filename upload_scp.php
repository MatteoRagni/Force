<?php
	session_start();
	include_once('globals.php');
	
	//$redirect = "https://".$_SERVER['HTTP_HOST']."/index.php";
	//if (!isset($_SESSION['username'])) { header("Location:$redirect"); }
	//if (!isset($_SESSION['password'])) { header("Location:$redirect"); }
	//if (!isset($_SESSION['home'])) { header("Location:$redirect"); }
	
	$error_msg = array("OK", "exceed max_file_size - ini", "exceed max_file_size", "partial upload", "no file uploaded", "write to disk failed", "temp dir missing", "unknown extension error");
	
	// Apre la risorsa SSH
	$connection_handler = ssh2_connect($world['sshserver'],$world['sshport']);
	if(!$connection_handler) { 
		die("[upload_scp.php] Connection Failed: ". $world['sshserver']." at ".$world['sshport']); 
	}
		
	// Esegue la autenticazione in SSH con password plain
	$connection = ssh2_auth_password($connection_handler,$_SESSION['username'],$_SESSION['password']);
	if (!$connection) { 
		die("[upload_scp.php] AuthPass Connection Failed!");
	}
	
	foreach ($_FILES["filesToUpload"]["error"] as $key => $error) {
		if ($error == UPLOAD_ERR_OK) {
			$tmp_name = $_FILES["filesToUpload"]["tmp_name"][$key];
			$name = $_FILES["filesToUpload"]["name"][$key];
			
			$source = $world['upload_temp'] . '/' . $name;
			$destination = $_SESSION['home'] . '/' .$name; 
			
			// localhost
			move_uploaded_file($tmp_name, $source);
			// copy to remote host
			if (!ssh2_scp_send($connection_handler, $source, $destination)) {
				die("[upload_scp.php] SCP not worked for: $name");
			}			
			unlink($source);
		} else {
			die("[upload_scp.php] Cannot upload ".$_FILES["filesToUpload"]["name"][$key].": error: ".$error_msg[$error]);
		}
	}
	
	$redirect = "https://".$_SERVER['HTTP_HOST']."/jsubmit.php#UploadFiles";
	header("Location:$redirect");
	
	// Possibili miglioramenti: usare php_session_upload_progress per mostrare le 
	// barre di caricamento in caso di upload di grandi dimensioni
?>