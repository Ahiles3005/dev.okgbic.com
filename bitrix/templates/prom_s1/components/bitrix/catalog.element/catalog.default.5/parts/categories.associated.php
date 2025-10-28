<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arResult
 * @var array $arParams
 * @var array $arVisual
 * @var CMain $APPLICATION
 * @var CBitrixComponent $component
 */


$GLOBALS['arCatalogSectionFilterAssociated'] = [
    'ID' => $arParams['UF_ASSOC_CATS']
];

$sTemplate = 'categories.small.1';

$arProperties = [
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_CATEGORIES_ASSOCIATED_NAME_DEFAULT'),
    'UF_ASSOC_CATS' => $arParams['UF_ASSOC_CATS'],
    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    'FILTER_NAME' => 'arCatalogSectionFilterAssociated',
    'LAZYLOAD_USE' => $arParams['LAZYLOAD_USE'],
    'COLUMNS' => 4,
    'PICTURE_SHOW' => 'Y',
];

//pre($arProperties);
//pre($arParams);
//pre($arResult);
//pre($arVisual);

?>
<div class="catalog-element-categories-associated-container catalog-element-additional-block">
    <div class="catalog-element-additional-block-name">
        <?= $arProperties['NAME'] ?>
    </div>
    <div class="catalog-element-additional-block-content">
        <?php $APPLICATION->IncludeComponent(
            'bitrix:catalog.section',
            $sTemplate,
            $arProperties,
            $component
        ) ?>
    </div>
</div>
<?php unset($sTemplate, $arProperties) ?>