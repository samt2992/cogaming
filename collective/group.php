<?php
$pageTitle='Group';
include('../inc/header.inc.html');
?>
<?php
	require_once(MYSQL);
	$userID = $_SESSION['userID'];
	$colID = $_GET['g'];

	#######################################
	#####	Gathering Grp Info   ##########
	#######################################
	$q="SELECT relGames, userID, profImgUrl, colName, descrip, dateCre FROM groups
		JOIN collective ON groups.grpColID=collective.colID
		WHERE grpColID=$colID";
	$r=mysqli_query($dbc, $q);
	$row=mysqli_fetch_array($r, MYSQLI_ASSOC);

	####################################
	######	Check for join   ###########
	####################################
	if(isset($_POST['joined']))
	{
		$jo="INSERT INTO registerd (userID, colID, regDate)VALUES($userID, $colID, NOW())";
		$ru=mysqli_query($dbc, $jo)or die(mysqli_error($dbc));

	}

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


?>
<div class='container'>
	<div class='carousel slide' id='com-reel' data-ride='carousel'>
		<!-- Indicators for each slide	-->
		<ol class='carousel-indicators'>
			<li data-target='#com-reel' data-slide='0' class='active'></li>
			<li data-target='#com-reel' data-slide='1'></li>
			<li data-target='#com-reel' data-slide='2'></li>
		</ol>
		<div class='carousel-inner'>
			<div class='item active'>
				<img src="../img/comms/<?php echo $row['gamID'];?>/1.jpg" alt='Community Art' class='img-responsive'>
				
			</div>
			<div class='item'>
				<img src="../img/comms/<?php echo $row['gamID'];?>/2.jpg" alt='Community Art' class='img-responsive'>
				
			</div>
			<div class='item'>
				<img src="../img/comms/<?php echo $row['gamID'];?>/3.jpg" alt='Community Art' class='img-responsive'>
				
			</div>
		</div>
		<!-----  Controls for the slideshow	------>
		<a class='left carousel-control' href='#com-reel' data-slide='prev'>
			<span class='glyphicon glyphicon-chevron-left'></span>
		</a>
		<a class='right carousel-control' href='#com-reel' data-slide='next'>
			<span class='glyphicon glyphicon-chevron-right'></span>
		</a>
	</div>
	<div class='page-header'>
		<h1> <?php echo $row['colName']; ?> Community<br/>
			<small><?php echo $row['descrip'];?></small></h1>
	</div>
	
	<div class='col-md-4'>
		<!--	Community info and stuff(sidebar)	-->
		<div class='panel-group' id='sidebar'>
			<div class='panel panel-default'>
				<a data-toggle='collapse' data-parent='#sidebar' href='#sidebarMain'>
					<div class='panel-heading'>
						
						<h4><?php echo $row['colName']; ?> Community Info<b class='caret'></b></h4>
					</div>
				</a>
				<div class='panel-collapse collapse in' id='sidebarMain'>
					<div class='panel-body'>
						
							<?php 
							$path='/img/comms/grpDef.png'
							if($row['profImgUrl']==$path)
							{
								echo "<img class='img-roudned img-responsive' src='/gaming" .$row['profImgUrl']. "' style='max-height:200px'>
									<p>";

									$q="SELECT COUNT(discID) FROM discussion WHERE colID=$colID";
									$r=mysqli_query($dbc, $q);
									$count=mysqli_fetch_array($r, MYSQLI_NUM);
									echo "No. of Posts: " . $count[0]. "</p>";

									echo "
									<a href='grpImages.php?c=" .$colID. "' alt='Upload'>
										<button style='margin-left:10px; margin-bottom:5px' class='btn btn-success' type='button'><span class='glyphicon glyphicon-picture'></span> View Images</button>
									</a>
									<a href='createPost.php?c=" .$colID. "' alt='Create new Post'>
										<button style='margin-left:10px' class='btn btn-primary' type='button'><span class='glyphicon glyphicon-edit'></span> Create Post</button>
									</a>";

							}else{
							echo"<div>
									<img class='img-circle' src='/gaming" .$row['profImgUrl']. "' style='max-height:120px'>
									
										<p class='text-right' style='margin-left:10px'>";
										
											$q='SELECT COUNT(discID) FROM discussion WHERE colID=$colID';
											$r=mysqli_query($dbc, $q);
											$count=mysqli_fetch_array($r, MYSQLI_NUM);
											echo 'No. of Posts: ' . $count[0];
										
										
									echo"</p>
										<a href='grpImages.php?c=<?php echo $colID;?>' alt='Upload'>
											<button style='margin-left:10px; margin-bottom:5px' class='btn btn-success' type='button'><span class='glyphicon glyphicon-picture'></span> View Images</button>
										</a>
										<a href='createPost.php?c=<?php echo $colID;?>' alt='Create new Post'>
											<button style='margin-left:10px' class='btn btn-primary' type='button'><span class='glyphicon glyphicon-edit'></span> Create Post</button>
										</a>
									
								</div>";
							}

							if($_SESSION['userID']==$row['userID'])
							{
								echo "<a href='editGroup.php?g=" .$colID. "' alt='Edit Group'>
										<button type='button' class='btn btn-default' name='edit'> Edit Group Details </button>
										</a>"
							}
						?>
						<div style='clear:both'>
						<?php 
						$qu="SELECT userID, colID FROM registerd WHERE userID=$_SESSION[userID] AND colID=$colID";
						$j=mysqli_query($dbc, $qu);
						if(isset($_SESSION['userID']) && (mysqli_num_rows($j)==0))
						{
						echo "<form role='join' action='community.php?c=$colID' method='post'>
								<button class='btn btn-primary' type='submit' name='join'>Join</button>
								<input type='hidden' value='TRUE' name='joined'>
							</form>";


						}
						?>
						
							<h5 style='padding-top:5px'>Related Topics</h5>
						<?php	
							$tags=explode(',', $row['relGames']);
							foreach($tags as $tag)
							{
								echo "<a href='search.php?s=" .str_replace(' ','',$tag). "'>" .$tag. "</a>,";
							}
							$q="SELECT COUNT(userID) FROM registerd WHERE colID=$colID";
							$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));
							$users=mysqli_fetch_array($r, MYSQLI_NUM);
							echo "<p>Currently active users: " .$users[0]. "</p>";
						?>
						<h5 style='padding-top:5px'>Top Posters</h5>
						<table class='table table-striped'>
							<tbody>

						<?php
							$q="SELECT username, COUNT(discID), colID FROM discussion 
								JOIN users ON users.userID=discussion.userID
								WHERE colID=$colID
								GROUP BY username ORDER BY COUNT(discID) DESC LIMIT 5";
							$r=mysqli_query($dbc, $q);
							while($top=mysqli_fetch_array($r, MYSQLI_ASSOC))
							{
								echo "<tr>
										<td>" . $top['username'] . "</td>
										<td>" . $top['COUNT(discID)'] . "</td>
									</tr>";

							}
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
				<a href="group.php?sort=??&g=$colID" alt='Create new post'> Sort </a>
			</small></h2>
		<?php
			$que="SELECT subj, rating, discID, username, discussion.userID, dateCre FROM discussion
					 JOIN users ON discussion.userID=users.userID WHERE colID=$colID ORDER BY rating DESC
					 LIMIT $start, 5";
			$res=mysqli_query($dbc, $que) or die(mysqli_error($dbc));
			
			/*	Create the panels(5) */
			while($stor=mysqli_fetch_array($res, MYSQLI_ASSOC))
			{
				echo "<div class='panel panel-primary'>
						<a href='discussion.php?d=" . $stor['discID'] . "'><div class='panel-heading' style='padding:5px 15px'>
							<h4>" . $stor['subj'] . "<small> Rating: " .$stor['rating']. "</small></h4>
						</div></a>
						<div class='panel-body'>
							<p>by <a href='../users/profile.php?p=" . $stor['userID'] . "'>" . $stor['username']
											 . "</a> on " . $stor['dateCre'] . "</p>
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
							echo "<li><a href='group.php?g=$colID&p=" . $page . "&s=" . ($start-5) . "'>&laquo;</a></li>";
						}
						for($i=1;$i<=$page;$i++)
						{
							echo "<li><a href=''group.php?g=$colID&p=" . $page . "&s=" . (5*($i-1)) . "'>" . $i . "</a></li>";
						}
						if($currentPage!=$page)
						{
							echo "<li><a href=''group.php?g=$colID&p=" . $page . "&s=" . ($start+5) . "'>&raquo;</a></li>";

						}
						echo "</ul>";
					}
				?>
		</div>
	</div>
</div>
<?php
mysqli_close($dbc);
include('../inc/footer.inc.html');
?>
