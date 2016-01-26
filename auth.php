<?php
session_start();


if(!isset($_SESSION['id']))
{
    echo '<SCRIPT LANGUAGE="JavaScript">document.location.href="index.php"</SCRIPT>';
}
else
{   
	include_once("config.php");
		// set up DB
		mysql_connect(PHPGRID_DBHOST, PHPGRID_DBUSER, PHPGRID_DBPASS);
		mysql_select_db(PHPGRID_DBNAME);
		$n = $_SESSION['id'];
		$sql = "SELECT name FROM user WHERE id = $n";
		$result = mysql_query( $sql ) or die('Erreur SQL !'.$sql.''.mysql_error()); 
		$row = mysql_fetch_assoc($result); 
		$nom = $row['name'];
    
    echo 'Ok '.$nom.' ,vous avez identifi&eacute;, mais vous n\'avez pas acces &agrave; ce fichier !<br /><br /><a href="logout.php">deconnexion</a>';
}

?>
