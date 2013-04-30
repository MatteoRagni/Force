<? 
	session_start();
	
	include_once('globals.php');
	
	$redirect = "https://".$_SERVER['HTTP_HOST']."/index.php";
	if (!isset($_SESSION['username'])) { header("Location:$redirect"); }
	if (!isset($_SESSION['password'])) { header("Location:$redirect"); }
	if (!isset($_SESSION['home'])) { header("Location:$redirect"); }
	
	// Apre la risorsa SSH
	$connection_handler = ssh2_connect($world['sshserver'],$world['sshport']);
	if(!$connection_handler) { 
		die("[delete.php] Connection Failed: ". $world['sshserver']." at ".$world['sshport']); 
	}
		
	// Esegue la autenticazione in SSH con password plain
	$connection = ssh2_auth_password($connection_handler,$_SESSION['username'],$_SESSION['password']);
	if (!$connection) { 
		die("[delete.php] AuthPass Connection Failed!");
	}
	
	$sftp = ssh2_sftp($connection_handler);
	
	// Elimino il file, usando le risorse di connessione 
	$file = $_SESSION['home'].'/'.$_GET['file'];
	if (!ssh2_sftp_unlink($sftp,$file)) {
		die("[delete.php] Cannot delete file: ".$_GET['file']);
	}
		$redirect = "https://".$_SERVER['HTTP_HOST']."/jsubmit.php#ReloadExisting";
	header('Location:'.$redirect);
?>