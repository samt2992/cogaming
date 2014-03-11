<?php

$pageTitle='Activate your Account!';
include('inc/header.inc.html');
#####################################################
########### Not included til email works ############
#####################################################
	/*$x = $y = FALSE;
	// Grab the values sent in link and check them(email and code)

	if(isset($_GET['x']) && preg_match('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $_GET['x']))
	{
		$x= $_GET['x'];
	}
	if(isset($_GET['y']) && (strlen($_GET['y'])==32))
	{
		$y=$_GET['y'];
	}

	if($x && $y) // If values are given and correct, update users active row to NULL
	{*/
		require_once('../mysqli_connect.php');

		/*$q="UPDATE users SET active=NULL WHERE (email='" . mysql_real_escape_string($dbc, $x) .
			"' AND active='" . 	mysql_real_escape_string($dbc, $y) . "') LIMIT 1";
		$r = mysqli_query($dbc, $q);

		if(mysqli_affected_rows($dbc) == 1)
		{*/
			echo "<div class='container'>
					<div class='alert alert-success'>
						<strong>Winner!</strong>
						You have activated your account! You may now log in.
					</div></div>";
			########	Give them a login, sending to game selection ################
			if(isset($_POST['submitted']))
			{
				$errors=array();

				if(empty($_POST['username']))
				{
					$errors[]='You forgot to input your username!';
				}else{
					$un = $_POST['username'];
				}
				if(empty($_POST['pass']))
				{
					$errors[]='You didnt insert your password...';
				}else{
					$p=$_POST['pass'];
				}

				if(empty($errors))
				{
					$q= "SELECT userID, username FROM users WHERE(username = '$un' AND pass=sha1('p'))";
					$r=mysqli_query($dbc, $q);

					if(mysqli_num_rows($r)==1)
					{
						$_SESSION=mysqli_fetch_array($r, MYSQLI_ASSOC);

						mysqli_free_result($r);
						mysqli_close($dbc);
						$url = BASE_URL . 'users/discover.php';
						header('Location: $url');
						exit();

					}else{
						echo "<div class='container'>
								<div class='alert alert-warning'>
									<strong>Sorry</strong>
									We couldnt find a match with what you gave us</div></div>";
					}

				}else{
					echo "<div class='container'>
							<div class='alert alert-danger'>
								<strong>Error</strong><br/>";
					foreach($errors as $msg)
					{
						echo "- $msg <br/>";
					}
					echo "</div></div>";
				}
			}

			echo "<div class='container'>
					<div class='col-md-6 col-md-offset-3'>
						<form role='login' action='activate.php' method='post'>
							<div class='form-group'>
								<label for='form'>Login Here</label>
								<input class='form-control' name='username' type='text' placeholder='Username'>
							</div>
							<div class='form-group'>
								<input class='form-control' type='password' name='pass' placeholder='Password'>
							</div>
							<button type='submit' name='submit' class='btn btn-primary'>Login</button>
							<input type='hidden' name='submitted' value='TRUE'>
						</form>
					</div>
				</div>";



#####################################################
########### Not included til email works ############
#####################################################
		/*}else{
			echo "<div class='container'>
					<div class='alert alert-danger'>
						<strong>Uh Oh.</strong>
						There was an issue activating the account. Check the link, or contact us.
					</div></div>";
		}
		mysqli_close();


	}else{
		echo "<div class='container'>
				<div class='alert alert-warning'>
					<strong>Huh!!?</strong>
					Who are you> why are you here!?!
				</div></div>";
	}*/
?>


