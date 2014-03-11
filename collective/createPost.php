<?php

	$pageTitle='New Post';
	include('../inc/header.inc.html');

	REQUIRE_ONCE(MYSQL);

	if(isset($_GET['c']))
	{
		$colID=$_GET['c'];
	
	$q="SELECT colName FROM collective WHERE colID=$colID";
	$r=mysqli_query($dbc, $q);
	$col=mysqli_fetch_array($r, MYSQLI_NUM);
	}
	
	if(isset($_POST['created']))
	{
		$errors=array();

		if(empty($_POST['title']))
		{
			$errors[]='You did not insert a title';
		}else{
			$sub=mysql_real_escape_string($_POST['title']);
		}

		if(empty($_POST['body']))
		{
			$errors[]='You did not give the post a body';
		}else{
			$b=mysql_real_escape_string($_POST['body']);
		}

		$c=$_POST['colid'];

		if(empty($errors))
		{

			$q="INSERT INTO discussion(colID, userID,  subj, body, dateCre)
				VALUES ($c, $_SESSION[userID], '$sub', '$b', NOW())";
			$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));

			if(mysqli_affected_rows($dbc)==1)
			{
				$q="SELECT discID FROM discussion WHERE subj='$sub'";
				$r=mysqli_query($dbc, $q);
				$id=mysqli_fetch_array($r, MYSQLI_NUM);

				$url='/gaming/collective/discussion.php?d=' .$id[0];
				header("Location: $url");
				mysqli_free_result($r);
				mysqli_close($dbc);

			}
		}
	}

?>

<div class='container'>
	<div class='page-header'>
		<h1>New Post</h1>
	</div>
	<form role='create' action='createPost.php<?php if(isset($_GET['c'])){echo '?c='.$colID;}?>' method='POST'>
		<div class='form-group'>
			<label for='colName'>Collective Posting To</label>
			<select class='form-control' name='colid'>
					<?php
					$q='SELECT colID, colName FROM collective';
					$r=mysqli_query($dbc, $q);
					if(isset($_GET['c']))
					{
						echo "<option value='$colID' selected>" .$col[0]. "</option>";
					}
					while($names=mysqli_fetch_array($r, MYSQLI_ASSOC))
					{
						echo "<option value='" .$names['colID']. "'>" .$names['colName']. "</option>";
					}

					?>
			</select>
		</div>
		<div class='form-group'>
			<label for='title'>Title</label>
			<input type='text' class='form-control' name='title' maxLength='200'>
		</div>
		<div class='form-group'>
			<label for='body'>Post Body</label>
			<textarea class='form-control' name='body' rows='5' maxLength='500'></textarea>
			<p class='help-block'>
				Please try to refrain from posting very similar content to others.<br/>
				Give context to images/links leaving the site
			</p>
		</div>
		<div class='form-group'>
			<button type='submit' class='btn btn-success' name='create'>Post Discussion</button>
			<input type='hidden' name='created' value='TRUE'>
		</div>
	</form>
</div>
<?php
mysqli_close($dbc);
include('../inc/footer.inc.html');
?>
