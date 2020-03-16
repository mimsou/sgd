<?php

$path_library = ROOT.'core/library';
set_include_path(get_include_path() .DS. $path_library);
require (BASE_PATH.DS.'core/library/Zend/Loader.php');
require (BASE_PATH.DS.'core/library/Zend/Loader/Autoloader.php');
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

?>