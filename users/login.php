<?php
	
	$pageTitle='Login';
	include('../inc/header.inc.html');
	
// Check for form submission and check each variable
if(isset($_POST['submitted']))
{
	require_once('../../mysqli_connect.php');

	$errors=array();
	if(empty($_POST['username']))
	{
		$errors[]='You did not enter your username';
	}else{
		$un = mysqli_real_escape_string($dbc,$_POST['username']);
	}

	if(empty($_POST['password']))
	{
		$errors[]='You forgot to enter a password';
	}else{
		$p = mysqli_real_escape_string($dbc, $_POST['password']);
	}

	if(empty($errors))
	{	
############################ When Live, fix the SSL authentification and  ######################
############################ add onto query ACTIVE IS NULL to check active #####################
		 
		$q = "SELECT userID, username FROM users WHERE (username ='$un' AND pass=sha1('$p'))";
		$r = mysqli_query($dbc, $q);

		if(mysqli_num_rows($r) == 1) 
		// If a record was found, start the session, close the connection and redirect to home.
		{
			$_SESSION=mysqli_fetch_array($r, MYSQLI_ASSOC);

			mysqli_free_result($r);
			mysqli_close($dbc);
			$url = BASE_URL . 'home.php';
			header("Location: $url");
			exit();
		}else{
			echo "<div class='container'>
					<div class='alert alert-danger'>
						<strong>Hmm...</strong>
						Looks like the username and password did not match our server...
						</div></div>";

		}
	}else{
		echo "<div class='container'>
				<div class='alert alert-danger'>
					<strong>Sorry.</strong><br/>";
		foreach($errors as $msg)
		{
			echo " - $msg<br/>";
		}
	}
}
?>
<div class='container'>
	<div class='page-header'>
		<h1>Log In!<small> Put on the Nintendo Power Glove</small></h1>
	</div>
	<!-- Login Side -->
	<div class='col-md-4'> 
		<form role='login' method='POST' action='login.php'>
			<div class='form-group'>
				<div class='input-group'>
					<span class='input-group-addon'><span class='glyphicon glyphicon-user'></span></span>
					<input type='text' class='form-control' name='username' value="<?php if(isset($_POST['username']))
																							{
																								echo $_POST['username'];
																							}?>">
				</div>
			</div>
			<div class='form-group'>
				<div class='input-group'>
					<span class='input-group-addon'><span class='glyphicon glyphicon-tower'></span></span>
					<input type='password' name='password' class='form-control'>
				</div>
			</div>
			<button type='submit' name='submit' class='btn btn-primary'>Login</button>
			<input type='hidden' name='submitted' value='TRUE'>
		</form>
	</div>
	<!--  Picture Side -->
	<div class='col-md-8'>
		
		<p>There will eventually be an image here to make the page look interesting, but for now!</p>
		<p>Lorem ipsium not hd9n h8 a hidh  haid haid  haidhwid iwhej ja-[a japdj djapa ]n uhd  hduahdoh nahd
			lbabdha bdohad Lorem ipsium not hd9n h8 a hidh  haid haid  haidhwid iwhej ja-[a japdj djapa ]n uhd  hduahdoh nahd
			lbabdha bdohad Lorem ipsium not hd9n h8 a hidh  haid haid  haidhwid iwhej ja-[a japdj djapa ]n uhd  hduahdoh nahd
			lbabdha bdohad vLorem ipsium not hd9n h8 a hidh  haid haid  haidhwid iwhej ja-[a japdj djapa ]n uhd  hduahdoh nahd
			lbabdha bdohad </p>
	</div>
</div>
