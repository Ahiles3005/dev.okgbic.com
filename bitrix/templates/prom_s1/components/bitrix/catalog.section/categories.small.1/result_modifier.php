<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('intec.core'))
    return;

$arVisual = [
    'LAZYLOAD' => [
        'USE' => !defined('EDITOR') ? $arParams['LAZYLOAD_USE'] === 'Y' : false,
        'STUB' => !defined('EDITOR') ? Properties::get('template-images-lazyload-stub') : null
    ],
    'COLUMNS' => ArrayHelper::fromRange([3, 4], $arParams['COLUMNS']),
    'PICTURE' => [
        'SHOW' => $arParams['PICTURE_SHOW'] === 'Y'
    ],
];
$arResult['VISUAL'] = $arVisual;

$arSections = Arrays::fromDBResult(CIBlockSection::GetList(
    [],
    ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $arParams['UF_ASSOC_CATS'], 'GLOBAL_ACTIVE'=>'Y'],
    false,
    ['ID', 'GLOBAL_ACTIVE', 'SORT', 'NAME', 'PICTURE', 'CODE', 'SECTION_PAGE_URL'],
    ['nTopCount' => $arVisual['COLUMNS']]
))->indexBy('ID');

foreach($arParams['UF_ASSOC_CATS'] as $arCat) {
    $arSection = $arSections->get($arCat);
    if(empty($arSection)){
        continue;
    }
    $arParams['UF_ASSOC_CATS_ITEMS'][] = $arSection;
}

include(__DIR__.'/modifiers/pictures.php');

//pre('s1-mod');
//pre($arResult);
//pre($arParams);

unset($arVisual, $arSection, $arSections);