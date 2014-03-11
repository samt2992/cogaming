<?php

	$pageTitle='Search Results';
	include('inc/header.inc.html');
	require_once('../mysqli_connect.php');

	$search=$_GET['keywords'];

	if(strlen($search)<=2)
	{
		echo "<div class='alert alert-warning'>
				<p>That's a very short search phrase, making it very dificult to find anything useful.<br/>
					Try searching for something else, or include '" .$search. "' in another search.</p>
				</div>";
	}else{
		$searchExplode=explode(" ", $search);

		foreach($searchExplode as $searchWord)
		{
			$x++;
			if($x==1)
			{
				$colName.="collective.colName LIKE '%$searchWord%' ";
				$gamName.="gamName LIKE '%$searchWord%'";
				$subj.="subj LIKE '%$searchWord%' ";
			}else{
				$colName.= "AND collective.colName LIKE '%$searchWord%' ";
				$gamName.=" AND gamName LIKE '%$searchWord%'";
				$subj.="AND subj LIKE '%$searchWord%' ";
			}

		}

		###########################################################
		############# Querying through Collectives ################
		###########################################################
		$q="SELECT collective.colName, collective.profImgUrl, comColID, collective.descrip FROM community
			JOIN collective ON community.comColID=collective.colID
			WHERE $colName";
		$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));

		if(mysqli_num_rows($r)>0)
		{
			echo "<div class='page-header'>
						<h2>Communities</h2>
					</div>";
			while($cols=mysqli_fetch_array($r, MYSQLI_ASSOC))
			{
				echo "<div class='media'>
						<a class='pull-left' href='collective/community.php?c=" .$cols['comColID']. "'>
							<img class='media-object img-responsive' src='/gaming" .$cols['profImgUrl']. "'
								style='max-height:140px;'>
						</a>
						<div class='media-body'>
							<h4 class='media-heading'>" .$cols['colName']. " Community</h4>
							<p>" .$cols['descrip']. "</p>
						</div>
					  </div>";
			}

		}
		$q="SELECT collective.colName, collective.profImgUrl, grpColID, collective.descrip FROM groups
			JOIN collective ON groups.grpColID=collective.colID
			WHERE $colName";
		$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));

		if(mysqli_num_rows($r)>0)
		{
			echo "<div class='page-header'>
						<h2>Communities</h2>
					</div>";
			while($cols=mysqli_fetch_array($r, MYSQLI_ASSOC))
			{
				echo "<div class='media'>
						<a class='pull-left' href='collective/community.php?c=" .$cols['grpColID']. "'>
							<img class='media-object img-responsive' src='/gaming" .$cols['profImgUrl']. "'
								style='max-height:140px;'>
						</a>
						<div class='media-body'>
							<a  href='collective/community.php?c=" .$cols['grpColID']. "'>
								<h4 class='media-heading'>" .$cols['colName']. "</h4>
							</a>
							<p>" .$cols['descrip']. "</p>
						</div>
					  </div>";
			}
		}
		$q="SELECT gamID, gamName, descrip, profImgUrl FROM games 
			WHERE $gamName";
		$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));

		if(mysqli_num_rows($r)>0)
		{
			echo "<div class='page-header'>
					<h2>Games</h2>
				  </div>";

			while($games=mysqli_fetch_array($r))
			{
				echo "<div class='media'>
						<a class='pull-left' href='games/game.php?g=" .$games['gamID']. "'>
							<img class='media-object img-responsive' src='/gaming" .$games['profImgUrl']. "'
								style='max-height:140px;'>
						</a>
						<div class='media-body'>
							<a href='games/game.php?g=" .$games['gamID']. "'>
								<h4 class='media-heading'>" .$games['gamName']. " Page</h4>
							</a>
							<p>" .$games['descrip']. "</p>
						</div>
					  </div>";
			}
		}
		$q="SELECT discID, users.username, discussion.userID, users.profImgID, subj, discussion.colID, collective.colName FROM discussion
			JOIN users ON discussion.userID=users.userID
			JOIN collective ON discussion.colID = collective.colID
			WHERE $subj";
		$r=mysqli_query($dbc, $q) or die(mysqli_error($dbc));


		if(mysqli_num_rows($r)>0)
		{
			echo "<div class='page-header'>
					<h2>Games</h2>
				  </div>";

			while($discs=mysqli_fetch_array($r))
			{
				echo "<div class='media'>
						<a class='pull-left' href='collective/discussion.php?d=" .$discs['discD']. "'>
							<img class='media-object img-responsive' src='/gaming" .$discs['profImgID']. "'
								style='max-height:140px;'>
						</a>
						<div class='media-body'>
							<a href='collective/discussion.php?d=" .$discs['discD']. "'>
								<h4 class='media-heading'>" .$discs['subj']. "</h4>
							</a>
							<p>By <a href='/gaming/users/user.php?u=" .$discs['userID']. "'>" .$discs['username']. " to " .$disc['colName']. "</p>
						</div>
					  </div>";
			}
		}



	}

?>