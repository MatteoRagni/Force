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

	function create_server_list($server_array) {
		$output = "";
		$output = '<select name="server_select" class="span2" id="server_select">';
		
		foreach($server_array as $server) {
			
			$output .= '
				<option>'.$server.'</option>';
		}
		$output .= '</select>';
		return $output;
	}
?>
	
?>

<html>
<!-- ##################################### -->
	<head>
		<title>Login @ <? echo $world['ClusterName']; ?> | PBSWebUI</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
	<!-- Styles and scripts -->
	<? include('html_include/styles.php'); ?>
	
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
			<div class="input-prepend input-append" style="position:relative; left:-2px;">
				<button class="btn btn-primary span2" type="submit">Login</button>
				<span class="add-on">@</span>
				<? echo create_server_list($world['server_list']); ?>
			</div>
		</form>
    </div> <!-- /container -->
	<!-- Fine Body -->
	
	<!-- FOOTER -->
	 <? include('html_include/footer.php'); ?>
	<!-- FINE FOOTER -->
	</body>
</html>

