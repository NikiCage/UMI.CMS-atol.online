<?php


$INFO['name'] = "fx_atol";
$INFO['filename'] = "components/fx_atol/class.php";
$INFO['config'] = "1";
$INFO['ico'] = "ico_fx_atol";
$INFO['default_method'] = "config";
$INFO['default_method_admin'] = "config";
$INFO['sno'] = "osn";

$INFO['func_perms'] = "Functions, that should have their own permissions.";
$INFO['func_perms/admin'] = "Администрирование модулем";
$INFO['func_perms/order'] = "Получать чек при оформлении";

$COMPONENTS = array();
$COMPONENTS[0] = "./classes/components/fx_atol/__admin.php";
$COMPONENTS[1] = "./classes/components/fx_atol/class.php";
$COMPONENTS[2] = "./classes/components/fx_atol/events.php";
$COMPONENTS[3] = "./classes/components/fx_atol/i18n.en.php";
$COMPONENTS[4] = "./classes/components/fx_atol/i18n.php";
$COMPONENTS[5] = "./classes/components/fx_atol/lang.en.php";
$COMPONENTS[6] = "./classes/components/fx_atol/lang.php";
$COMPONENTS[7] = "./classes/components/fx_atol/permissions.php";
$COMPONENTS[8] = "./classes/modules/fx_atol/classes/foxiAtol.php";

?>