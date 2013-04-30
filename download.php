<? 
	session_start();
	
	include_once('globals.php');
	
	$redirect = "https://".$_SERVER['HTTP_HOST']."/index.php";
	if (!isset($_SESSION['username'])) { header("Location:$redirect"); }
	if (!isset($_SESSION['password'])) { header("Location:$redirect"); }
	if (!isset($_SESSION['home'])) { header("Location:$redirect"); }

	
	// Apre la risorsa SSH
	$connection_handler = ssh2_connect($_SESSION['sshserver'],$_SESSION['sshport']);
	if(!$connection_handler) { 
		die("[download.php] Connection Failed: ". $_SESSION['sshserver']." at ".$_SESSION['sshport']); 
	}
		
	// Esegue la autenticazione in SSH con password plain
	$connection = ssh2_auth_password($connection_handler,$_SESSION['username'],$_SESSION['password']);
	if (!$connection) { 
		die("[download.php] AuthPass Connection Failed!");
	}
	
	// Copia il file in un punto raggiungibile dall'interprete	
	// il nome viene reso irriconoscibile
	$file = $_SESSION['home'].'/'.$_GET['file'];
	$temp_file = $world['tmp'].'/'.uniqid('down-');
	ssh2_scp_recv($connection_handler,$file,$temp_file);
	
	header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length:'.filesize($temp_file));
    ob_clean();
    flush();
    readfile($temp_file);
	unlink($temp_file);

?>