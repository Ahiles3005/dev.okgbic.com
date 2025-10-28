<?
//Подгрузка ядра1
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("BX_COMPRESSION_DISABLED", true);
set_time_limit(0);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
global $APPLICATION, $USER, $DB;
//$USER->Authorize(1);
LocalRedirect('/bitrix/');
?>
