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
		<title>Queue | <? echo $world['ClusterName']; ?> | FORCE - Torque Web Interface</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Styles and scripts -->
	<? include('html_include/styles.php'); ?>

<!-- ###################################### -->    
	<body>
	
	<!-- Header and navigation bar -->
	<? $active = 'queue'; include('html_include/header.php'); ?>
	<!-- Fine Header -->
	
	<!-- Body -->
	<!-- Suddivisione in Tab: header delle tab -->
	<div class="container" id="common-page">
		<ul class="nav nav-tabs" id="myTab">
			<li class="active">
				<a href="#queue_state" data-toggle="tab"><h3>Status Queue</h3></a>
			</li>
		</ul>
	
	<!-- Suddivisione in Tab: Contenuto delle tab -->
		<div id="myTabContent" class="tab-content">
			<div class="tab-pane active" id="queue_state">
				<? include('html_include/qstatxml.php'); ?>
			</div> <!-- /container -->
		</div>
	</div> <!-- div myTabContent -->
	<!-- Fine Body -->
	
	<!-- FOOTER -->
	<? include('html_include/footer.php'); ?>
	<!-- FINE FOOTER -->
	</body>
</html>

