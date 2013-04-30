<!DOCTYPE html>
<?
	//header('P3P: CP="CAO PSA OUR"');
	session_start();
?>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?	
		include('html_include/styles.php'); 	
		include('globals.php');
?>
	</head>
	<body>

	<div class="navbar navbar-fixed-top navbar-inverse" style="position: absolute;">
		<div class="navbar-inner">
			<div class="container" style="width: auto; padding: 0 20px;">
				<a class="brand" href="#"><? echo $_GET['file2cat']; ?></a>
                <ul class="nav pull-right">
					<li><a href="download.php?file=<? echo $_GET['file2cat']; ?>">Download</a></li>
					<li><a href="javascript:window.close()"><div class="badge badge-important" 
						   style="position:relative; top:-1px;">Close</div></a></li>
                </ul>
            </div>
		</div>
    </div>
		
		<div class="well" style="padding: 50px;">
<?
			$text = ssh2_exec2($_SESSION['username'],$_SESSION['password'],'cat '.$_GET['file2cat']);
			echo '<pre class="prettyprint linenums"><code>' . $text . '</code></pre>';
?>
		</div>
	</body>