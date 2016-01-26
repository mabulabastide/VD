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

$g = new jqgrid(); 

$col = array();
$col["title"] = " ID"; // caption of column
$col["name"] = "ID"; // field name, must be exactly same as with SQL prefix or db field
$col["width"] = "10";
$col["export"] = false; // this column will not be exported 
$cols[] = $col; 

$col = array(); 
$col["title"] = "NoVente"; // caption of column 
$col["name"] = "NoVente";  
$col["width"] = "10"; 
$cols[] = $col;         
         
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
$str = $g->get_dropdown_values("select distinct ID as k, Nom as v from t_clients"); 
$col["editoptions"] = array("value"=>":;".$str);  
$col["formatter"] = "select"; // display label, not value 
$cols[] = $col; 

$col = array(); 
$col["title"] = "Poids Achat"; 
$col["name"] = "PoidsAchat";  
$col["width"] = "50"; 
$col["editable"] = true; // this column is editable 
$col["editoptions"] = array("size"=>20); // with default display of textbox with size 20 
$col["editrules"] = array("required"=>true); // and is required 
$col["search"] = false; 
$cols[] = $col; 

$col = array(); 
$col["title"] = "Prix"; 
$col["name"] = "PrixKgTTC";  
$col["width"] = "50"; 
$col["editable"] = true; // this column is editable 
$col["editoptions"] = array("size"=>20); // with default display of textbox with size 20 
$cols[] = $col; 

$col = array(); 
$col["title"] = "Mode Paiement"; 
$col["name"] = "ModePaiement";  
$col["width"] = "50"; 
$col["editable"] = true; // this column is editable 
$col["editoptions"] = array("size"=>20); // with default display of textbox with size 20 
$cols[] = $col; 

$grid["sortname"] = 'NoVente'; // by default sort grid by this field 
$grid["sortorder"] = "desc"; // ASC or DESC 
$grid["caption"] = "detail"; // caption of grid 
#$grid["autowidth"] = true; // expand grid to screen width 

$g->set_options($grid); 

$g->set_actions(array(     
                        "add"=>false, // allow/disallow add 
                        "edit"=>true, // allow/disallow edit 
                        "delete"=>true, // allow/disallow delete 
                        "rowactions"=>true, // show/hide row wise edit/del/save option 
                        "autofilter" => true, // show/hide autofilter for search 
                    )  
                ); 

$g->select_command = "SELECT t_detail.ID,NoVente,IDClient, PoidsAchat, PrixKgTTC,ModePaiement FROM t_detail
                        INNER JOIN t_clients on t_clients.ID = t_detail.IDClient 
                        "; 

// this db table will be used for add,edit,delete 
$g->table = "t_detail"; 

// pass the cooked columns to grid 
$g->set_columns($cols); 

// generate grid output, with unique grid name as 'list1' 
$out = $g->render("list1"); 
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
