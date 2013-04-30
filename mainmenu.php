<!-- 
	Elementi da implementare:
		* Upload files
		* generazione dello script di 
		* inserimento di una console ssh implementata in ruby magari?
		* visualizzazione dello stato dell coda
		* visualizzazione dello stato del singolo job	
-->

<!-- HTTPS CONTROL -->
<?

	if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == ""){
		$redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		header("Location: $redirect");
	}

?>

<!-- SESSION CONTROL -->
<? 
	session_start(); 
	include_once('globals.php');
	$redirect = "https://".$_SERVER['HTTP_HOST']."/index.php";
	if (!isset($_SESSION['username'])) { header("Location:$redirect"); }
	if (!isset($_SESSION['password'])) { header("Location:$redirect"); }
	if (!isset($_SESSION['home'])) { header("Location:$redirect"); }	
	
?>

<html>
<!-- ##################################### -->
	<head>
		<title>Home | <? echo $world['ClusterName']; ?> | FORCE - Torque Web Interface</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Styles and scripts -->
	<? include('html_include/styles.php'); ?>
	
<!-- ###################################### -->    
	<body>
	
	<!-- Header and navigation bar -->
	<? $active = 'home'; include('html_include/header.php'); ?>
	<!-- Fine Header -->
	
	<!-- Body -->
	<div class="container" id="common-page">
		<div class="hero-unit">
			<table width="100%"><tr>
				<td align="left"><h1>Welcome!</h1></td>
				<td align="right"><img src=<? echo $world['ClusterLogo']; ?> width="500px"></td>
			</tr></table>
		</div>
		<div class="row">
			<div class="span4">
				<h2>Queue</h2>
				<p>Here you can query actual queue status. This is the same running "qstat" command upon a console!</p>
				<p><a class="btn btn-primary" href="queue_status.php"><i class="icon-th-list icon-white"></i> Go to Queue...</a></p>
			</div>
			<div class="span4">
				<h2>Submit</h2>
				<p>A simple interface allows you to upload binaries and create a PBS running script. It allows you to properly define resources and walltime</p>
				<p><a class="btn btn-primary" href="jsubmit.php"><i class="icon-download-alt icon-white"></i> Submit a new job...</a></p>
			</div>
			<div class="span4">
				<h2>Delete</h2>
				<p>Have you made some mistakes? You job cannot reach completion and you don'twant to wait until walltime resource will abort it? Here you can delete your job, based upon its running ID.</p>
				<p><a class="btn btn-primary" href="jqueue.php"><i class="icon-trash icon-white"></i> Delete a job...</a></p>
			</div>
		</div>
		<div class="row">
			<div class="span4">
				<h2>Results</h2>
				<p>Access to your job result's files</p>
				<p><a class="btn btn-primary" href="jsubmit.php#ReloadExisting"><i class="icon-info-sign icon-white"></i> Access result files...</a></p>
			</div>
			<div class="span4">
				<h2>SSH</h2>
				<p>Actually under development: a complete SSH web interface that permit you to login directly into the cluster</p>
				<p><a class="btn btn-primary" href="sshinterf.php"><i class="icon-tasks icon-white"></i> Access SSH interface...</a></p>
			</div>
			<div class="span4">
				<h2>Ganglia</h2>
				<p>Access the monitoring system to keep under control the whole cluster.</p>
				<p><a class="btn btn-primary" href=<?echo $world['GangliaURL'];?> ><i class="icon-eye-open icon-white"></i> Go to Ganglia...</a></p>
			</div>
		</div>
    </div> <!-- /container -->
	<!-- Fine Body -->
	
	<!-- FOOTER -->
	<? include('html_include/footer.php'); ?>
	<!-- FINE FOOTER -->
	</body>
</html>

