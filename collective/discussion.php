<?php
	require_once('../../mysqli_connect.php');


	$q="SELECT users.username, users.profImgID, discussion.userID, subj, body, dateCre, rating, discID FROM discussion 
		JOIN users ON discussion.userID = users.userID 
		WHERE discID = $_GET[d]";
	$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));
	$disc=mysqli_fetch_array($r, MYSQLI_ASSOC);

	$pageTitle=$disc['subj'];
	include('../inc/header.inc.html');

	if(isset($_POST['liked']))
	{
		$q="UPDATE discussion SET rating = rating +1 WHERE discID = $disc[discID]";
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
		$q="UPDATE discussion SET rating = rating -1 WHERE discID = $disc[discID]";
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

	if(isset($_POST['messaged']))
	{
		if(empty($_POST['body']))
		{
			echo "<div class='alert alert-danger'>
					<p>You didn\'t enter a message</p>
				  </div>";
		}else{
			$b=$_POST['body'];
		}

		$pid=0;

		$q="INSERT INTO messages(userID, discID, parentID, body, dateCre)
			VALUES ($disc[userID], $disc[discID], $pid, '$b', NOW())";
		$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));

		if(mysqli_affected_rows($dbc)==1)
		{
			echo "<div class='alert alert-success'>
					<p>Your message has been posted! Check below.</p>
				  </div>";
		}else{
			echo "<div class='alert alert-danger'>
					<p>We could not contact the server properly. Try again or contact us if the error persists</p>
				  </div>";
		}
	}

	if(isset($_POST['replied']))
	{
		if(empty($_POST['body']))
		{
			echo "<div class='alert alert-danger'>
					<p>You didn\'t enter a message</p>
				  </div>";
		}else{
			$b=$_POST['body'];
		}

		$pid=$_POST['pID'];

		$q="INSERT INTO messages(userID, discID, parentID, body, dateCre)
			VALUES ($disc[userid], $disc[discID], $pid, '$b', NOW()";
		$r=mysqli_query($dbc, $q);

		if(mysqli_affected_rows($dbc)==1)
		{
			echo "<div class='alert alert-success'>
					<p>Your reply has been posted! Check below.</p>
				  </div>";
		}else{
			echo "<div class='alert alert-danger'>
					<p>We could not contact the server properly. Try again or contact us if the error persists</p>
				  </div>";
		}
	}



?>
<div class='container'>
	<div class='page header'>
		<h1><?php echo $disc['subj'];?></h1>
	</div>
	<div class='col-md-10'>
		<div class='media'>
			<a class='pull-left' href='../users/user.php?u=<?php echo $disc['userID'];?>'>
				<img class='media-object img-responsive' src='/gaming<?php echo $disc['profImgID'];?>'
					 alt='Profile image' style='max-height:140px'>
			</a>
			<div class='media-body'>
				<h4 class='media-heading'>Created by <?php echo $disc['username']. ' on ' .$disc['dateCre'];?></h4>
				<p>
					<?php echo $disc['body'];?>
				</p>
							
			</div>
		</div>
	</div>
	<div class='col-md-2'>

		<form role='like' action='discussion.php?d=<?php echo $disc['discID'];?>' method='POST'>
			<div class='form-group'>
				<button name='like' class='btn btn-success' type='submit'><span class='glyphicon glyphicon-chevron-up'></span></button>
				<input type='hidden' name='liked' value='TRUE'>
			</div>
		</form>
		<p><?php echo $disc['rating'];?></p>
		<form role='unlike' action='discussion.php?d=<?php echo $disc['discID'];?>' method='POST'>
			<div class='form-group'>
				<button name='nope' class='btn btn-danger' type='submit'><span class='glyphicon glyphicon-chevron-down'></span></button>
				<input type='hidden' name='noped' value='TRUE'>
			</div>
		</form>
	</div>
	<div class='col-md-12'>
	<hr/>
	<form role='message' action='discussion.php?d=<?php echo $disc['discID'];?>' method='POST'>
		<div class='form-group'>
			<label for='body'>Leave a message</label>
			<textarea class='form-control' name='body' rows='5'></textarea>
		</div>
		<div class='form-group'>
			<button class='btn btn-primary' type='submit' name='post'><span class='glyphicon glyphicon-comment'></span> Submit</button>
			<input type='hidden' name='messaged' value='TRUE'>
		</div>
	</form>
	</div>

	<?php
		$q="SELECT messID FROM messages WHERE discID=$disc[discID]";
		$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));

		if(mysqli_num_rows($r)==0)
		{
			echo "<div class='alert alert-warning' style='clear:both'>
					<p class='text-center'><strong>There are no messages!</strong><br/>
						Why not be the first?</p>
				  </div>";
		}else{
			$q="SELECT users.username, users.profImgID, messages.userID, messID, parentID, body, dateCre
				FROM messages
				JOIN users ON messages.userID=users.userID
				WHERE discID=$disc[discID] AND parentID=0
				ORDER BY dateCre";
			$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));
			while($mess=mysqli_fetch_array($r, MYSQLI_ASSOC))
			{
				echo"<div class='media' style='clear:both'>
						<a class='pull-left' href='../users/user.php?u=" .$mess['userID']. "'>
							<img class='media-object img-responsive' src='/gaming" .$mess['profImgID']. "' style='max-height:64px'>
						</a>
						<div class='panel-group' id='accordian'>
							<div class='panel panel-default'>
								<div class='panel-body'>
									<div class='media-body'>
										<h4 class='media-heading'>Created by " .$mess['username']. " on " .$mess['dateCre']. "</h4>"; 
										$q="SELECT users.username FROM messages 
											JOIN users ON messages.userID=users.userID
											WHERE messID=$mess[parentID]";
										$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));
										while($reply=mysqli_fetch_array($r, MYSQLI_NUM))
										{
											echo "<p class='help-block'>In reply to " .$reply['username']. "</p>";
										}
										echo 
										"<p>"
											
											 .$mess['body'].
											
										"</p>
									</div>
							
								
									<a data-toggle='collapse' data-parent='#accordian' href='#collapseOne'>
										<div class='panel-heading'>
											<h5>Reply <b class='caret'></b></h5>
										</div>
									</a>
									<div id='collapseOne' class='panel-collaspe collapse'>
										<div class='panel-body'>
											<form role='reply' action='discussion.php?d=" .$disc['discID']. "' method='POST'>
												<div class='form-group'>
													<label for='body'>Leave a message</label>
													<textarea class='form-control' name='body' rows='5'></textarea>
												</div>
												<div class='form-group'>
													<button class='btn btn-primary' type='submit' name='post'><span class='glyphicon glyphicon-comment'></span> Submit</button>
													<input type='hidden' name='replied' value='TRUE'>
													<input type='hidden' name='pID' value='" .$mess['parentID']. "'> 
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>";


			}
		}
	?>
	

</div>