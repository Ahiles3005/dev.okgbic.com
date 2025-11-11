<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
?>

<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>

<?php
$params = unserialize(base64_decode($_POST['params']));
?>
<?php $APPLICATION->IncludeComponent(
    'bitrix:menu',
    'mobile.2-getmenu.ajax',
    $params,
);
?>

<?php unset($arMenu) ?>