<?php
	session_start();
	include('inc/config.inc.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Community of Gamers</title>
		<link rel='stylesheet' href='/gaming/css/bootstrap.css'>
		<style>
			.jumbotron{
				color:rgb(255, 255, 255);
				}
			.register{
				width: 42%;
			}
			.motto{
				width:42%;
			}
			.center{
				text-align:center;
			}
		</style>
		
		
	</head>
	<body>
		<script src="http://code.jquery.com/jquery.js"></script>
		<script src="/gaming/js/bootstrap.js"></script> 
		<nav class="navbar navbar-default navbar-fixed-top" role="navbar">
			<!-- Brand and toggle are grouped -->
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span> <!--ScreenReaderOnly -->
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo BASE_URL ?>index.php">COGaming</a>
			</div>

			<!-- Collect the forms and stuff for collapsing -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			
			
				<ul class="nav navbar-nav navbar-right">
						<li><?php if((isset($_SESSION['userID'])) &&
							(!strpos($_SERVER['PHP_SELF'],'logout.php'))  )
							{
								echo "<a href='/gaming/users/logout.php'>Logout</a>";
							}else{
								echo "<a href='/gaming/users/login.php'>Log In</a>";
																			
							}?></li>
				</ul>
			</div>
		</div>
		</nav>
		<div class='container'>
			<!-- main section -->
			<div class='jumbotron'>
				<h1>Community of Gaming</h1>
				<p>The community of everything you need.</p>
			</div>

			<!-------  PHP checking the form  ------- -->
			<?php
				if(isset($_POST['submitted'])) //	Check form for submission
				{
					//	Check and validate each input and assign variable
					require_once('../mysqli_connect1.php');
					$errors = array();

					if(empty($_POST['email']))
					{
						$errors[]= 'You forgot to give us an email address';
					}elseif(preg_match('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $_POST['email']))
					{
						
						
						$e = mysql_real_escape_string(trim($_POST['email']));
					}else{
						$errors[]='That is not a valid email.';
					}

					if(empty($_POST['username']))
					{
						$errors[]= 'You forgot to think of an awesome username';

					}else{
						$un=mysql_real_escape_string(trim($_POST['username']));

					}

					if(!empty($_POST['pass1']))
					{
						if(($_POST['pass1']) != ($_POST['pass2']))
						{
							$errors[]='The passwords do not match.';
						}else{
							$p=trim($_POST['pass1']);
						}

					}else{
						$errors[]='You did not fill both the passwords';
					}

					$q="SELECT userID FROM users WHERE username='$un'";
					$r=mysqli_query($dbc, $q);


					// Insert the information to the database
					if((mysqli_num_rows($r)==0) && (empty($errors)))
					{
						$a = md5(uniqid(rand(), true));
						$q = "INSERT INTO users(username, email,active, pass) VALUES('$un', '$e', '$a',sha1('$p'))";
						$r= mysqli_query($dbc, $q);


						if(mysqli_affected_rows($dbc) == 1)//  Check username isnt taken, then send a confirm email
						{
							/*$body = "Thank you for registering! You are now part of the community of gameing.
									However, you need to actviate your account and join game communites. Click
									on this link to activate:<br/>";
							$body .= BASE_URL . "activate.php?x=" . urlencode($e) . "&y=$a";
							mail($_POST['email'], 'Account Activation', $body); */


							echo "<div class='alert alert-success'>
									<strong>Awesome!</strong>
									You have made an account, click below to activate it!
								</div>";
							echo "<div class='col-md-4 col-md-offset-4'>
									<a  href='activate.php' alt='Activate'><button class='btn btn-success' type='button'>Activate!</button></a>
								</div>";
							exit();
						}else{
							echo "<div class='alert alert-warning'>
									<strong>Sorry!</strong>
									We couldnt sign you up right now. Its not you, it's me.
								</div>";
						}
					}else{
						echo "<div class='alert alert-danger'>
								<strong>Excuse us..</strong><br/>";
						foreach($errors as $msg)
						{
							echo " - $msg <br/>";
						}
						echo "</div>";
					}

				}

			?>
			<div class='col-md-6'>
				<h3>Register</h3>

				<form action='index.php' method='POST'>
					<div class='form-group'>
						<input class='form-control' name='email' type='email' placeholder='Email' maxLength='80'>
					</div>
					<div class='form-group'>
						<input class='form-control' name='username' type='text' placeholder='Username'>
					</div>
					<div class='form-group'>
						<input class='form-control' name='pass1' type='password' placeholder='Password'>
						<input class='form-control' name='pass2' type='password' placeholder='Confirm Password'>
					</div>
					<button type='submit' name='submit' class='btn btn-primary'>Join In</button>
						<p>Already have an account? <a href='/gaming/users/login.php'>Log In</a> here.</p>
					<input type='hidden' name='submitted' value='TRUE'>
				</form>
			</div>
			<div class='col-md-6'>
				<div class='center'>
					<br/><br/><br/>
					<p>We play from two sides of the globe</p>
					<p>We play from two sides of the room</p>
					<p>We play in two sides of a war</p>
					<p>We play as gamers</p>
					<p>This is <strong>Community of Gaming</stong></p>
					<br/>
				</div>
				
			</div>



