<?php
error_reporting(E_ALL & ~E_NOTICE);
define(ROOT_PATH,dirname(dirname(__FILE__)).'/');
define(SYS_PATH,'../system');
define(CUR_PATH,ROOT_PATH.'app-web');




require_once(SYS_PATH.'/functions.php');

$INCLUDE_PATH = array(
	CUR_PATH,
	ROOT_PATH.'app-core'
);
$CONFIG_PATH = array(
	CUR_PATH.'/config',
	ROOT_PATH.'app-core/config',
	ROOT_PATH.'config',
);


rsf_require_class('RSF');
rsf_require_class('WebRequest');
rsf_require_class('WebResponse');
RSF::get_instance()->setRequest(new WebRequest());
RSF::get_instance()->setResponse(new WebResponse());

RSF::get_instance()->run();
