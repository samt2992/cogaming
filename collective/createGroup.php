<?php

	$pageTitle='New Post';
	include('../inc/header.inc.html');

	REQUIRE_ONCE(MYSQL);

	
	
	if(isset($_POST['created']))
	{
		$errors=array();

		if(empty($_POST['colName']))
		{
			$errors[]='You did not insert a title for your group!';
		}else{
			$name=$_POST['colName'];
		}

		if(empty($_POST['tags']))
		{
			$errors[]='You did not insert any tags';
		}else{
			$t=mysql_real_escape_string($_POST['tags']);
		}

		if(empty($_POST['description']))
		{
			$errors[]='You did not give a description.';
		}else{
			$d=mysql_real_escape_string($_POST['description']);
		}

		$m=$_SESSION['userID'];
		

			if(empty($errors))
			{

				$q="INSERT INTO collective(colName,descrip)
					VALUES ('$name','$d');";
				$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));

				$s="SELECT colID FROM collective WHERE colName='$name'";
				$rs=mysqli_query($dbc, $s) or die(mysqli_error($dbc));

				$id=mysqli_fetch_array($s, MYSQLI_NUM);

				$q="INSERT INTO groups(grpColID, userID, relGames, dateCre)
					VALUES($id[0], $m, '$t', NOW())";
				$r=mysqli_query($dbc, $q);

				if(mysqli_affected_rows($dbc)==1)
				{
					$q="SELECT colID FROM collective WHERE colName='$_POST[colName]'";
					$r=mysqli_query($dbc, $q);
					$id=mysqli_fetch_array($r, MYSQLI_NUM);

					$url='/gaming/collective/group.php?g=' .$id[0];
					header("Location: $url");
					mysqli_free_result($r);
					mysqli_close($dbc);

				}else{
					echo "<div class='alert alert-warning'>
							<p>We could not connect to the server</p>
						  </div>";
				}
			}else{
				echo "<div class='alert alert-danger'>
						<p><strong>Errors: </strong>
						";
						foreach($errors as $msg)
						{
							echo "- $msg <br/>";
						}
				echo "</p></div>";
			}

	

		
	}

?>

<div class='container'>
	<div class='page-header'>
		<h1>New Group</h1>
	</div>
	<form role='create' action='createGroup.php' method='POST'>
		<div class='form-group'>
			<label for='colName'>What shall you call yourselves?</label>
			<input type='text' name='colName' class='form-control'>
		</div>

		<div class='form-group'>
			<label for='tags'>Related Games/Terms</label>
			<input type='text' class='form-control' name='tags' maxLength='200'>
			<p class='help-block'>Seperate each tag with a comma. This section can be linked to general things like Steam or Battlfield</p>
		</div>
		<div class='form-group'>
			<label for='description'>A description for your group</label>
			<textarea class='form-control' name='description' rows='5' maxLength='500'></textarea>
			<p class='help-block'>
				Please try to refrain from using vulgar language in public text.<br/>
				You may be as much like /r/spacedicks as you want within your group.
			</p>
		</div>
		<div class='form-group'>
			<button type='submit' class='btn btn-success' name='create'>Form Group</button>
			<input type='hidden' name='created' value='TRUE'>
		</div>

	</form>
</div>

<?php
	mysqli_close($dbc);
	include('../inc/footer.inc.html');
?>
