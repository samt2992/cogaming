<?php
$pageTitle = 'Edit Profile';
include('../inc/header.inc.html');
?>

<?php
	require_once(MYSQL);
	$q="SELECT * FROM users WHERE userID=$_SESSION[userID]";
	$r=mysqli_query($dbc, $q);
	$user=mysqli_fetch_array($r, MYSQLI_ASSOC);

	if(isset($_POST['saved']))
	{
		$errors=array();

		if(empty($_POST['firstName']))
		{
			$fn=$user['firstName'];
		}else{
			$fn=$_POST['firstName'];
		}
		

		if(empty($_POST['lastName']))
		{
			$ln=$user['lastName'];
		}else{
			$ln=$_POST['lastName'];
		}

		
		if(empty($_POST['email']))
		{
			$e=$user['email'];

		}elseif(preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/', $_POST['email']))
		{
			$e=mysql_real_escape_string($_POST['email']);
		}else{
			$errors[]='Thats not a valid form of email';
		}

		if(empty($_POST['nPass']))
		{
			$p=sha1($user['pass']);;
		}else{

			if(sha1($_POST['cPass'])!=$user['pass'])
			{
				$errors[]='The current password does not compute';

			}elseif(($_POST['nPass'])==($_POST['conf']))
			{
				$p=$_POST['nPass'];
			}else{
				$errors[]='The new passwords dont match';
			}
		}

		if(empty($_POST['dob']))
		{
			$dob=$user['dob'];

		}else{
			$dob=$_POST['dob'];
		}

		
		
		if(!empty($errors))
		{
			echo "<div class='alert alert-danger'>
					<p><strong>Error: </strong>";
					foreach($errors as $msg)
					{
						echo "- $msg  </br>";
					}
			echo "</p></div>";
		}else{
			$q="UPDATE users
				SET firstName='$fn', lastName='$ln', dob='$dob', pass=sha1('$p'), email='$e'
				WHERE userID = $user[userID]";
			$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));

			if(mysqli_affected_rows($dbc)==1)
			{
				echo "<div class='alert alert-success'>
						<p><strong>Updated Profile</strong</p></div>";
			}else{
				echo "<div class='alert alert-danger'>
						<p>There was an error in the exchange</p></div>";
			}

		}

	}
?>
<div class='container'>
	<div class='page-header'>
		<h1>Edit Profile <small>Update your look..</small></h1>
	</div>
	<form role='Edit Profile' action='edit.php' enctype='multipart/form-data' method='POST'> 
		<div>
			<div class='col-md-4 col-md-offset-1'>

				<div>
					<h3>Personal Information</h3>
					<label for='firstName'>Names</label>
					<div class='input-group'>
						<input class='form-control' type='text' name='firstName' value="<?php echo $user['firstName'];?>" placeholder='First Name'>
						<input class='form-control' type='text' name='lastName' value="<?php echo $user['lastName'];?>" placeholder='Last Name'>
					</div>
					<label for='dob'>Date of Birth</label>
					<input class='form-control' type='date' name='dob' value="<?php echo $user['dob'];?>">
				</div>
				</br>

				<div>
					<h3>Passwords</h3>
					<label for='cPass'>Current Password</label>
					<input class='form-control' type='password' name='cPass'>
					<label for='nPass'>New Password</label>
					<div class='form-group'>
						<input class='form-control' type='password' name='nPass' placeholder='Password'>
						<input class='form-control' type='password' name='conf' placeholder='Confirm'>
					</div>
				</div>


			</div>
			<div class='col-md-6 col-md-offset-1'>
				<div>
					<h3>Email Address</h3>
					<div class='form-group'>
						<label class='control-label'>Current Email: </label>
						<p class='form-control-static'><?php echo $user['email'];?></p>
					</div>
					<div class='form group'>
						<label for='email'>New email</label>
						<input class='form-control' name='email' type='email' placeholder='Enter email'>
					</div>

				</div>
			</div>
		</div>
		<div class='col-md-4'>
			<button class='btn btn-success' type='submit' name='save' style='margin:10px'>Save</button>
		</div>
		<input type='hidden' name='saved' value='TRUE'>
	</form>
</div>
<?php

include('../inc/footer.inc.html');
?>

