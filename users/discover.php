<?php

$pageTitle='Discover Games';
include('../inc/header.inc.html');

?>

<div class='container'>
	<div class='page-header'>
		<h1>Discover New Games!<small> Join communities you like the sound of!</small>
	</div>
	<?php
		//Find the amount rows wanting to display

		require_once(MYSQL);
		
		$display = 6;

		if(isset($_POST['submitted']))
		{
			$q="SELECT profImgUrl, gamNam, Descrip, comColID FROM games
				JOIN community ON games.gamID = community.gamID 
				ORDER BY Rand() LIMIT $display";
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
						<a href='/gaming/collective/community.php?c=" . $row['comColID'] . "&p=1&s=1' alt='" . $row['gamNam'] . " Community Page'>
							<img data-src='holder.js/300x200' alt='Game Image' src='/gaming/" . $row['profImgUrl'] . "'>
						</a>	
							<div class='caption'>
								<h3>" . $row['gamNam'] . "</h3>
								<p>" . $row['Descrip'] . "</p>
								<button class='btn btn-success' type='button'>Join</button>
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
							<a href='/gaming/collective/community.php?c=" . $row['community.comColID'] . "&p=1&s=1' alt='" . $row['gamNam'] . " Community Page'>
								<img data-src='holder.js/300x200' alt='Game Image' src='/gaming/" . $row['profImgUrl'] . "'>
							</a>
							<div class='caption'>
								<h3>" . $row['gamNam'] . "</h3>
								<p>" . $row['Descrip'] . "</p>
								<button class='btn btn-success' type='button'>Join</button>
							</div>
						</div>
					</div>";

				}
								
			}
			echo "</div>";
			mysqli_free_result($r);
			mysqli_close($dbc);


			



		}
		echo "<form role='refresh' method='POST' action='discover.php'>
					<div class='form-group'>
						<button type='submit' class='btn btn-primary' name='refresh'>Discover More</button>
					</div>
					<input type='hidden' name='submitted' value='TRUE'>
				</form>";


	?>
</div>
