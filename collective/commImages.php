<?php

	$pagetitle='Community Image Library';
	include('../inc/header.inc.html');

	$colID=$_GET['c'];

	require_once(MYSQL);

	$q="SELECT colID, colName FROM collective WHERE colID=$colID";
	$r=mysqli_query($dbc, $q);

	$comm=mysqli_fetch_array($r, MYSQLI_ASSOC);

	####################################
	#######	Code for Pagination  #######
	####################################
	$q="SELECT COUNT(discID) FROM discussion WHERE colID=$colID";
	$r=mysqli_query($dbc, $q);
	$pa=mysqli_fetch_array($r, MYSQLI_NUM);

	$total=$pa[0];

	if(isset($_GET['p']) && is_numeric($_GET['p']))
	{
		$page=$_GET['p'];
	}elseif($total>5){
		$page=ceil($total/5);
	}else{
		$page=1;
	}
	/*Start of the page*/
	if(isset($_GET['s']) && is_numeric($_GET['s']))
	{
		$start=$_GET['s'];

	}else{
		$start=0;
	}
	#####################################
	####### Error Checking ##############
	#####################################
	if(isset($_POST['uploaded']))
	{
		$errors=array();

		if(empty($_POST['descrip']))
		{
			$errors[]='You did not include a description';
		}else{
			$d=mysqli_real_escape_string(trim($_POST['descrip']));
		}

		if(empty($_POST['tags'])){
			$t=NULL;
		}else{
			$t=mysqli_real_escape_string(trim($_POST['tags']));

		}

		
		if(empty($_POST['colName']))
		{
			$errors[]='You did not share the image with a community or group';

		}elseif(in_array($_POST['colName'], $names))
		{
			
			$col=$_POST['colName'];
		}else{
			$errors[]='That community or group does not exist';
		}

		
	
		if(isset($_FILES['image']))
			{
				$allowed=array('image/jpeg', 'image/PNG', 'image/png', 'image/x-png', 'image/gif', 'image/jpg');
				if(in_array($_FILES['image']['type'], $allowed))
				{
					if(!is_dir('/gaming/uploads/games/' .$_POST['colName']. '/'))
					{
						mkdir('/gaming/uploads/games/' .$_POST['colName']. '/');
					}
					$imageName=uniqid(). '.' .  pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
					if(move_uploaded_file($_FILES['image']['tmp_name'], '/gaming/uploads/games/'.$_POST['colName']. '/' .$imageName))
					{
						echo "<div class='alert alert-success'>
								<p>Image has been uploaded</p>
								</div>";
					}

				}else{
					$errors[]='Sorry, wrong file type. Please use JPG, PNG or GIF';
				}
			}

		if($_FILES['image']['error'] > 0)
			{
				switch($_FILES['image']['error'])
				{
					case 1: 
						$errors[]='File exceeds Max File Size';
						break;
					case 2:
						$errors[]='File exceeds HTML Max file Size';
						break;

					case 3:
						$errors[]='File was only partially uploaded';
						break;

					case 4:
						$errors[]='No file was uploaded';
						break;

					case 6:
						$errors[]='No temp folder was available';
						break;

					case 7:
						$errors[]='unable to write to disk';
						break;

					case 8:
						$errors[]='File upload stopped';
						break;

					default:
						$errors[]='System connection was lost';
						break;
				}

			}
			if(file_exists($_FILES['image']['tmp_name']) && isfile($_FILES['upload']['tmp_name']))
			{
				unlink($_FILES['upload']['tmp_name']);
			}

		if(empty($errors))
		{
			$q="INSERT INTO images (fileURL,colID, descrip, tags)
				VALUES($_FILES[image][name], $col, '$d', '$t')";
			$r=mysqli_query($dbc, $q);

			if(mysqli_affected_rows($dbc)==1)
			{
				echo "<div class='alert alert-success'>
						<You have successfully uploaded an image!</p>
					</div>";

			}else{
				echo "<div class='alert alert-danger'>
						<p><strong>Sorry</strong> There was an error on our end...</p>
					</div>";
			}
		}else{
			echo "<div class='alert alert-danger'>
					<p><strong>Error</strong>";
			foreach($errors as $msg)
			{
				echo "- $msg  </br>";
			}

			echo "</p></div>";
		}
	}

	if(isset($_POST['liked']))
	{
		$q="UPDATE images SET rating = rating +1 WHERE imgID = $_POST[id]";
		$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));

		if(mysqli_affected_rows($dbc)==1)
		{
			echo "<div class='alert alert-success'>
					<p>Im sure you've made this eprson very happy</p>
				  </div>";
		}else{
			echo "<div class='alert alert-danger'>
					<p>Something happened...We couldn't exchange the artificial happiness with the server.</p>
				  </div>";

		}

	}
	if(isset($_POST['noped']))
	{
		$q="UPDATE images SET rating = rating -1 WHERE imgID = $_POST[id]";
		$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));
		if(mysqli_affected_rows($dbc)==1)
		{
			echo "<div class='alert alert-warning'>
					<p>This person will be mildly offended.</p>
				  </div>";
		}else{
			echo "<div class='alert alert-danger'>
					<p>Something happened...We couldn't exchange the artificial sadness with the server.</p>
				  </div>";

		}


	}
?>
<script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox();
	});
</script>
<div class='container'>
	<div class='page-header'>
		<h1>Image Library<small> The images that have been shared.</small></h1>
		<form enctype='multipart/form-data' action='userImages.php' method='POST'>
			<input type='file' name='image'>
			<div class='form-group'>
				<select class='form-control' name='colName'>
					<?php
					if(isset($_GET['c']))
					{
						echo "<option value='$colID' selected>" .$comm['colName']. "</option>";
					}
					$q="SELECT registerd.colID, collective.colName FROM registerd
						JOIN collective ON registerd.colID=collective.colID
						WHERE userID=$_SESSION[userID]";
					$r=mysqli_query($dbc, $q);
					while($names=mysqli_fetch_array($r, MYSQLI_ASSOC))
					{
						echo "<option value='" .$names['colID']. "'>" .$names['colName']. "</option>";
					}

					?>
				</select>
			</div>
			<div class='form-group'>
				<label for='descrip'>Description</label>
				<textarea name='descrip' class='form-control' rows='3'></textarea>
			</div>
			<div class='form-group'>
				<label for='tags'>Tags</label>
				<input type='text' class='form-control' name='tags'>
				<p class='help-block'>Seperate tags with commas.</p>
			</div>
			<button type='submit' class='btn btn-success' name='upload'><span class='glyphicon glyphicon-picture'></span>Upload Image</button>
			<input type='hidden' name='uploaded' value='TRUE'>


	</div>
	<?php
		
		$q="SELECT images.imgID, images.descrip, images.tags, images.fileURL, images.colID, images.rating, collective.colName FROM images
			JOIN collective ON images.colID = collective.colID 
			WHERE images.colID=$colID ORDER BY dateCre DESC LIMIT 8";
		$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));
		

		if(mysqli_num_rows($r)==0)
		{
			echo "<div class='col-md-4 col-md-offset-4'>
					<div class='alert alert-warning'>
						<p>There are no images here! Be the first and share an image.
						</p>
					</div>
				</div>";

		}else{
			echo "<div class='row'>";
			for($i=0;$i<4;$i++)
			{
				while($imgs=mysqli_fetch_Array($r, MYSQLI_ASSOC))
				{
					echo "<div class='col-md-3'>
							<div class='thumbnail'>
								<a class='fancybox' rel='group' href='/gaming/" . $imgs['fileURL'] . "'>
									<img class='img-responsive' src='/gaming/" . $imgs['fileURL'] . "' style='max-height:200'>
								</a>
								<div class='caption'>
									<p>" . $imgs['descrip'] . "</p>
									<p>" . $imgs['tags'] . "</p>
									<p>Posted to <a href='/gaming/collective/community.php?c=" . $imgs['colID'] . "'>" . $imgs['colName'] . "</a></p>
									<div style='float:right'>
										<form role='like' action='commImages.php?c=" .$colID. "&p=" .$page. "&s=" .$start. "' method='POST'>
											<div class='form-group'>
												<button name='like' class='btn btn-success' type='submit'><span class='glyphicon glyphicon-chevron-up'></span></button>
												<input type='hidden' name='liked' value='TRUE'>
												<input type='hidden' name='id' value='" .$imgs['imgID']. "'>
											</div>
										</form>
										<p>" .$imgs['rating']. "</p>
										<form role='unlike' action='commImages.php?c=" .$colID. "&p=" .$page. "&s=" .$start. "' method='POST'>
											<div class='form-group'>
												<button name='nope' class='btn btn-danger' type='submit'><span class='glyphicon glyphicon-chevron-down'></span></button>
												<input type='hidden' name='noped' value='TRUE'>
												<input type='hidden' name='id' value='" .$imgs['imgID']. "'>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>";
				}

			}
			echo "</div>
					<div class='row'>";

			for($i=0;$i<4;$i++)
			{
				while($imgs)
				{
					echo "<div class='col-md-3'>
							<div class='thumbnail'>
								<a class='fancybox' rel='group' href='/gaming/" . $imgs['fileURL'] . "'>
									<img class='img-responsive' src='/gaming/" . $imgs['fileURL'] . "' style='max-height:200'>
								</a>
								<div class='caption'>
									<p>" . $imgs['descrip'] . "</p>
									<p>" . $imgs['tags'] . "</p>
									<p>Posted to <a href='/gaming/collective/community.php?c=" . $imgs['colID'] . "'>" . $imgs['colName'] . "</a></p>
								</div>
							</div>
						</div>"; 
				}

			}
			echo "</div>";

		}
?>
			<div class='col-md-4 col-md-offset-4'>
				<!--	Pagination	-->
				<?php
					if($page>1)
					{
						echo "<ul class='pagination'>";

						$currentPage=($start/8)+1;
						if($currentPage != 1)
						{
							echo "<li><a href='commImages.php?c=$colID&p=" . $page . "&s=" . ($start-8) . "'>&laquo;</a></li>";
						}
						for($i=1;$i<=$page;$i++)
						{
							echo "<li><a href='commImages.php?c=$colID&p=" . $page . "&s=" . (8*($i-1)) . "'>" . $i . "</a></li>";
						}
						if($currentPage!=$page)
						{
							echo "<li><a href='commImages.php?c=$colID&p=" . $page . "&s=" . ($start+8) . "'>&raquo;</a></li>";

						}
						echo "</ul>";
					}
				?>
			</div>
