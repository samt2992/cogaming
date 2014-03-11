<?php

$pageTitle = 'Top Communities';
include('../inc/header.inc.html');
require_once(MYSQL);
	####################################
	#######	Code for Pagination  #######
	####################################
	$q="SELECT COUNT(colID) FROM collective";
	$r=mysqli_query($dbc, $q);
	$pa=mysqli_fetch_array($r, MYSQLI_NUM);

	$total=$pa[0];

	if(isset($_GET['p']) && is_numeric($_GET['p']))
	{
		$page=$_GET['p'];
	}elseif($total>6){
		$page=ceil($total/6);
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
	<div class='page-header'>
		<h1>Top Communities<small> Join in on the best of the chatter</small>
	</div>
	<?php
		//Find the amount rows wanting to display

		
		
		$display = 6;

		
			$q="SELECT collective.profImgUrl, collective.colName, collective.descrip, comColID, COUNT(registerd.colID) AS freq
				FROM community
				JOIN collective ON community.comColID=collective.colID
				JOIN registerd ON collective.colID=registerd.colID
				GROUP BY comColID  
				ORDER BY freq DESC
				LIMIT $display";
			$r=mysqli_query($dbc, $q)
				or die("Error: " . mysqli_error($dbc));
			
			//cREATE A FOR LOOP GOING 1-3 FOR FIRST ROW
			echo "<div class='row'>";
			for($i = 1; $i<=3; $i++)
			{
				while($row=mysqli_fetch_array($r, MYSQLI_ASSOC))
				{
				echo "<div class='col-md-4'>
						<div class='thumbnail'>
						<a href='/gaming/collective/community.php?c=" . $row['comColID'] . "&p=1&s=1' alt='" . $row['colName'] . " Community Page'>
							<img data-src='holder.js/300x200' alt='Game Image' src='/gaming/" . $row['profImgUrl'] . "'>
						</a>	
							<div class='caption'>
								<h3>" . $row['colName'] . "</h3>
								<p>" . $row['descrip'] . "</p>
								<a href='/gaming/collective/community.php?c=" . $row['comColID'] . "&p=1&s=1' alt='" . $row['colName'] . " Community Page'>
									<button class='btn btn-success' type='button'>Peek In</button>
								</a>
							</div>
						</div>
					</div>";
				}

			}
			echo "</div>
				  <div class='row'>";

			//Create a for loop for 4-6 for second row
			for($i=1;$i<=3;$i++)
			{
				while($row)
				{
				echo "<div class='col-md-4'>
						<div class='thumbnail'>
						<a href='/gaming/collective/community.php?c=" . $row['comColID'] . "&p=1&s=1' alt='" . $row['colName'] . " Community Page'>
							<img data-src='holder.js/300x200' alt='Game Image' src='/gaming/" . $row['profImgUrl'] . "'>
						</a>	
							<div class='caption'>
								<h3>" . $row['colName'] . "</h3>
								<p>" . $row['descrip'] . "</p>
								<a href='/gaming/collective/community.php?c=" . $row['comColID'] . "&p=1&s=1' alt='" . $row['colName'] . " Community Page'>
									<button class='btn btn-success' type='button'>Peek In</button>
								</a>
							</div>
						</div>
					</div>";
				}
								
			}
			echo "</div>";
			mysqli_free_result($r);
			mysqli_close($dbc);


			


	?>
	<div class='col-md-4 col-md-offset-4'>
				<!--	Pagination	-->
				<?php
					
					if($page>1)
					{
						echo "<ul class='pagination'>";

						$currentPage=($start/6)+1;
						if($currentPage != 1)
						{
							echo "<li><a href='topCommunities.php?p=" . $page . "&s=" . ($start-6) . "'>&laquo;</a></li>";
						}
						for($i=1;$i<=$page;$i++)
						{
							echo "<li><a href='topCommunities.php?p=" . $page . "&s=" . (6*($i-1)) . "'>" . $i . "</a></li>";
						}
						if($currentPage!=$page)
						{
							echo "<li><a href='topCommunities.php?p=" . $page . "&s=" . ($start+6) . "'>&raquo;</a></li>";

						}
						echo "</ul>";
					}
				?>
		</div>
</div>
