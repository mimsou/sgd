<?php

define("WEBROOT", str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]));
define("SCRIPTROOT",str_replace("ad/index.php", "","http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']));
define("SCRIPTADROOT",str_replace("index.php", "","http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']));
define("ADROOT", str_replace("index.php", "", $_SERVER["SCRIPT_FILENAME"]));
define("ROOT" ,substr(ADROOT, 0, -3));
define("ROOTCOM" ,ROOT."/com");
define("ADROOTCOM" ,ADROOT."/com");
define("MODPATHTAB", "../model/Dbtable");
define("SEP", "/");
define("TPL", "tpl");
$path_library = ROOT.'core/library';
set_include_path(get_include_path() .PATH_SEPARATOR. $path_library);

?>
