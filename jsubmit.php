<!-- 
	Elementi da implementare:
		* Upload files
		* generazione dello script di 
		* inserimento di una console ssh implementata in ruby magari?
		* visualizzazione dello stato dell coda
		* visualizzazione dello stato del singolo job	
		
	test di modifica 
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
		<title>Submit | <? echo $world['ClusterName']; ?> | FORCE - Torque Web Interface</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Styles and scripts -->
	<? include('html_include/styles.php'); ?>
	
<!-- ###################################### -->    
	<body>
	
	<!-- Header and navigation bar -->
	<? $active = 'submit'; include('html_include/header.php'); ?>
	<!-- Suddivisione in Tab: Selezione della tab -->
	
	<!-- Body -->
	<div class="container" id="common-page">
		<ul class="nav nav-tabs" id="myTab">
			<li class="active">
				<a href="#form_create" data-toggle="tab"><h3>Use form to create script</h3></a>
			</li>
			<li>
				<a href="#UploadFiles" data-toggle="tab"><h3>Upload script or files</h3></a>
			</li>
			<li>
				<a href="#ReloadExisting" data-toggle="tab"><h3>Run script</h3></a>
			</li>
		</ul>

		<!-- Suddivisione in Tab: Contenuto delle tab -->
		<div id="myTabContent" class="tab-content">
			<div class="tab-pane active" id="form_create">
				<? include('html_include/qsubform.php'); ?>
			</div>
			<div class="tab-pane" id="UploadFiles">
				<? include('html_include/qsubupload.php'); ?>
			</div>
			<div class="tab-pane" id="ReloadExisting">
				<!-- run script file già nella home -->
				<? include('html_include/qsub_run.php'); ?>
			</div>
		</div> <!-- div myTabContent -->
	
    </div> <!-- /container -->
	<!-- Fine Body -->
	
	<!-- FOOTER -->
	<? include('html_include/footer.php'); ?>
	<!-- FINE FOOTER -->
	</body>
</html>

