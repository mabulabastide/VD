<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Vente directe</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Le styles -->
  <!--<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
  <style type="text/css">
    body {
      padding-top: 10px;
      padding-bottom: 0px;
    }
  </style>

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>

<body>
  <div style="text-align : center">
    <h1>Vente directe</h1>
  </div>
    <?php session_start(); if(!isset($_SESSION[ 'id'])) { echo '

        <form method="post" action="login.php">
        <table border="0px" align="center" cellpadding="0" cellspacing="0">
        <tr>
        <td align="center" valign="middle">
        <table border="0">
        <tr>
        <td>
        <b>Utilisateur :</b><br />
        <input type="text" name="name" /><br /><br />
        <b>Mot de passe :</b><br />
        <input type="password" name="pass"/><br /><br />
        <input type="submit" value="Connecter" /> <input type="reset" value="Annuler" />
        </td>
        </tr>
        </table>
        </td>
        </tr>
        </table>
        </form>
  '; } else { echo '<SCRIPT LANGUAGE="JavaScript">document.location.href="auth.php"</SCRIPT>'; } ?>

</body>
