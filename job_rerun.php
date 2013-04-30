<?
	session_start();
	
	include_once('globals.php');
	
	$redirect = "https://".$_SERVER['HTTP_HOST']."/index.php";
	if (!isset($_SESSION['username'])) { header("Location:$redirect"); }
	if (!isset($_SESSION['password'])) { header("Location:$redirect"); }
	if (!isset($_SESSION['home'])) { header("Location:$redirect"); }
	
	if($debug) {
		$qsub_cmd = 'echo "Result of '.$_POST['relative_path'].'\n" && cat '.$_POST['relative_path']; 
	} else {
		$qsub_cmd = 'qsub '.$_POST['relative_path'];
	}
	
	$output = ssh2_exec2($_SESSION['username'],$_SESSION['password'],$qsub_cmd);

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

	<? echo '<pre><code>' . $output . '</code></pre>'; ?>

</div>
</body>