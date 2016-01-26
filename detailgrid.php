<?php
/**
 * PHP Grid Component
 *
 * @author Abu Ghufran <gridphp@gmail.com> - http://www.phpgrid.org
 * @version 1.5.2
 * @license: see license.txt included in package
 */

// include db config

    include_once("config.php");
    // set up DB
    mysql_connect(PHPGRID_DBHOST, PHPGRID_DBUSER, PHPGRID_DBPASS);
    mysql_select_db(PHPGRID_DBNAME);
    // include and create object
    include(PHPGRID_LIBPATH."inc/jqgrid_dist.php");

$detail_grid = new jqgrid();




$grid["sortname"] = 'NoVente'; // by default sort grid by this field
$grid["sortorder"] = "asc"; // ASC or DESC
$grid["autowidth"] = true; // expand grid to screen width
$grid["multiselect"] = false; // allow you to multi-select through checkboxes
$grid["caption"] = "Détails";
$grid["export"] = array("format"=>"pdf", "filename"=>"my-file", "heading"=>"Clients vente directe ", "orientation"=>"landscape", "paper"=>"a4");
//$opt["export"]["range"] = "filtered";
$grid["height"] = "500";
$detail_grid->set_options($grid);

$detail_grid->set_actions(array(
                        "add"=>true, // allow/disallow add
                        "edit"=>true, // allow/disallow edit
                        "delete"=>true, // allow/disallow delete
                        "rowactions"=>true, // show/hide row wise edit/del/save option
                        "autofilter" => false, // show/hide autofilter for search
                        "export" => true
                    )
                );

// ID

$col = array();
$col["title"] = " ID"; // caption of column
$col["name"] = "ID"; // field name, must be exactly same as with SQL prefix or db field
$col["hidden"] = true;
$col["width"] = "100";
$col["export"] = false; // this column will not be exported
$col["editrules"] = array("required"=>true);
$cols[] = $col;

// No Vente

$col = array();
$col["title"] = " N° Vente"; // caption of column
$col["name"] = "NoVente"; // field name, must be exactly same as with SQL prefix or db field
$col["hidden"] = true;
$col["width"] = "100";
$col["export"] = false; // this column will not be exported
$col["editable"] = true;
$col["editrules"] = array("required"=>true);
$cols[] = $col;

// Client

$col = array();
$col["title"] = "Client";
$col["name"] = "IDClient";
$col["dbname"] = "t_clients.Nom"; // this is required as we need to search in name field, not id
$col["width"] = "100";
$col["align"] = "left";
$col["search"] = true;
$col["editable"] = true;
$col["edittype"] = "select"; // render as select
$col["export"] = true;
# fetch data from database, with alias k for key, v for value

# on change, update other dropdown
$str = $detail_grid->get_dropdown_values("SELECT DISTINCT ID AS k, Nom AS v FROM t_clients ORDER BY Nom");
$col["editoptions"] = array(
            "value"=>$str  /*,
            "onchange" => array(    "sql"=>"SELECT DISTINCT PoidsAchat AS k, PoidsAchat AS v FROM t_detail WHERE IDClient = '{ID}'",
                                    "update_field" => "PoidsAchat" ) */ );
$col["formatter"] = "select"; // display label, not value
$col["stype"] = "select"; // enable dropdown search
$col["searchoptions"] = array("value" => ":;".$str);

$cols[] = $col;

//Poids achat

$col = array();
$col["title"] = "Poids achat";
$col["name"] = "PoidsAchat";
$col["width"] = "100";
$col["search"] = true;
$col["editable"] = true;
$col["edittype"] = "select"; // render as select
$str = $detail_grid->get_dropdown_values("SELECT PoidsAchat as k, PoidsAchat as v from t_detail");
$col["editoptions"] = array("value"=>$str);

// initially load 'note' of that client_id
$col["editoptions"]["onload"]["sql"] = "SELECT PoidsAchat as k, PoidsAchat as v from t_detail WHERE IDClient = '{ID}'";

$col["formatter"] = "select"; // display label, not value
$col["searchoptions"] = array("value" => ":;".$str);

$cols[] = $col;

//Prix au KG

$col = array();
$col["title"] = "Prix au Kg TTC";
$col["name"] = "PrixKgTTC";
$col["width"] = "50";
$col["editable"] = true; // this column is editable
$col["editoptions"] = array("size"=>20); // with default display of textbox with size 20
$col["editrules"] = array("required"=>true); // and is required
//$col["formatter"] = "date"; // format as date
$col["search"] = false;
$cols[] = $col;

//Mode de payement

$col = array();
$col["title"] = "Mode de paiement";
$col["name"] = "ModePaiement";
$col["width"] = "50";
$col["editable"] = true; // this column is editable
$col["editoptions"] = array("size"=>20); // with default display of textbox with size 20
$col["editrules"] = array("required"=>true); // and is required
$cols[] = $col;

$detail_grid->select_command = "SELECT NoVente, PoidsAchat, t_detail.IDClient, PrixKgTTC, ModePaiement FROM t_detail
                        INNER JOIN t_clients on t_clients.ID = t_detail.IDClient ";

// this db table will be used for add,edit,delete
$detail_grid->table = "t_detail";

// pass the cooked columns to grid
$detail_grid->set_columns($cols);

// generate grid output, with unique grid name as 'list1'
$out = $detail_grid->render("detail");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <link rel="stylesheet" type="text/css" media="screen" href="lib/js/themes/redmond/jquery-ui.custom.css"></link>
    <link rel="stylesheet" type="text/css" media="screen" href="lib/js/jqgrid/css/ui.jqgrid.css"></link>

    <script src="lib/js/jquery.min.js" type="text/javascript"></script>
    <script src="lib/js/jqgrid/js/i18n/grid.locale-en.js" type="text/javascript"></script>
    <script src="lib/js/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
    <script src="lib/js/themes/jquery-ui.custom.min.js" type="text/javascript"></script>
</head>
<body>
    <div style="margin:10px">
    <?php echo $out?>
    </div>
</body>
</html>
