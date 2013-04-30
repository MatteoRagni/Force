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
	<div class="container" id="common-page">
		<? include('html_include/qstatxml.php'); ?>
    </div> <!-- /container -->
	<!-- Fine Body -->
	
	<!-- FOOTER -->
	<? include('html_include/footer.php'); ?>
	<!-- FINE FOOTER -->
	</body>
</html>

