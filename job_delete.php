<?
	session_start();
	
	include_once('globals.php');
	
	$redirect = "https://".$_SERVER['HTTP_HOST']."/index.php";
	if (!isset($_SESSION['username'])) { header("Location:$redirect"); }
	if (!isset($_SESSION['password'])) { header("Location:$redirect"); }
	if (!isset($_SESSION['home'])) { header("Location:$redirect"); }
	
	if($debug) {
		$qdel_cmd = 'touch '.$_GET['jid'].'.txt';
	} else {
		$qdel_cmd = 'qdel '.$_POST['relative_path'];
	}
	
	$output = ssh2_exec2($_SESSION['username'],$_SESSION['password'],$qdel_cmd);
	header('Location:queue_status.php');

?>