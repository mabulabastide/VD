<?php 
$connexion = mysql_connect('localhost','root','');
if(!$connexion)
{
	die("Impossible de se connecter a la base");
}
$db = mysql_select_db('remchk',$connexion);
if(!$db)
{
	die("Impossible de selectionner la base !");
}
$name = $_POST['name'];
$pass = $_POST['pass'];

if(empty($name) || empty($pass))
{
	echo '<SCRIPT LANGUAGE="JavaScript">document.location.href="index.php"</SCRIPT>';
}
else
{
	$sql = "SELECT * FROM user WHERE name='$name'";
	$req = mysql_query($sql) or die("Erreur SQL");

	while($data = mysql_fetch_array($req))
	{
		$mdp = $data['pass'];
		$id = $data['id'];
		
	}

	if(md5($pass) == $mdp)
	{		
		session_start();

		$_SESSION['id'] = $id;
		
		echo '<SCRIPT LANGUAGE="JavaScript">document.location.href="grid.php"</SCRIPT>';

	}
	else
	{
		echo '<SCRIPT LANGUAGE="JavaScript">alert("Login ou mot de passe non reconnu !\nEssayez de nouveau.")</SCRIPT>';
		echo '<SCRIPT LANGUAGE="JavaScript">document.location.href="index.php"</SCRIPT>';
	}
}
?>