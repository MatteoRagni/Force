<?
	$world['ClusterName']           = "X-CLUSTER";
	$world['ClusterLogo']           = "img/x-cluster-logo.png";
	$world['UniLogo']				= "img/unitn-logo.png";
	$world['GangliaURL']            = 'http://ganglia.wikimedia.org/latest/';
	$world['AdminMail']             = "nirvana1289@gmail.com";
	$world['University']            = "Università degli studi di Trento";
	$world['queue_list']			= array("batch","Another_queue1","Another_queue2");
	$world['upload_temp']			= '/var/www/uploads';
	$world['tmp']					= '/tmp';
	$debug							= true;
	
	// List server
	$world['server_list'][0] = 'localhost:22';
	$world['server_list'][1] = '192.168.1.10:22';
	
	// Bisogna ricordarsi di settare il parametro di upload_temp_dir
	
	
	
	// ----- DO NOT EDIT -------
	
	$world['ClusterLogo2'] = "<img src=\"" . $world['ClusterLogo'] . "\" width=\"150px\">"; 
	$world['UniLogo2'] = "<img src=\"" . $world['UniLogo'] . "\" width=\"150px\">"; 
	$world['GangliaURL2'] = '<a href="' . $world['GangliaURL'] . '" class="titolo_nav"><i class="icon-eye-open icon-white"></i> Ganglia</a>';
	$world['AdminMail2'] = "<a href=\"mailto:" . $world['AdminMail'] . "\">Contact Admin</a>";
	$world['AdminMail3'] = "<a href=\"mailto:" . $world['AdminMail'] . "\">here</a>";
	
	$VERSION = 'v0.7Alpha - Linux';
	if($debug) { $VERSION .= ' - DebugOn'; }
?>

<?php
	// Dichiarazione di alcune comode funzioni per il login

	
	// funzione che esegue un comando in ssh, aprendo e chiudendo la connessione
	// richiede username e password. Ritorna lo stdout del comando. Per ottenere
	// lo stderr si dovrebbe ridefinire la funzione cambiando il tipo di stream
	// Il server è quello definito nella sessione globale
	// ssh2_exec2(username, password, "stringa con il comando da eseguire")	
	function ssh2_exec2($username, $password, $command) 
	{
		global $world;
		
		// Apre la risorsa SSH
		$connection_handler = ssh2_connect($_SESSION['sshserver'],$_SESSION['sshport']);
		if(!$connection_handler) { 
			die("[globals.php] Connection Failed: ". $_SESSION['sshserver']." at ".$_SESSION['sshport']); 
		}
	
		// Esegue la autenticazione in SSH con password plain
		$connection = ssh2_auth_password($connection_handler,$username,$password);
		if (!$connection) { 
			die("[globals.php] AuthPass Connection Failed!");
		}

		// Esegue il comando e ritorna lo stream
		$ssh_stream = ssh2_exec($connection_handler, $command);
		if (!$ssh_stream) { 
			die("[globals.php: line 41] Cannot execute: $command");
		}
		else {
			// Estrai lo stream
			stream_set_blocking($ssh_stream, true);
			$output = "";
			while ($buf = fread($ssh_stream,4096*8)) { 
				$output .= $buf;
			}
			fclose($ssh_stream);
		}
		return $output;
	};
	
	function redirectssl() {
		if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == ""){
			$redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			header("Location: $redirect");
		}
	}
	
	function ssh2_exec3($connection, $command) {
		
		$ssh_stream = ssh2_exec($connection, $command);
		if (!$ssh_stream) { 
			die("[globals.php: line 41] Cannot execute: $command");
		}
		else {
			// Estrai lo stream
			stream_set_blocking($ssh_stream, true);
			$output = "";
			while ($buf = fread($ssh_stream,4096*8)) { 
				$output .= $buf;
			}
			fclose($ssh_stream);
		}
		return $output;
	
	}
	
	function ssh2_sftp_down($username, $password, $abs_path) {
	
		global $world;
		
		// Apre la risorsa SSH
		$connection_handler = ssh2_connect($_SESSION['sshserver'],$_SESSION['sshport']);
		if(!$connection_handler) { 
			die("[globals.php] Connection Failed: ". $_SESSION['sshserver']." at ".$_SESSION['sshport']); 
		}
		
		// Esegue la autenticazione in SSH con password plain
		$connection = ssh2_auth_password($connection_handler,$username,$password);
		if (!$connection) { 
			die("[globals.php] AuthPass Connection Failed!");
		}
		
		$sftp = ssh2_sftp($connection_handler);
		
		$stream = fopen("ssh2.sftp://$sftp$abs_path", 'r');
		
		return $stream;
		
	}
		
?>