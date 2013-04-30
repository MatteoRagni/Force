<?
	session_start();
	
	include_once('globals.php');
	
	$redirect = "https://".$_SERVER['HTTP_HOST']."/index.php";
	if (!isset($_SESSION['username'])) { header("Location:$redirect"); }
	if (!isset($_SESSION['password'])) { header("Location:$redirect"); }
	if (!isset($_SESSION['home'])) { header("Location:$redirect"); }
	
	$tmpfile = '/tmp/'.uniqid('qsub-');
	file_put_contents($tmpfile, $_POST['result_script']);
	if(!$_POST['scriptname']) { $_POST['scriptname'] = uniqid('qsub-'); }
	$file = $_SESSION['home'].'/'.$_POST['scriptname'].'.pbs';
	
	// Apre la risorsa SSH
	$connection_handler = ssh2_connect($world['sshserver'],$world['sshport']);
	if(!$connection_handler) { 
		die("[submit_run.php] Connection Failed: ". $world['sshserver']." at ".$world['sshport']); 
	}
		
	// Esegue la autenticazione in SSH con password plain
	$connection = ssh2_auth_password($connection_handler,$_SESSION['username'],$_SESSION['password']);
	if (!$connection) { 
		die("[submit_run.php] AuthPass Connection Failed!");
	}
	
	// Invia lo script al nodo master che dovrà avviarlo
	if (!ssh2_scp_send($connection_handler, $tmpfile, $file)) {
		die("[submit_run.php] SCP not worked for: $name");
	}
	// Cancella il file sul sistema attuale
	unlink($tmpfile);
	
	if($debug) {
		$qsub_cmd = 'echo "Result of '.$file.'\n" && cat '.$file; 
	} else {
		$qsub_cmd = 'qsub '.$file;
	}
		
	$output = ssh2_exec3($connection_handler, $qsub_cmd);
?>

<head>
	<title>Submission Result</title>
	<? include_once('html_include/styles.php'); ?>
</head>
<body>
<div class="navbar navbar-fixed-top navbar-inverse" style="position: absolute;">
	<div class="navbar-inner">
		<div class="container" style="width: auto; padding: 0 20px;">
			<a class="brand" href="#">PBS Response</a>
			<ul class="nav pull-right">
				<li><a href="javascript:window.close()"><div class="badge badge-important" 
					   style="position:relative; top:-1px;">Close</div>
				</a></li>
			</ul>
		</div>
	</div>
</div>
<div class="well" style="padding: 50px;">
<?
	echo '<pre><code>' . $output . '</code></pre>';
?>
</div>
</body>

