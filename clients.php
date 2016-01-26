<?php
/**
 * PHP Grid Component
 *
 * @author Abu Ghufran <gridphp@gmail.com> - http://www.phpgrid.org
 * @version 1.5.2
 * @license: see license.txt included in package
 */

session_start();
// set time-out period (in seconds)
$inactive = 1200;

// check to see if $_SESSION["timeout"] is set
if (isset($_SESSION["timeout"])) {
    // calculate the session's "time to live"
    $sessionTTL = time() - $_SESSION["timeout"];
    if ($sessionTTL > $inactive) {
        session_destroy();
        header("Location: logout.php");
    }
}

$_SESSION["timeout"] = time();

//local :  1 = mb, 9 = eva, 8 = admin   labastide.net : 1 = mb, 10 = eva, 8 =admin
if($_SESSION['id']!=8 && $_SESSION['id']!=10 && $_SESSION['id']!=1)
{
	echo '<SCRIPT LANGUAGE="JavaScript">document.location.href="index.php"</SCRIPT>';
}
else
{

	// include db config
	include_once("config.php");
		// set up DB
		mysql_connect(PHPGRID_DBHOST, PHPGRID_DBUSER, PHPGRID_DBPASS);
		mysql_select_db(PHPGRID_DBNAME);
		// include and create object
		include(PHPGRID_LIBPATH."inc/jqgrid_dist.php");
	$g = new jqgrid();

	// set database table for CRUD operations
	$g->table = "t_clients";

	// set few params
	$grid["caption"] = "Clients vente directe";
	$grid["export"] = array("format"=>"pdf", "filename"=>"my-file", "heading"=>"Clients vente directe ", "orientation"=>"landscape","paper"=>"a4");

	$grid["export"]["render_type"] = "html";
	// export filtered data or all data
	$grid["export"]["range"] = "all"; // or "all"

	// params are array(<function-name>,<class-object> or <null-if-global-func>)
	$e["on_render_pdf"] = array("set_pdf_format", null);
	$g->set_events($e);

	function set_pdf_format($param)
	{
	    $grid = $param["grid"];
	    $arr = $param["data"];
	    //$grid->SetFont('helvetica', '', 11);

	    $html .= "<h1>".$grid->options["export"]["heading"]."</h1>";
	    $html .= '<table border="1" cellpadding="2" cellspacing="1">';

	    $i = 0;
	    foreach($arr as $v)
	    {
	        $shade = ($i++ % 2) ? 'bgcolor="#efefef"' : '';
	        $html .= "<tr>";
	        foreach($v as $d)
	        {
	            // bold header
	            if  ($i == 1)
	                $html .= "<td bgcolor=\"lightgrey\"><strong>$d</strong></td>";
	            else
	                $html .= "<td $shade>$d</td>";
	        }
	        $html .= "</tr>";
	    }

	    $html .= "</table>";
	    return $html;
	}

	//$opt["export"]["range"] = "filtered";
	$grid["autowidth"] = true; // expand grid to screen width
	$grid["height"] = "400";
	$grid["sortname"] = 'Nom'; // by default sort grid by this field
	$grid["sortorder"] = "asc"; // ASC or DESC
	$g->set_options($grid);

	// disable all dialogs except edit
	$g->navgrid["param"]["edit"] = false;
	$g->navgrid["param"]["add"] = false;
	$g->navgrid["param"]["del"] = false;
	$g->navgrid["param"]["search"] = false;
	$g->navgrid["param"]["refresh"] = true;

	$g->set_actions(array(
			"add"=>true, // allow/disallow add
			"edit"=>true, // allow/disallow edit
			"delete"=>true, // allow/disallow delete
			"inline"=>true,
			"rowactions"=>true, // show/hide row wise edit/del/save option
			"autofilter" => true, // show/hide autofilter for search
			"export" => true,
			"search" => "advance" // show single/multi field search condition (e.g. simple or advance)
			)
		);

	$col = array();
	$col["title"] = " ID"; // caption of column
	$col["name"] = "ID"; // field name, must be exactly same as with SQL prefix or db field
	$col["hidden"] = true;
	$col["width"] = "10";
	$col["export"] = false; // this column will not be exported
	//$col["editrules"] = array("required"=>true);
	$cols[] = $col;

	$col = array();
	$col["title"] = " Nom"; // caption of column
	$col["name"] = "Nom"; // field name, must be exactly same as with SQL prefix or db field
	$col["hidden"] = false;
	$col["width"] = "80";
	$col["editable"] = true;
	//$col["export"] = false; // this column will not be exported
	$col["editrules"] = array("required"=>true);
	$cols[] = $col;

	$col = array();
	$col["title"] = "Prénom"; // caption of column
	$col["name"] = "Prenom"; // field name, must be exactly same as with SQL prefix or db field
	$col["hidden"] = false;
	$col["width"] = "80";
	$col["editable"] = true;
	//$col["export"] = false; // this column will not be exported
	//$col["editrules"] = array("required"=>true);
	$cols[] = $col;

	$col = array();
	$col["title"] = "Adresse"; // caption of column
	$col["name"] = "Adresse"; // field name, must be exactly same as with SQL prefix or db field
	$col["hidden"] = false;
	$col["width"] = "110";
	$col["editable"] = true;
	//$col["export"] = false; // this column will not be exported
	//$col["editrules"] = array("required"=>true);
	$cols[] = $col;

	$col = array();
	$col["title"] = " Code poste"; // caption of column
	$col["name"] = "CodePostal"; // field name, must be exactly same as with SQL prefix or db field
	$col["hidden"] = false;
	$col["width"] = "40";
	$col["editable"] = true;
	//$col["export"] = false; // this column will not be exported
	//$col["editrules"] = array("required"=>true);
	$cols[] = $col;

	$col = array();
	$col["title"] = "Ville"; // caption of column
	$col["name"] = "Ville"; // field name, must be exactly same as with SQL prefix or db field
	$col["hidden"] = false;
	$col["width"] = "100";
	$col["editable"] = true;
	//$col["export"] = false; // this column will not be exported
	//$col["editrules"] = array("required"=>true);
	$cols[] = $col;

	$col = array();
	$col["title"] = "Téléphone"; // caption of column
	$col["name"] = "Tel"; // field name, must be exactly same as with SQL prefix or db field
	$col["hidden"] = false;
	$col["width"] = "70";
	$col["editable"] = true;
	//$col["export"] = false; // this column will not be exported
	//$col["editrules"] = array("required"=>true);
	$cols[] = $col;

	$col = array();
	$col["title"] = " E-Mail"; // caption of column
	$col["name"] = "Email"; // field name, must be exactly same as with SQL prefix or db field
	$col["hidden"] = false;
	$col["width"] = "100";
	$col["editable"] = true;
	//$col["export"] = false; // this column will not be exported
	//$col["editrules"] = array("required"=>true);
	$cols[] = $col;

	$g->set_columns($cols);
	// render grid
	$out = $g->render("list1");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <link rel="stylesheet" type="text/css" media="screen" href="lib/js/themes/south-street/jquery-ui.custom.css"></link>
    <link rel="stylesheet" type="text/css" media="screen" href="lib/js/jqgrid/css/ui.jqgrid.css"></link>

    <script src="lib/js/jquery.min.js" type="text/javascript"></script>
    <script src="lib/js/jqgrid/js/i18n/grid.locale-fr.js" type="text/javascript"></script>
    <script src="lib/js/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
    <script src="lib/js/themes/jquery-ui.custom.min.js" type="text/javascript"></script>
    <style >
    h2 {
    text-align: center;}
    </style>
</head>
<body>
	<div>
		<span >
		<h2>Vente directe : Clients</h2>
		</span>
	</div>
    <div style="margin:10px">
    <?php echo $out?>
    </div>
    <div class= 'container-fluid '>
			<?php echo '<br /><h3><a href="logout.php">		Se d&eacute;connecter</a><br>'; ?>
			<?php echo '<a href="grid.php">	 Afficher le listing des ventes</a></h3>'; ?>
	</div>
</body>
</html>
