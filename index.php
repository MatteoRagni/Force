<!DOCTYPE html>
<!-- login.php - Matteo Ragni 2013 -->

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
	
	if (!isset($_SESSION['username'])) $_SESSION['username'] = null;
	if (!isset($_SESSION['password'])) { $_SESSION['password'] = null; }
	if (!isset($_SESSION['home'])) { $_SESSION['home'] = null; }	
	
?>

<html>
<!-- ##################################### -->
	<head>
		<title>Login @ <? echo $world['ClusterName']; ?> | PBSWebUI</title>
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

		<script src="js/application.js"></script>

	
<!-- ###################################### -->    
	<body>
	
	<!-- Header -->
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="#"><? echo $world['ClusterName']; ?></a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li class="active"><a href="#"><i class="icon-user icon-white"></i> Login</a></li>
						<li><? echo $world['GangliaURL2']; ?></li>
					</ul> 
				</div>
				<div class="nav-collapse pull-right">
					<ul class="nav">
						<li><? echo $world['AdminMail2']; ?></li>
					</ul>
				</div>
			</div>
		</div> <!-- .navbar-inner -->
	</div> <!-- .navbar -->
	<!-- Fine Header -->
	
	<!-- Body -->
	<!-- TODO Modificare gli elementi in modo tale che inviino in post i dati di login -->
	<div class="container">
		<form class="form-signin" method="POST" action="login_ssh.php">
			<h2 class="form-signin-heading">Please login</h2>
			<input type="text" class="input-block-level" name="username" placeholder="SSH username">
			<input type="password" class="input-block-level" name="password" placeholder="Password">
			<button class="btn btn-large btn-primary" type="submit">Login</button>
		</form>
    </div> <!-- /container -->
	<!-- Fine Body -->
	
	<!-- FOOTER -->
	 <div id="footer">
      <div class="container">
		<table width="100%">
			<tr><td>
				<p class="muted credit"><? echo $world['University']; ?></p>
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

