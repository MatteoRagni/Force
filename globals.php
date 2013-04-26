<?
	$world['ClusterName']           = "X-CLUSTER";
	$world['ClusterLogo']           = "img/x-cluster-logo.png";
	$world['GangliaURL']            = "http://x-cluster.science.unitn.it";
	$world['AdminMail']             = "nirvana1289@gmail.com";
	$world['University']            = "Università degli studi di Trento";
	$world['sshserver']             = "localhost";
	$world['sshport']               = "22";
	$world['queue_list']			= array("batch","Another_queue1","Another_queue2");
	
	
	// ----- DO NOT EDIT -------
	
	$world['ClusterLogo2'] = "<img src=\"" . $world['ClusterLogo'] . "\" width=\"150px\">"; 
	$world['GangliaURL2'] = "<a href=\"" . $world['GangliaURL'] . "\">Ganglia</a>";
	$world['AdminMail2'] = "<a href=\"mailto:" . $world['AdminMail'] . "\">Contact Admin</a>";
	$world['AdminMail3'] = "<a href=\"mailto:" . $world['AdminMail'] . "\">here</a>";
	
	$VERSION = "v0.2 - Linux";
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
		$connection_handler = ssh2_connect($world['sshserver'],$world['sshport']);
		if(!$connection_handler) { 
			die("[globals.php] Connection Failed: ". $world['sshserver']." at ".$world['sshport']); 
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
			while ($buf = fread($ssh_stream,4096)) { 
				$output .= $buf;
			}
			fclose($ssh_stream);
		}
		return $output;
	};
	
	function xml_qstat_reader($qstat_xml) {
		
	};
	
?>