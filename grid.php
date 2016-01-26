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
$inactive = 1200;  //session 20minutes

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

// 1 = mb, 10= eva, 8 =admin
if($_SESSION['id']!=8 && $_SESSION['id']!=10 && $_SESSION['id']!=1)
{
	header("Location: index.php");
}

else  //start
{
	include_once("config.php");

	mysql_connect(PHPGRID_DBHOST, PHPGRID_DBUSER, PHPGRID_DBPASS);
	mysql_select_db(PHPGRID_DBNAME);
	// include and create object
	include(PHPGRID_LIBPATH."inc/jqgrid_dist.php");

	// master grid : t_ventes
	$m_grid = new jqgrid();

	$m_grid->select_command = "SELECT * FROM t_ventes";

	$opt["caption"] = "Ventes";
	$opt["height"] = "150";
	$opt["sortorder"] = "desc";
	$opt["detail_grid_id"] = "detail";
	$opt["subgridparams"] = "NoVente,Date,NoVache";
	$opt["multiselect"] = false;
	$opt["autowidth"] = true; // expand grid to screen width

	$m_grid->set_options($opt);

	$m_grid->table = "t_ventes";

	$m_grid->set_actions(array(
							"add"=>true, // allow/disallow add
							"edit"=>true, // allow/disallow edit
							"delete"=>true, // allow/disallow delete
							"rowactions"=>true, // show/hide row wise edit/del/save option
							"export"=>false, // show/hide export to excel option
							"autofilter" => false, // show/hide autofilter for search
							"search" => "advance" // show single/multi field search condition (e.g. simple or advance)
						)
					);

	$col = array();
	$col["title"] = "N° Vente"; // caption of column
	$col["name"] = "NoVente"; // field name, must be exactly same as with SQL prefix or db field
	$col["width"] = "100";
	//$col["editable"] = true;
	//$col["show"] = array("list"=>true,"edit"=>true,"add"=>true,"view"=>true);
	$col["hidden"] = false;
  $col["align"] = "center";
	//$col["export"] = true; // this column will not be exported
	$m_cols[] = $col;

	$col = array();
	$col["title"] = "Date";
	$col["name"] = "Date";
	$col["formatter"] = "date"; // format as date
	$col["formatoptions"] = array("srcformat"=>'Y-m-d',"newformat"=>'d.m.Y', "opts" => array( "dayNames" => [ "Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi" ], "dayNamesMin" => [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ],"monthNames"=> [ "Janvier", "Fevrier", "Mars", "April", "Mai", "Juin", "Julliet", "Aout", "Septembre", "Oktobre", "Novembre", "Decembre" ]));
	$col["width"] = "100";
	$col["align"] = "right";
	$col["search"] = true;
	$col["editable"] = true;
  $col["align"] = "center";
	$m_cols[] = $col;

	$col = array();
	$col["title"] = "N° Vache";
	$col["name"] = "NoVache";
	$col["width"] = "100";
	$col["search"] = true;
	$col["editable"] = true;
  $col["align"] = "center";
	$m_cols[] = $col;

	$col = array();
	$col["title"] = "Genre";
	$col["name"] = "Genre";
	$col["width"] = "100";
	$col["search"] = true;
	$col["editable"] = true;
  $col["align"] = "center";
	$m_cols[] = $col;

	$col = array();
	$col["title"] = "Poids carcasse";
	$col["name"] = "PoidsCarcasse";
	$col["width"] = "100";
	$col["search"] = true;
	$col["editable"] = true;
  $col["align"] = "center";
	$m_cols[] = $col;

	$col = array();
	$col["title"] = "Prix abattage";
	$col["name"] = "PrixAbattage";
	$col["width"] = "100";
	$col["search"] = true;
	$col["editable"] = true;
  $col["align"] = "center";
	$m_cols[] = $col;

	$col = array();
	$col["title"] = "Prix découpe";
	$col["name"] = "PrixDecoupe";
	$col["width"] = "100";
	$col["search"] = true;
	$col["editable"] = true;
  $col["align"] = "center";
	$m_cols[] = $col;

	$col = array();
	$col["title"] = "Piece compta";
	$col["name"] = "NoPieceCompta";
	$col["width"] = "100";
	$col["search"] = true;
	$col["editable"] = true;
  $col["align"] = "center";
	$m_cols[] = $col;

	$m_grid->set_columns($m_cols);

	$out_master = $m_grid->render("Ventes");

	//______________________________________________________________________________________________________________________//


	// detail grid : t_detail

	$d_grid = new jqgrid();

	$d_grid->table = "t_detail";

	// receive id, selected row of parent grid
	$id = intval($_GET["rowid"]);
	$date = $_GET["Date"];
	$num = intval($_GET["NoVente"]);
	$vache = $GET["NoVache"];
	/*$genre = $GET["Genre"];
	$prix_decoupe = $GET["PrixDecoupe"];
	$prix_abattage = $GET["PrixAbattage"];
	$poids_carcasse = $GET["PoidsCarcasse"];
	$piece_compta = $GET["PieceCompta"];*/


	$d_grid->select_command = "SELECT DISTINCT t_detail.ID,NoVente,IDClient,PoidsAchat,PrixKgTTC, ModePaiement FROM t_detail
                        INNER JOIN t_clients on t_clients.ID = IDClient WHERE NoVente = $id ";


// disable all dialogs except edit
$d_grid->navgrid["param"]["edit"] = false;
$d_grid->navgrid["param"]["add"] = false;
$d_grid->navgrid["param"]["del"] = false;
$d_grid->navgrid["param"]["search"] = false;
$d_grid->navgrid["param"]["refresh"] = true;

	$d_grid->set_actions(array(
		"add"=>true, // allow/disallow add
		"edit"=>true, // allow/disallow edit
		"delete"=>true, // allow/disallow delete
		"inlineadd"=>true, // allow/disallow delete
		"rowactions"=>true, // show/hide row wise edit/del/save option
		"autofilter" => false, // show/hide autofilter for search
		"export" => true,
		)
	);

	//for export  {{
	$sql = "SELECT SUM(PoidsAchat) AS PA,SUM( PoidsAchat * PrixKgTTC ) AS value_sum FROM t_detail WHERE NoVente = $id";
	$result = mysql_query( $sql ) or die('Erreur SQL !'.$sql.''.mysql_error());
	$row = mysql_fetch_assoc($result);
	$sssumTTC = round($row['value_sum'],2);
	$sssumHT = $sssumTTC / 1.055;  //  HT 5.5%
	$sssumHT = round($sssumHT,2);
  $sssumTVA = $sssumTTC - $sssumHT;
	$sssPA = $row[PA];

	$opt = array();
	$opt["sortname"] = 'NoVente'; // by default sort grid by this field
	$opt["sortorder"] = "desc"; // ASC or DESC
	$opt["height"] = ""; // autofit height of subgrid
	$opt["autowidth"] = true; // expand grid to screen width
	$opt["caption"] = "Détails vente"; // caption of grid
	$opt["multiselect"] = false; // allow you to multi-select through checkboxes
	//$opt["reloadedit"] = false; // allow you to multi-select through checkboxes
	//$opt["autowidth"] = true; // expand grid to screen width
	$opt["export"] = array("format"=>"pdf", "filename"=>"Vente_directe", "heading"=>'Vente directe N°'.$num .' du '.$date .'      Total vente : '.$sssumTTC .' € TTC'.'  '.$sssumHT .' € HT  ' . $sssumTVA .' € TVA 5.5%'. '  Poids total vendu: '.$sssPA.' kg ', "orientation"=>"landscape", "paper"=>"a4");
	//}} export
  $opt["reloadedit"] = true;

	$d_grid->set_options($opt);

  //ID

	$col = array();
	$col["title"] = " ID"; // caption of column
	$col["name"] = "ID"; // field name, must be exactly same as with SQL prefix or db field
	$col["width"] = "10";
	$col["export"] = false; // this column will not be exported
  $col["align"] = "center";
	$cols[] = $col;

/*	$col = array();
	$col["title"] = " ID"; // caption of column
	$col["name"] = "ID"; // field name, must be exactly same as with SQL prefix or db field
	$col["hidden"] = true;
	$col["width"] = "100";
	$col["export"] = false; // this column will not be exported
	$col["editrules"] = array("required"=>true);
	$cols[] = $col;  */

//No Vente

	$col = array();
	$col["title"] = " N° Vente"; // caption of column
	$col["name"] = "NoVente"; // field name, must be exactly same as with SQL prefix or db field
	$col["hidden"] = true;
	$col["width"] = "100";
	$col["export"] = false; // this column will not be exported
	$col["editrules"] = array("required"=>true);
  $col["align"] = "center";
	$cols[] = $col;

  //Client

	$col = array();
	$col["title"] = "Client";
	$col["name"] = "IDClient";
	$col["dbname"] = "t_details.IDClient"; // this is required as we need to search in name field, not id
	$col["width"] = "100";
	$col["align"] = "left";
	$col["search"] = true;
	$col["editable"] = true;
	$col["edittype"] = "select"; // render as select

	# fetch data from database, with alias k for key, v for value
	$str = $d_grid->get_dropdown_values("SELECT DISTINCT ID AS k, Nom AS v FROM t_clients ORDER BY Nom");
	$col["editoptions"] = array(
	            "value"=>$str,
	            "required"=>true /*,
	            "onchange" => array(    "sql"=>"SELECT DISTINCT NoVente AS k, NoVente AS v FROM t_detail WHERE IDClient = '{ID}'",
	                                    "update_field" => "NoVente" ) */ );
	$col["formatter"] = "select"; // display label, not value
	$col["stype"] = "select"; // enable dropdown search
	$col["searchoptions"] = array("value" => ":;".$str);
  $col["align"] = "center";
	$cols[] = $col;

//test nom ne marche pas
/*
  $col = array();
	$col["title"] = "nom";
	$col["name"] = "nom";
  $col["dbname"] = "SELECT Nom FROM t_clients WHERE t_details.IDClient = t_clients.ID";
  $col["hidden"] = true;
	$col["width"] = "100";
	$col["export"] = true;
	$col["search"] = false;
	$col["editable"] = false;
	$cols[] = $col;
*/

// Poids achat

	$col = array();
	$col["title"] = "Poids achat";
	$col["name"] = "PoidsAchat";
	$col["width"] = "50";
	$col["align"] = "left";
	$col["search"] = true;
	$col["editable"] = true;
	$col["editrules"] = array("required"=>true);
  $col["align"] = "center";
	$cols[] = $col;

  //Prix Kg

	$col = array();
	$col["title"] = "PrixKgTTC";
	$col["name"] = "PrixKgTTC";
	$col["width"] = "50";
	$col["search"] = true;
	$col["editable"] = true;
	$col["editrules"] = array("required"=>true);
  $col["align"] = "center";
	$cols[] = $col;

  //calcule prix total
  $col = array();
  $col["title"] = "Total à payer";
  $col["name"] = "newcol";
  $col["width"] = "50";
  $col["search"] = false;
  $col["editable"] = false;
  $col["align"] = "center";
  $cols[] = $col;


//Mode paiement

	$col = array();
	$col["title"] = "Mode Paiement";
	$col["name"] = "ModePaiement";
	$col["width"] = "50";
	$col["search"] = true;
	$col["editable"] = true;
  $col["align"] = "center";
	$cols[] = $col;


	$d_grid->set_columns($cols);

	$e["on_insert"] = array("add_detail", null, true);
	$e["on_update"] = array("update_detail", null, true);
	$e["on_render_pdf"] = array("set_pdf_format", null);
  $e["on_data_display"] = array("filter_display", null, true);

  $d_grid->set_events($e);

function filter_display($data)
{
    foreach($data["params"] as &$d)
    {
        $d["newcol"] = $d["PoidsAchat"] * $d["PrixKgTTC"];

    }
}

	function add_detail(&$data)
	{
		$id = intval($_GET["rowid"]);
		$data["params"]["NoVente"] = $id;
		//$data["params"]["total"] = $data["params"]["amount"] + $data["params"]["tax"];
	}

	function update_detail(&$data)
	{
		$id = intval($_GET["rowid"]);
		$data["params"]["NoVente"] = $id;
		//$data["params"]["total"] = $data["params"]["amount"] + $data["params"]["tax"];
	}

	function set_pdf_format($arr)
	{
    $pdf = $arr["pdf"];
    $data = $arr["data"];/*
    foreach ($data as $v1) {
    foreach ($v1 as $v2) {
        echo "$v2\n";
    }
	}
	echo count($data, COUNT_RECURSIVE);*/

    //$pdf->SetFont('dejavusans', '', 10);
    $pdf->SetLineWidth(0.1);
	}

	// generate grid output, with unique grid name as 'list1'
	$out_detail = $d_grid->render("detail");
}//fin else du départ
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
	<script src="//cdn.jsdelivr.net/jquery.hotkeys/0.8b/jquery.hotkeys.js"></script>
	<meta charset="utf-8">
    <title>Vente directe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="mb">

    <!-- Le styles -->
    <!--<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet"> -->
    <style type="text/css">
		body
		{
			padding-top: 10px;
			padding-bottom: 0px;
		}
		.sidebar-nav {
			padding: 9px 0;
		}
		.nav
		{
			margin-bottom:10px;
		}
		.accordion-inner a {
			font-size: 13px;
			font-family:tahoma;
		}

    </style>
</head>
<body>
	<div style="text-align : center">
		<h1>Vente directe</h1>
	</div>
	<!--<div style= "margin: 25px 50px 75px 150px"; >-->
	<div>

		<?php echo $out_master; ?>
		<br>
		<br>
		<?php echo $out_detail; ?>
	</div>
	<div class= 'container-fluid '>
			<?php echo '<br /><h3><a href="logout.php">		Se d&eacute;connecter</a><br>'; ?>
			<?php echo '<a href="clients.php">	 Afficher le listing des clients</a></h3><br>'; ?>
	</div>

	<script>
	// insert key to add new row, tab to focus on save icon & press enter to save
	$(document).bind('keyup', 'insert', function(){
		  jQuery('#detail_iladd').click();
		});

	</script>
	<script>
	var opts = {

    'loadComplete': function () {
        var grid = $("#table_total"),
        sum = grid.jqGrid('getCol', 'Montant', false, 'sum');
        grid.jqGrid('footerData','set', {Montant: 'Total: '+sum});
    }
};
</script>
</body>
</html>
