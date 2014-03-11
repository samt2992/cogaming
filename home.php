<?php
$pageTitle = 'Your Hub';
include('inc/header.inc.html');
require_once('../mysqli_connect.php');
	$q="SELECT * FROM users WHERE userID=$_SESSION[userID]";
	$r=mysqli_query($dbc, $q);
	$user=mysqli_fetch_array($r, MYSQLI_ASSOC);

	####################################
	#######	Code for Pagination  #######
	####################################
	
	$q="SELECT COUNT(discID) FROM discussion
		JOIN collective ON discussion.colID=discussion.colID
		JOIN registerd ON collective.colID=registerd.colID
		WHERE registerd.userID=$_SESSION[userID]";
	$r=mysqli_query($dbc, $q);
	$cnt=mysqli_fetch_array($r, MYSQLI_NUM);

	$total=$cnt[0];
	if(isset($_GET['p']) && is_numeric($_GET['p']))
	{
		$page=$_GET['p'];

	}elseif($total>5){
		$page=ceil($total/5);
	}else{
		$page=1;
	}

	if(isset($_GET['s']) && is_numeric($_GET['s']))
	{
		$start=$_GET['s'];
	}else{
		$start=0;
	}
?>

<div class='container'>
	<div class='carousel slide' id='news-reel' data-ride='carousel'>
		<!-- Indicators for each Slide  -->
		<ol class='carousel-indicators'>
			<li data-target='#news-reel' data-slide='0' class='active'></li>
			<li data-target='#news-reel' data-slide='1'></li>
			<li data-target='#news-reel' data-slide='2'></li>
		</ol>
		<div class='carousel-inner'>
			<div class='item active'>
				<img src='img/articles/southBig.jpg' alt='South Park Art'>
				<div class='carousel-caption'>
					<h3>No more lies...</h3>
					<p>Stick of Truth is finally here!</p>
				</div>
			</div>
			<div class='item'>
				<img src='img/articles/soulsBig.jpg' alt=''>
				<div class='carousel-caption'>
					<h3>Our Last Lives</h3>
					<p>Dark Souls 2 is just around the corner. Bring extra credits.</p>
				</div>
			</div>
			<div class='item'>
				<img src='img/articles/titanBig.jpg' alt=''>
				<div class='carousel-caption'>
					<h3>Titans Falling</h3>
					<p>Does Titanfall live up to titanic standards?</p>
				</div>
			</div>
		</div>
		<!-----  Controls for the slideshow	------>
		<a class='left carousel-control' href='#news-reel' data-slide='prev'>
			<span class='glyphicon glyphicon-chevron-left'></span>
		</a>
		<a class='right carousel-control' href='#news-reel' data-slide='next'>
			<span class='glyphicon glyphicon-chevron-right'></span>
		</a>
	</div>
	<div class='page-header'>
		<h1>Central Hub<br/>
			<small>Connected to everything you need</small></h1>
	</div>
	<div class='col-md-4'>
		<!--	User info and stuff(sidebar)	-->

		<div class='panel-group' id='sidebar'>
			<div class='panel panel-default'>
				<a data-toggle='collapse' data-parent='#sidebar' href='#sidebarMain'>
					<div class='panel-heading'>
						<!-- Could make next few lines an if, incase user isnt logged in on this page -->
						<h4><?php echo $_SESSION['username']; ?> Info <b class='caret'></b></h4>
					</div>
				</a>
				<div class='panel-collapse collapse in' id='sidebarMain'>
					<div class='panel-body'>
						<!-- Elements Here	-->
						<div>
							<img class='img-circle img-responsive' src="/gaming<?php echo $user['profImgID'];?>" style='max-height:140px;'>
							</br>
							<?php
								$q="SELECT DATE_fORMAT(regDate, '%d-%b-%y'), COUNT(discussion.discID) FROM users
									JOIN discussion ON discussion.userID=users.userID
									WHERE users.userID=$user[userID]";
								$r=mysqli_query($dbc, $q);
								$info=mysqli_fetch_array($r, MYSQLI_NUM);
								ECHO "<p class='text-right'>Joined on: " . $info[0];
								ECHO "</br>Discussions Started: " . $info[1];

								$q="SELECT COUNT(messID) FROM messages WHERE userID=$user[userID]";
								$r=mysqli_query($dbc, $q);
								$cnt=mysqli_fetch_array($r, MYSQLI_NUM);
								echo "</br>Messages posted: " . $cnt[0];

							?>
							
						</div>
						<div style='clear:both'>
							<a href='users/edit.php' alt='Edit Profile'>
								<button class='btn btn-primary' type='button' name='edit'><span class='glyphicon glyphicon-edit'></span> Edit Profile</button>
							</a>
							<a href='users/userImages.php' alt='Upload'>
								<button class='btn btn-success' type='button'><span class='glyphicon glyphicon-picture'></span> View Images</button>
							</a>

							<h5>Subscribed Communities</h5>
							<table class='table table-default'>
								<tbody>
									<?php
									$q="SELECT collective.colName, community.comColID FROM collective
										JOIN registerd ON registerd.colID=collective.colID
										JOIN community ON community.comColID=collective.colID
										WHERE registerd.userID=$user[userID]
										LIMIT 5";
									$r=mysqli_query($dbc, $q);
									while($coms=mysqli_fetch_array($r, MYSQLI_ASSOC))
									{
										echo "<tr>
												<td>
													<a href='" . BASE_URL . "collective/community.php?c=" . $coms['comColID'] . "'>
														" . $coms['colName'] . "
													</a>
												</td>
											</tr>";
									}
									echo "<tr>
											<td>
												<a href='users/subComs.php?u=" . $user['userID'] . "'>More Communities</a>
											</td>
										  </tr>";
									?>
								</tbody>
							</table>
							<h5>Subscribed Groups</h5>
							<table class='table table-default'>
								<tbody>
									<?php
									$q="SELECT collective.colName, groups.grpColID FROM collective
										JOIN registerd ON registerd.colID=collective.colID
										JOIN groups ON groups.grpColID = collective.colID
										WHERE registerd.userID=$user[userID]
										LIMIT 5";
									$r=mysqli_query($dbc, $q);
									while($grps=mysqli_fetch_array($r, MYSQLI_ASSOC))
									{
										echo "<tr>
												<td>
													<a href='" . BASE_URL . "collective/groups.php?g=" . $grps['grpColID'] . "'>
													" . $grps['colName'] . "
													</a>
												</td>
											  </tr>";

									}
									echo "<tr>
											<td>
												<a href='users/subGrps.php?u=" . $user['userID'] . "'>More Groups</a>
											</td>
										  </tr>";
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class='col-md-8'>
		<!-- 	Top discussions/main section  	-->
		<h2>Top Stories
			<small>
				<a href="createPost.php?c=$colID" alt='Create new post'> Something here(Sort?) </a>
			</small></h2>
		<?php
			require_once('../mysqli_connect.php');
			$que="SELECT registerd.colID, collective.colName, discussion.discID, discussion.subj, discussion.dateCre, discussion.rating, discussion.userID, users.username FROM registerd
					JOIN collective ON registerd.colID = collective.colID
					 JOIN discussion ON collective.colID=discussion.colID
					 JOIN users ON discussion.userID=users.userID
					 WHERE registerd.userID=$_SESSION[userID] 
					 ORDER BY discussion.rating DESC
					 LIMIT $start, 5";
			$res=mysqli_query($dbc, $que) or die(mysqli_error($dbc));
			
			/*	Create the panels(5) */
			while($stor=mysqli_fetch_array($res, MYSQLI_ASSOC))
			{
				echo "<div class='panel panel-primary'>
						<a href='collective/discussion.php?d=" . $stor['discID'] . "'>
						<div class='panel-heading' style='padding:5px 15px'>
							<h4>" . $stor['subj'] . "<small> Rating: " .$stor['rating']. "</small></h4>
						</div></a>
						<div class='panel-body' style='padding:5px 10px'>
							<p>by <a href='users/profile.php?p=" . $stor['userID'] . "'>" . $stor['username']
											 . "</a> to <a href='collective/community.php?c=" . $stor['colID'] .
											 "'>" . $stor['colName'] . "</a> on " . $stor['dateCre'] . "</p>
						</div>
					</div>";


			}

		?>
			<div class='col-md-4 col-md-offset-4'>
				<!--	Pagination	-->
				<?php
					if($page>1)
					{
						echo "<ul class='pagination'>";

						$currentPage=($start/5)+1;
						if($currentPage != 1)
						{
							echo "<li><a href='home.php?&p=" . $page . "&s=" . ($start-5) . "'>&laquo;</a></li>";
						}
						for($i=1;$i<=$page;$i++)
						{
							echo "<li><a href='home.php?&p=" . $page . "&s=" . (5*($i-1)) . "'>" . $i . "</a></li>";
						}
						if($currentPage!=$page)
						{
							echo "<li><a href='home.php?&p=" . $page . "&s=" . ($start+5) . "'>&raquo;</a></li>";

						}
						echo "</ul>";
					}
				?>
		</div>
	</div>
</div>