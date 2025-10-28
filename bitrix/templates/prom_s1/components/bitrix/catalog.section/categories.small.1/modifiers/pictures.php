<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arVisual
 */

$arFiles = [];

$hCollect = function (&$arItem) use (&$arFiles) {
    if (!empty($arItem['PICTURE']) && !Type::isArray($arItem['PICTURE']))
        $arFiles[] = $arItem['PICTURE'];
};

foreach ($arParams['UF_ASSOC_CATS_ITEMS'] as &$arItem) {
    $hCollect($arItem);
}
unset($arItem);

if (!empty($arFiles)) {
    $arFiles = Arrays::fromDBResult(CFile::GetList([], [
        '@ID' => implode(',', $arFiles)
    ]))->each(function ($iIndex, &$arFile) {
        $arFile['SRC'] = CFile::GetFileSRC($arFile);
    })->indexBy('ID');
} else {
    $arFiles = Arrays::from([]);
}

$hSet = function (&$arItem) use (&$arFiles, &$arVisual) {
    $arItem['IMG'] = [
        'SHOW' => false,
        'VALUE' => []
    ];

    if (!empty($arItem['PICTURE']) && !Type::isArray($arItem['PICTURE']))
        $arItem['PICTURE'] = $arFiles->get($arItem['PICTURE']);

    if (!empty($arItem['PICTURE'])) {
        $arItem['IMG']['VALUE'] = $arItem['PICTURE'];
        $arItem['IMG']['SHOW'] = $arVisual['PICTURE']['SHOW'];
    }
};

foreach ($arParams['UF_ASSOC_CATS_ITEMS'] as &$arItem) {
    $hSet($arItem);
}

unset($arItem, $arFiles, $hCollect, $hSet);