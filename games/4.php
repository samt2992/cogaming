<?php
	$gamID='4';
	$pageTitle='Halo 4';
	include('../inc/header.inc.html');

	require_once(MYSQL);
	$q="SELECT * FROM games WHERE gamID=$gamID";
	$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));
	$info=mysqli_fetch_array($r, MYSQLI_ASSOC);

	$q="SELECT comColID FROM community 
		WHERE gamID=$info[gamID]";
	$r=mysqli_query($dbc, $q);
	$row=mysqli_fetch_array($r, MYSQLI_NUM);
	$id=$row[0];

	function plot(){
		echo "<p>
		Halo 4 returns players into the role of SPARTAN-II super-soldier Master Chief, 
		over four years after the Human-Covenant War, the activation of the Installation
		 04B (an incomplete Halo ring) and Master Chief's cryogenic incubation in the 
		 wreckage of the UNSC Forward Unto Dawn. After the ship wreckage crash-lands
		  inside the mysterious Forerunner shielded world of Requiem, Master Chief must 
		  fight a newly-formed splinter Covenant group (the Storm Covenant), communicate 
		  with the UNSC Infinity vessel (which also crash-lands into Requiem), deal with
		   the rampancy of his A.I. companion Cortana, and fight a new group of enemies 
		   (the dangerous Promethean A.I.) led by The Didact (a Forerunner Promethean, 
		   accidentally revived by the actions of Master Chief, who was formerly the 
		   supreme commander of the Forerunner military and holds a strong grudge towards 
		   humanity).
			</p>";

	}

	function gameplay(){
		echo "<p>
			As a tie-in to the in-game fiction, the multiplayer modes (collectively 
			known as \"Infinity\") are based on the SPARTAN-IV program in the UNSC 
			Infinity after the actions of the main campaign. The competitive multiplayer 
			mode, known as \"War Games\", are training simulations inside the UNSC Infinity. 
			Firefight mode (from Halo: Reach) is replaced with a new mission-based mode called 
			\"Spartan Ops\", which are episodic co-operative campaigns (each including its own 
			pre-cinematic dealing with the crew of the UNSC Infinity) Both modes allow players 
			to use their customized SPARTAN-IV (each with their own unlockable armor 
			permutations, identification, custom loadouts, and experience level, known as \"Spartan Ranks\").
			</p>
			<p>
			New gameplay mechanics to the franchise include a dedicated sprint button (with no Armor 
			Ability restriction), customizable multiplayer loadouts (similar to the Call of Duty 
			franchise), and Personal Ordnance Drops (in which players, after reaching a score 
			threshold in \"Infinity\" game modes, can summon a weapon, grenade, or power-up at their 
			location). Along with the standard Halo game modes ( Slayer, Oddball, Capture the Flag, 
			and King of the Hill), the game adds a variety of new base game modes (Dominion, 
			Extraction, Flood, Regicide, and Ricochet, as well as the official inclusion of Grifball) 
			in ten new maps (not including various DLC maps) using a variety of new weapons 
			(including weapons used by the Promethean A.I.). Forge Mode makes a return (adding new 
			features, including dynamic lighting and object locking), as well as Theater Mode 
			(for watching and recording game clips) and file sharing (for screenshots, game clips, 
			Forge map variations, and custom game modes).
			</p>
			<p>
			Prior to the game's release, a live-action webseries, titled Halo 4: Forward Unto Dawn, 
			was ran between October 5, 2012 and November 2, 2012 to promote the game. Set 32 years 
			prior to the game, the story follows Officer Cadet Thomas Lasky (who plays an important 
			support character in the game) as he struggles through the Corbulo military academy 
			only to help Master Chief defend the academy against the Covenant.
			</p>";
	}

	if(isset($_POST['liked']))
	{
		$q="UPDATE games SET rating=rating+1
			WHERE gamID=$gamID";
		$r=mysql_query($dbc, $q) or die(mysqli_error($dbc));

		$qu="INSERT INTO registerd(userID, colID)
			VALUES($_SESSION[userID], $id";
		$ru=mysqli_query($dbc, $qu) or die(mysqli_error($dbc));

		if(mysqli_affected_rows($dbc)==0)
		{
			echo "<div class='alert alert-success'>
					<p><strong>Whoop!</strong> You joined this community! Get posting! </p>
				  </div>";
		}else{
			echo "<div class='alert alert-success'>
					<p><strong>Sorry</strong>There was an error on our end, try again. <br/>
					If the problem persist, contact us
					</p>
				  </div>";
		}

	}

	if(isset($_POST['unliked']))
	{
		$q="UPDATE games SET rating=rating-1
			WHERE gamID=$gamID";
		$r=mysqli_query($dbc, $q);

		$qu="DELETE FROM registerd
			WHERE userID=$_SESSION[userID] AND colID=$id";
		$ru=mysqli_query($dbc, $qu);

		if(mysqli_affected_rows($dbc)==0)
		{
			echo "<div class='alert alert-warning'>
					<p>You have successfully left the community</p>
				</div>";
		}else{
			echo "<div class='alert alert-success'>
					<p><strong>Sorry</strong>There was an error on our end, try again. <br/>
					If the problem persists, contact us.
					</p>
				  </div>";
		}
	}	
?>

<style>
.jumbotron{
	background: url('/gaming/img/games/4/small.jpg');
}
@media only screen and (min-width:690px){
	.jumbotron{
		background: url("/gaming/img/games/4/big.jpg");
	}
}


</style>

<div class='container'>
	<div class='jumbotron'>
		<h1><?php echo $info['gamNam']; ?> </h1>
	</div>
	<div class='col-md-4'>
		<div class='panel-group' id='sidebar'>
			<div class='panel panel-default'>
				<a data-toggle='collapse' data-parent='#sidebar' href='#sidebarmain'>
					<div class='panel-heading'>
						<h4> <?php echo $info['gamNam'] ?> Info <b class='caret'></b></h4>
					</div>
				</a>
				<div class='panel-collapse collapse in' id='sidebarmain'>
					<div class='panel-body'>
						<img class='img-rounded img-responsive' src="/gaming/<?php echo $info['profImgUrl']; ?>"alt='Game Image'>
						<div style='margin-top:5px'>
							<div style='float:left'>
								<a href='/gaming/collective/community.php?c=<?php echo $id; ?>'>
									<button class='btn btn-primary' name='Community' type='button'>
										<span class='glyphicon glyphicon-home'></span> Community Page
									</button>
								</a>
							</div>
							<div style="float:right">
								<form role='rating' action="<?php echo $info['gamID'];?>.php" method='POST'>
									<?php
										$q="SELECT colID FROM registerd WHERE userID=$_SESSION[userID] AND colID=$id";
										$r=mysqli_query($dbc, $q);
										if(mysqli_num_rows($r)==0)
										{
											echo "<div class='form-group'>
													<button name='like' class='btn btn-success' type='submit'>
														<span class='glyphicon glyphicon-hand-up'></span> Like
													</button>
													<input type='hidden' name='liked' value='TRUE'>
												</div>";
										}else{
											echo "<div class='form-group'>
													<button name='like' class='btn btn-danger' type='submit'>
														<span class='glyphicon glyphicon-hand-up'></span> Un-Like
													</button>
													<input type='hidden' name='unliked' value='TRUE'>
												</div>";
										}?>
								</form>
							</div>
						</div>
						<div style="clear:both">
							<p><b>DoR: </b> <?php echo $info['DoR']; ?></p>
							<p><b>Platforms: </b> <?php echo $info['platform'];?></p>
							<p><b>Genre: </b> <?php echo $info['genre'];?> </p>
							<p><b>Developer: </b> <?php echo $info['developer'];?></p>
							<p><b>Publisher: </b> <?php echo $info['publisher'];?></p>
							<h4>Current Discussions</h4>
							<?php
								$q="SELECT subj, discID, users.username FROM discussion
									JOIN users ON discussion.userID=users.userID
									WHERE colID=$id
									ORDER BY dateCre DESC 
									LIMIT 5";
								$r=mysqli_query($dbc, $q);
								

								echo "<table class='table table-striped'>
										<thead>
											<tr>
												<th>Subject</th>
												<th>User</th>
											</tr>
										</thead>
										<tbody>";
									while($disc=mysqli_fetch_array($r, MYSQLI_ASSOC))
									{
										 echo "<tr>
										 		<td><a href='/gaming.collective/discussion.php?d=" .$disc['discID']. "' alt='Discussion'> " .$disc['subj']. "</td>
										 		<td>" .$disc['username']. "</td>
										 	   </tr>";
											  												
									}
								echo "	</tbody>
									 </table>";
								?>

						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<div class='col-md-8'>
		<h2>Description</h2>
			<?php
				echo $info['descrip'];
			?>
		<h2>Plot</h2>
			<?php
				plot();
			?>
		<h2>Gameplay</h2>
			<?php
				gameplay();
			?>
	</div>
</div>
<?php

include('../inc/footer.inc.html');
?>