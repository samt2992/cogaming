<?php

$pageTitle = 'Top Communities';
include('../inc/header.inc.html');
require_once(MYSQL);
	####################################
	#######	Code for Pagination  #######
	####################################
	$q="SELECT COUNT(gamID) FROM games";
	$r=mysqli_query($dbc, $q);
	$pa=mysqli_fetch_array($r, MYSQLI_NUM);

	$total=$pa[0];

	if(isset($_GET['p']) && is_numeric($_GET['p']))
	{
		$page=$_GET['p'];
	}elseif($total>8){
		$page=ceil($total/8);
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
		<h1>Top Games<small> See what everyone is loving right now</small>
	</div>
	<?php
		//Find the amount rows wanting to display

		
		
		$display = 8;

		
			$q="SELECT profImgUrl, gamName, descrip, rating
				FROM games
				ORDER BY rating DESC
				LIMIT $display";
			$r=mysqli_query($dbc, $q)
				or die("Error: " . mysqli_error($dbc));
			
			//cREATE A FOR LOOP GOING 1-3 FOR FIRST ROW
			echo "<div class='row'>";
			for($i = 1; $i<=4; $i++)
			{
				while($row=mysqli_fetch_array($r, MYSQLI_ASSOC))
				{
				echo "<div class='col-md-4'>
						<div class='thumbnail'>
						<a href='/gaming/games/game.php?g=" . $row['gamID'] . "&p=1&s=1' alt='" . $row['gamName'] . " Page'>
							<img data-src='holder.js/300x200' alt='Game Image' src='/gaming/" . $row['profImgUrl'] . "'>
						</a>	
							<div class='caption'>
								<h3>" . $row['gamName'] . "<small> Rating: " .$row['rating']. "</small></h3>
								<p>" . $row['descrip'] . "</p>
								<a href='/gaming/games/game.php?g=" . $row['gamID'] . "&p=1&s=1' alt='" . $row['gamName'] . " Page'>
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
			for($i=1;$i<=4;$i++)
			{
				while($row)
				{
				echo "<div class='col-md-4'>
						<div class='thumbnail'>
						<a href='/gaming/games/game.php?g=" . $row['gamID'] . "&p=1&s=1' alt='" . $row['gamName'] . " Page'>
							<img data-src='holder.js/300x200' alt='Game Image' src='/gaming/" . $row['profImgUrl'] . "'>
						</a>	
							<div class='caption'>
								<h3>" . $row['gamName'] . "<small> Rating: " .$row['rating']. "</small></h3>
								<p>" . $row['descrip'] . "</p>
								<a href='/gaming/games/game.php?g=" . $row['gamID'] . "&p=1&s=1' alt='" . $row['gamName'] . " Page'>
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
							echo "<li><a href='topGames.php?p=" . $page . "&s=" . ($start-6) . "'>&laquo;</a></li>";
						}
						for($i=1;$i<=$page;$i++)
						{
							echo "<li><a href='topGames.php?p=" . $page . "&s=" . (6*($i-1)) . "'>" . $i . "</a></li>";
						}
						if($currentPage!=$page)
						{
							echo "<li><a href='topGames.php?p=" . $page . "&s=" . ($start+6) . "'>&raquo;</a></li>";

						}
						echo "</ul>";
					}
				?>
		</div>
</div>
