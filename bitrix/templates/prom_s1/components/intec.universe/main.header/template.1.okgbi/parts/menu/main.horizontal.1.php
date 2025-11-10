<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

$arMenu = $arResult['MENU']['MAIN'];
$arMenuParams = !empty($arMenuParams) ? $arMenuParams : [];

$sPrefix = 'MENU_MAIN_';
$arParameters = [];

foreach ($arParams as $sKey => $sValue)
    if (StringHelper::startsWith($sKey, $sPrefix)) {
        $sKey = StringHelper::cut($sKey, StringHelper::length($sPrefix));
        $arParameters[$sKey] = $sValue;
    }

$arParameters['TRANSPARENT'] = $arResult['VISUAL']['TRANSPARENCY'] ? 'Y' : 'N';
$arParameters = ArrayHelper::merge($arParameters, $arMenuParams, [
    'ROOT_MENU_TYPE' => $arMenu['ROOT'],
    'CHILD_MENU_TYPE' => $arMenu['CHILD'],
    'MAX_LEVEL' => $arMenu['LEVEL'],
//    'MENU_CACHE_TYPE' => 'N',
    'USE_EXT' => 'Y',
    'DELAY' => 'N',
    'ALLOW_MULTI_SELECT' => 'N',
    'MENU_CACHE_TYPE' => 'Y',
    'MENU_CACHE_TIME' => 60*60*24,
]);
$isAjax = false;

if(isset($_GET['catalogitemajax']) && $_GET['catalogitemajax'] == 'Y') {
    $isAjax = true;
}
?>

<?php $APPLICATION->IncludeComponent(
    'bitrix:menu',
    'horizontal.1-new',
    $arParameters,
    $this->getComponent()
); ?>

<?php unset($arMenu) ?>