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
		<title><? echo $world['ClusterName']; ?> | PBSWebUI</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

    <!-- Styles and scripts -->
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="style.css" rel="stylesheet">
		
	    <script src="js/jquery.js"></script>
		<script src="js/bootstrap-transition.js"></script>
		<script src="js/bootstrap-alert.js"></script>
		<script src="js/bootstrap-modal.js"></script>
		<script src="js/bootstrap-dropdown.js"></script>
		<script src="js/bootstrap-scrollspy.js"></script>
		<script src="js/bootstrap-tab.js"></script>
		<script src="js/bootstrap-tooltip.js"></script>
		<script src="js/bootstrap-popover.js"></script>
		<script src="js/bootstrap-button.js"></script>
		<script src="js/bootstrap-collapse.js"></script>
		<script src="js/bootstrap-carousel.js"></script>
		<script src="js/bootstrap-typeahead.js"></script>
		<script src="js/bootstrap-affix.js"></script>

		<script src="js/holder/holder.js"></script>
		<script src="js/google-code-prettify/prettify.js"></script>
	
<!-- ###################################### -->    
	<body>
	
	<!-- Header and navigation bar -->
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="#"><? echo $world['ClusterName']; ?></a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li>
							<a href="mainmenu.php">
								<i class="icon-home icon-white"></i> 
								Home
							</a>
						</li>
						<li>
							<a href="queue_status.php">
								<i class="icon-th-list icon-white"></i>
								Queue
							</a>
						</li>
						<li>
							<a href="jsubmit.php">
								<i class="icon-download-alt icon-white"></i>
								Submit
							</a>
						</li>
						<li>
							<a href="jdelete.php">
								<i class="icon-trash icon-white"></i>
								Delete
							</a>
						</li>
						<li>
							<a href="jresults.php">
								<i class="icon-info-sign icon-white"></i>
								Results
							</a>
						</li>
						<li class="active">
							<a href="#">
								<i class="icon-tasks icon-white"></i>
								SSH
							</a>
						</li>
					</ul> 
				</div>
				<div class="nav-collapse pull-right">
					<ul class="nav">
						<li>
							<? echo $world['GangliaURL2']; ?>
						</li>
						<li>
							<a href="logout.php">
								<i class="icon-user icon-white"></i>
								Logout
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div> <!-- .navbar-inner -->
	</div> <!-- .navbar -->
	<!-- Fine Header -->
	
	<!-- Body -->
	<div class="container" id="common-page">
		<h1>Session under development</h1>
		<?  echo "Testing session<br />";
			echo $_SESSION['username']."<br />"; 
			
			echo $_SESSION['home']."<br />";
			echo ssh2_exec2($_SESSION['username'], $_SESSION['password'], 'echo $PWD') . "<br/>";
		?>
    </div> <!-- /container -->
	<!-- Fine Body -->
	
	<!-- FOOTER -->
	 <div id="footer">
      <div class="container">
		<table width="100%">
			<tr><td>
				<p class="muted credit"><? echo $world['University']; ?>: for support click <? echo $world['AdminMail3']; ?>.</p>
				<p class="author"><a href="mailto:nirvana1289@gmail.com">Matteo Ragni</a> - PBSWebUI 2013 - <? echo $VERSION ?></p>
			</td><td align="right">
				<? echo $world['ClusterLogo2']; ?>
			</td></tr>
		</table>
      </div>
    </div>
	<!-- FINE FOOTER -->
	</body>
</html>

