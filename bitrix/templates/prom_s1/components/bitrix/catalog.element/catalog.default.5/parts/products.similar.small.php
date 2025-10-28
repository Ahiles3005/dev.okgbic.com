<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 * @var CMain $APPLICATION
 * @var CBitrixComponent $component
 */

$GLOBALS['arCatalogElementFilterSimilar'] = [
    'ID' => array_slice($arResult['FIELDS']['SIMILAR']['VALUES'], 0, 
        empty($arVisual['SIMILAR']['START_COUNT']) ? null : $arVisual['SIMILAR']['START_COUNT']
    )
];

$sPrefix = 'PRODUCTS_SIMILAR_';
$sTemplate = 'products.small.3';

$iLength = StringHelper::length($sPrefix);

$arProperties = [];

foreach ($arParams as $sKey => $sValue) {
    if (!StringHelper::startsWith($sKey, $sPrefix))
        continue;

    $sKey = StringHelper::cut($sKey, $iLength);

    $arProperties[$sKey] = $sValue;
}

unset($sPrefix, $iLength, $sKey, $sValue);

$arProperties = ArrayHelper::merge($arProperties, [
    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    'SECTION_USER_FIELDS' => [],
    'SHOW_ALL_WO_SECTION' => 'Y',
    'FILTER_NAME' => 'arCatalogElementFilterSimilar',
    'PRICE_CODE' => $arParams['PRICE_CODE'],
    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
    'LAZYLOAD_USE' => $arParams['LAZYLOAD_USE']
]);

if (empty($arVisual['SIMILAR']['NAME']))
    $arVisual['SIMILAR']['NAME'] = Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_PRODUCTS_SIMILAR_NAME_DEFAULT');

?>
<div class="catalog-element-products-recommended-container catalog-element-additional-block">
    <div class="catalog-element-additional-block-name-small">
        <?= $arVisual['SIMILAR']['NAME'] ?>
    </div>
    <div class="catalog-element-additional-block-content">
    <?php 
    if(array_key_exists("ajax_action", $_REQUEST) && $_REQUEST["ajax_action"] == "PRODUCTS_SIMILAR_MORE"){
    	$APPLICATION->RestartBuffer();
        if(!empty($arVisual['SIMILAR']['START_COUNT']) && 
            $arVisual['SIMILAR']['START_COUNT'] < count($arResult['FIELDS']['SIMILAR']['VALUES'])
        ){
            $GLOBALS['arCatalogElementFilterSimilar'] = [
                'ID' => array_slice($arResult['FIELDS']['SIMILAR']['VALUES'], $arVisual['SIMILAR']['START_COUNT'])
            ];
        } else {
            $GLOBALS['arCatalogElementFilterSimilar'] = [
                'ID' => [0]
            ];
        }
    }
    ?>
        <?php $APPLICATION->IncludeComponent(
            'bitrix:catalog.section',
            $sTemplate,
            $arProperties,
            $component
        ) ?>
    <?php 
    if(array_key_exists("ajax_action", $_REQUEST) && $_REQUEST["ajax_action"] == "PRODUCTS_SIMILAR_MORE"){
    	die();
    }
    ?>
    </div>
    <?php if(!empty($arVisual['SIMILAR']['START_COUNT']) && 
            $arVisual['SIMILAR']['START_COUNT'] < count($arResult['FIELDS']['SIMILAR']['VALUES'])
    ){ ?>
    <div class="intec-ui intec-ui-control-button" data-action="PRODUCTS_SIMILAR_MORE" style="width:100%;color:#0b71b5"><?=
        Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_PRODUCTS_SIMILAR_BUTTON_MORE');
    ?></div>
<script>
(function ($, api) {
    $(function () {
      $('.catalog-element-products-recommended-container [data-action="PRODUCTS_SIMILAR_MORE"]').click(function(){
        BX.ajax({
            url: '.',
            data: {ajax_action: 'PRODUCTS_SIMILAR_MORE'},
            method: 'POST',
            dataType: 'html',
            async: true,
            processData: true,
            emulateOnload: true,
            start: true,
            cache: false,
            onsuccess: function(data){
                console.log(data);
                var elements = $(data).find('.c-catalog-section-products-small-3 .catalog-section-items').html();
                $('.c-catalog-section-products-small-3 .catalog-section-items').append(elements);
                $('.catalog-element-products-recommended-container [data-action="PRODUCTS_SIMILAR_MORE"]').remove();
            }
        });
      });
    });
})(jQuery, intec);
</script>
    <?php } ?>
</div>
<?php unset($sTemplate, $arProperties) ?>