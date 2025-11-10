<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/** @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arParams = ArrayHelper::merge([
        'LAZYLOAD_USE' => 'N',
        'UPPERCASE' => 'N',
        'TRANSPARENT' => 'N',
        'DELIMITERS' => 'N',
        'SECTION_VIEW' => 'default',
        'SECTION_COLUMNS_COUNT' => 3,
        'SECTION_ITEMS_COUNT' => 3,
        'OVERLAY_USE' => 'N'
], $arParams);

$arVisual = [
        'LAZYLOAD' => [
                'USE' => $arParams['LAZYLOAD_USE'] === 'Y'
        ],
        'UPPERCASE' => $arParams['UPPERCASE'] === 'Y',
        'TRANSPARENT' => $arParams['TRANSPARENT'] === 'Y',
        'DELIMITERS' => $arParams['DELIMITERS'] === 'Y',
        'SECTION' => [
                'VIEW' => ArrayHelper::fromRange([
                        'default',
                        'images'
                ], $arParams['SECTION_VIEW']),
                'COLUMNS' => ArrayHelper::fromRange([
                        2,
                        3,
                        4
                ], $arParams['SECTION_COLUMNS_COUNT']),
                'ITEMS' => $arParams['SECTION_ITEMS_COUNT']
        ],
        'OVERLAY' => [
                'USE' => $arParams['OVERLAY_USE'] === 'Y'
        ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['TRANSPARENT'])
    $arVisual['DELIMITERS'] = false;

?>
<?php $fDraw = function ($arItem, $iLevel, $bIsIBlock = false, $bIsSection = false) use (&$fDraw, &$arParams, &$arResult, &$arVisual) { ?>
    <?php

    $arItems = $arItem['ITEMS'];
    $id = md5($arItem['LINK']);

    if (!$bIsIBlock) {
        $bIsSection = false;
    }

    if ($bIsSection) {
        include('parts/section.php');
    }
    ?>
<?php } ?>

<?php if (!empty($arResult)): ?>
<div>
    <?php foreach ($arResult as $arItem) { ?>
        <?php


        $bIsIBlock = false;

        if (!empty($arItem['ITEMS'])) {
            $bIsIBlock = ArrayHelper::getFirstValue($arItem['ITEMS']);
            $bIsIBlock = ArrayHelper::getValue($bIsIBlock, ['PARAMS', 'FROM_IBLOCK']);
            $bIsIBlock = Type::toBoolean($bIsIBlock);
        }

        if ($bIsIBlock) {
            $fDraw($arItem, 1, $bIsIBlock, $bIsIBlock);
        }
        ?>


    <?php } ?>
</div>
<?php endif; ?>