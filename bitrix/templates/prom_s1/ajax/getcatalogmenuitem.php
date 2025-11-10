<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
?>

<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php


use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;



if (!Loader::includeModule('intec.core')) {
    echo Loc::getMessage('template.errors.module', ['#MODULE#' => 'intec.core']);
    die();
}

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

$arParameters = [
        'ROOT' => 'top',
        'CHILD' => 'left',
        'LEVEL' => 3,
        'IBLOCK_TYPE' => '1s_catalog_okgbi',
        'IBLOCK_ID' => 138,
        'PROPERTY_IMAGE' => '',
        'SHOW' => 'Y',
        'SHOW_FIXED' => 'Y',
        'SHOW_MOBILE' => 'Y',
        'DELIMITERS' => 'Y',
        'SECTION_VIEW' => 'images',
        'SUBMENU_VIEW' => 'simple.1',
        'SECTION_COLUMNS_COUNT' => 4,
        'SECTION_ITEMS_COUNT' => 100,
        'CATALOG_LINKS' => ['/catalog/'],
        'POSITION' => 'top',
        'TRANSPARENT' => 'Y',
        'LAZYLOAD_USE' => 'Y',
        'UPPERCASE' => 'N',
        'OVERLAY_USE' => 'Y',
        'ROOT_MENU_TYPE' => 'top',
        'CHILD_MENU_TYPE' => 'left',
        'MAX_LEVEL' => 3,
        'USE_EXT' => 'Y',
        'DELAY' => 'N',
        'ALLOW_MULTI_SELECT' => 'N',
        'MENU_CACHE_TYPE' => 'Y',
        'MENU_CACHE_TIME' => 86400
];
?>

<?php $APPLICATION->IncludeComponent(
    'bitrix:menu',
    'horizontal.1-catalogitem.ajax',
    $arParameters,
); ?>

<?php unset($arMenu) ?>