<?php 
# ----------------------------------------------------------------------------
#--------------------	Config File for Gaming Site --------------------------
#-----------------------------------------------------------------------------

define('LIVE', FALSE); //Set to true when the site is live(error messages are sent to browser when false)
define('EMAIL', 'samt2992@gmail.com');	//Where errors are emailed to

define('BASE_URL', '/gaming/');
define('IMG_BASE', 'C:/xampp/htdocs/gaming/');
define('MYSQL', '../../mysqli_connect.php');

date_default_timezone_set('EUROPE/LONDON');

#------------------------------------------------------------------------------
#---------------------		MAnaging Errors 	-------------------------------
#------------------------------------------------------------------------------

function myErrorHandler($eNumber, $eMessage, $eFile, $eLine, $eVars)
{
	$message= "<p>An error occured in $eFile on $eLine: $eMessage\n";
	$message.="Date/Time:" . date('n-j-Y H:i:s') . "\n";
	$message.="<pre>" . print_r($eVars, 1) . "</pre>\n";

	if(!LIVE)
		{
			echo "<div class='alert alert-danger'>
					<strong>Error</strong>"
					. $message . "</div>";
		}else{
			mail(EMAIL, 'Site Error', $message, 'From: antichav-man@hotmail.co.uk');
			if($eNumber != E_NOTICE)
			{
				echo "<div class='alert alert-warning'>
						<strong>Sorry</strong>
						A system error occured, it's not you, it's me</div>";

			}
		}
}

function userCount($colID){
	require_once('../../mysqli_connect.php');
	$q="SELECT COUNT(userID) FROM registerd WHERE colID=$colID";
	$r=mysqli_query($dbc, $q);
	$count=mysqli_fetch_array($r, MYSQLI_NUM);

	return $count[0];
}
set_error_handler('myErrorHandler');


