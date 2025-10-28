<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Json;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */


$this->setFrameMode(true);

Loc::loadMessages(__FILE__);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

/**
 * @var array $arData
 */
include(__DIR__.'/parts/data.php');

$arVisual = $arResult['VISUAL'];
$arFields = $arResult['FIELDS'];
$arSvg = [
    'NAVIGATION' => [
        'LEFT' => FileHelper::getFileData(__DIR__.'/svg/gallery.preview.navigation.left.svg'),
        'RIGHT' => FileHelper::getFileData(__DIR__.'/svg/gallery.preview.navigation.right.svg')
    ],
    'SIZES' => FileHelper::getFileData(__DIR__.'/svg/sizes.svg'),
    'DELIVERY_CALCULATION' => FileHelper::getFileData(__DIR__.'/svg/delivery.svg'),
    'BUTTONS' => [
        'COMPARE' => FileHelper::getFileData(__DIR__.'/svg/button.action.compare.svg'),
        'DELAY' => FileHelper::getFileData(__DIR__.'/svg/button.action.delay.svg'),
        'BASKET' => FileHelper::getFileData(__DIR__.'/svg/button.action.basket.svg'),
    ],
    'PRICE' => [
        'DIFFERENCE' => FileHelper::getFileData(__DIR__.'/svg/purchase.price.difference.svg'),
        'CHEAPER' => FileHelper::getFileData(__DIR__.'/svg/purchase.cheaper.svg')
    ],
    'STORE' => [
        'LIST' => FileHelper::getFileData(__DIR__.'/svg/store.section.list.svg'),
        'MAP' => FileHelper::getFileData(__DIR__.'/svg/store.section.map.svg')
    ]
];

$bOffers = !empty($arResult['OFFERS']);
$bSkuDynamic = $bOffers && $arResult['SKU']['VIEW'] === 'dynamic';
$bSkuList = $bOffers && $arResult['SKU']['VIEW'] === 'list';

$bAdditionalColumn = ($arFields['BRAND']['SHOW'] && $arVisual['BRAND']['ADDITIONAL']['SHOW'] && $arVisual['BRAND']['ADDITIONAL']['POSITION'] === 'column') ||
    ($arFields['DOCUMENTS']['SHOW'] && $arVisual['DOCUMENTS']['POSITION'] === 'column') ||
    ($arFields['RECOMMENDED']['SHOW'] && $arVisual['RECOMMENDED']['POSITION'] === 'column') ||
    ($arFields['SIMILAR']['SHOW'] && $arVisual['SIMILAR']['POSITION'] === 'column') ||
    ($arFields['ASSOCIATED']['SHOW'] && $arVisual['ASSOCIATED']['POSITION'] === 'column');

if (!$bAdditionalColumn) {
    $arVisual['INFORMATION']['BUY']['POSITION'] = 'wide';
    $arVisual['INFORMATION']['PAYMENT']['POSITION'] = 'wide';
    $arVisual['INFORMATION']['SHIPMENT']['POSITION'] = 'wide';
}

$bInformation = ($arVisual['INFORMATION']['BUY']['SHOW'] && $arVisual['INFORMATION']['BUY']['POSITION'] === 'wide') ||
    ($arVisual['INFORMATION']['PAYMENT']['SHOW'] && $arVisual['INFORMATION']['PAYMENT']['POSITION'] === 'wide') ||
    ($arVisual['INFORMATION']['SHIPMENT']['SHOW'] && $arVisual['INFORMATION']['SHIPMENT']['POSITION'] === 'wide');

$bRecalculation = false;

if ($bBase && $arVisual['PRICE']['RECALCULATION'])
    $bRecalculation = true;
?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-element',
        'c-catalog-element-catalog-default-5'
    ],
    'data' => [
        'data' => Json::encode($arData, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true),
        'properties' => !empty($arResult['SKU_PROPS']) ? Json::encode($arResult['SKU_PROPS'], JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true) : '',
        'available' => $arData['available'] ? 'true' : 'false',
        'main-view' => $arVisual['MAIN_VIEW']
    ]
]) ?>
    <div class="catalog-element-delimiter"></div>
    <?= Html::beginTag('div', [
        'class' => 'catalog-element-body',
        'itemscope' => '',
        'itemtype' => 'https://schema.org/Product',
        'data' => [
            'role' => 'dynamic',
            'recalculation' => $bRecalculation ? 'true' : 'false'
        ]
    ])?>
<meta itemprop="name" content="<?echo (
		isset($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
: $arResult['NAME']
); ?>">
<meta itemprop="sku" content="<?= $arFields['ARTICLE']['VALUE'] ?>">
<meta itemprop="mpn" content="<?= $arFields['ARTICLE']['VALUE'] ?>">

<div style="display: none">
    <?php
//echo '<pre>'.print_r($arResult, 1).'</pre>';
    if(empty($arVisual['PROPERTIES']['DETAIL']['SHOW'])){
        echo '<meta itemprop="description" content="Купить '.$arResult['NAME'].' в компании Очаково ЖБИ">';
    }
?>
    <div itemprop="brand" itemscope itemtype="https://schema.org/Brand">
    <?php
    $str = $arResult['PROPERTIES']['BREND']['VALUE'];

    if (false !== strpos($str, 'c0c99ea5-5fdd-11eb-94e1-00155da60401'))
        echo '<meta itemprop="name" content="NOTABETON">';


    $str2 = $arResult['PROPERTIES']['BREND']['VALUE'];

    if (false !== strpos($str2, 'da0f89ce-5fdd-11eb-94e1-00155da60401'))
        echo '<meta itemprop="name" content="OKGBI">';


    $str3 = $arResult['PROPERTIES']['BREND']['VALUE'];

    if (false !== strpos($str3, 'dc915fffa-5fdd-11eb-94e1-00155da60401') )
        echo '<meta itemprop="name" content="RAINBASE">';


    $str4 = $arResult['PROPERTIES']['BREND']['VALUE'];

    if (false !== strpos($str4, 'd0f18082-5fdd-11eb-94e1-00155da60401') ) {
        echo '<meta itemprop="name" content="RAINPLUS">';
    }


    $str5 = $arResult['PROPERTIES']['BREND']['VALUE'];

    if (false !== strpos($str5, 'd0f18085-5fdd-11eb-94e1-00155da60401') ) {
        echo '<meta itemprop="name" content="RAINPRO">';
    }

?>
    </div>
    <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
        <meta itemprop="price" content="<?= (empty($arResult['ITEM_PRICES'][0]['PRICE'])? 0: $arResult['ITEM_PRICES'][0]['PRICE']); ?>" />
        <meta itemprop="priceCurrency" content="RUB" />
        <link itemprop="availability" href="http://schema.org/InStock">
        <meta itemprop="priceValidUntil" content="2029-12-31">
        <link itemprop="url" href="<? echo $current_link  = $APPLICATION->GetCurPage(false); ?>">
    </span>
    <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <meta itemprop="worstRating" content="1"/>
        <meta itemprop="bestRating" content="5"/>
        <meta itemprop="ratingValue" content="<?= $arResult['PROPERTIES']['rating']['VALUE']; ?>"/>
        <meta itemprop="ratingCount" content="<?= $arResult['PROPERTIES']['vote_count']['VALUE']; ?>"/>
    </div>
</div>
<?php if ($arVisual['PANEL']['DESKTOP']['SHOW'] && (!$bOffers || $bSkuDynamic))
            include(__DIR__.'/parts/panel.php');
        ?>
        <?php if ($arVisual['PANEL']['MOBILE']['SHOW'] && (!$bOffers || $bSkuDynamic)) { ?>
            <!--noindex-->
            <?php include(__DIR__.'/parts/panel.mobile.php'); ?>
            <!--/noindex-->
        <?php } ?>
        <div class="intec-content intec-content-visible">
            <div class="intec-content-wrapper">
                <div class="catalog-element-main-container">
                    <?php
                        include(__DIR__ . '/parts/main.container.view.'.$arVisual['MAIN_VIEW'].'.php');
                    ?>
                </div>
                <?php if (!$bSkuList)
                    include(__DIR__.'/parts/sets.php');
                ?>
            </div>
        </div>
        <div class="intec-content intec-content-visible">
            <div class="intec-content-wrapper">
                <div class="catalog-element-additional-container">
                    <div class="intec-grid intec-grid-wrap intec-grid-i-h-15 intec-grid-1024-wrap">
                        <?= Html::beginTag('div', [
                            'class' => Html::cssClassFromArray([
                                'catalog-element-additional-base-container' => !$bAdditionalColumn,
                                'catalog-element-additional-left-container' => $bAdditionalColumn,
                                'intec-grid-item' => [
                                    '' => !$bAdditionalColumn,
                                    'auto' => $bAdditionalColumn,
                                    '1024-1' => true
                                ]
                            ], true)
                        ]) ?>
                            <?php if ($bSkuList)
                                include(__DIR__.'/parts/offers.list.php');

                            if ($arVisual['SECTIONS']) {
                                include(__DIR__.'/parts/sections.php');
                                if ($arVisual['INFORMATION']['BUY']['SHOW'] && $arVisual['INFORMATION']['BUY']['POSITION'] === 'column')
                                    include(__DIR__.'/parts/information.buy.php');
                            } else {
                                if ($arVisual['DESCRIPTION']['DETAIL']['SHOW'])
                                    include(__DIR__.'/parts/description.detail.php');

                                if ($arVisual['PROPERTIES']['DETAIL']['SHOW'])
                                    include(__DIR__.'/parts/properties.detail.php');

                                if (
                                    $arVisual['STORES']['USE'] && $arVisual['STORES']['POSITION'] === 'content' &&
                                    $arResult['SKU']['VIEW'] === 'dynamic'
                                )
                                    include(__DIR__.'/parts/stores.php');

                                if ($arFields['DOCUMENTS']['SHOW'] && $arVisual['DOCUMENTS']['POSITION'] === 'content')
                                    include(__DIR__.'/parts/documents.php');

                                if ($arFields['VIDEO']['SHOW'])
                                    include(__DIR__.'/parts/videos.php');

                                if ($arFields['ARTICLES']['SHOW'])
                                    include(__DIR__.'/parts/articles.php');

                                if ($arResult['REVIEWS']['SHOW'])
                                    include(__DIR__.'/parts/reviews.php');
                            }

                            if (
                                $arFields['BRAND']['SHOW'] && $arVisual['BRAND']['ADDITIONAL']['SHOW'] &&
                                $arVisual['BRAND']['ADDITIONAL']['POSITION'] === 'content'
                            )
                                include(__DIR__.'/parts/brand.additional.php');

                            if ($arFields['RECOMMENDED']['SHOW'] && $arVisual['RECOMMENDED']['POSITION'] === 'content')
                                include(__DIR__.'/parts/products.recommended.php');
							
                            if ($arFields['SIMILAR']['SHOW'] && $arVisual['SIMILAR']['POSITION'] === 'content')
                                include(__DIR__.'/parts/products.similar.php');

                            if ($arFields['ASSOCIATED']['SHOW'] && $arVisual['ASSOCIATED']['POSITION'] === 'content')
                                include(__DIR__.'/parts/products.associated.php');

                            if ($arVisual['CATEGORIES_ASSOCIATED']['SHOW'] && !empty($arParams['UF_ASSOC_CATS']))
                                include(__DIR__.'/parts/categories.associated.php');

                            if ($arFields['SERVICES']['SHOW'])
                                include(__DIR__.'/parts/services.php');
                            ?>

                            <?php if (!$arVisual['SECTIONS'] && $bAdditionalColumn) { ?>
                                <?php if ($arVisual['INFORMATION']['BUY']['SHOW'] && $arVisual['INFORMATION']['BUY']['POSITION'] === 'column')
                                    include(__DIR__.'/parts/information.buy.php');

                                if ($arVisual['INFORMATION']['PAYMENT']['SHOW'] && $arVisual['INFORMATION']['PAYMENT']['POSITION'] === 'column')
                                    include(__DIR__.'/parts/information.payment.php');

                                if ($arVisual['INFORMATION']['SHIPMENT']['SHOW'] && $arVisual['INFORMATION']['SHIPMENT']['POSITION'] === 'column')
                                    include(__DIR__.'/parts/information.shipment.php');
                                ?>
                            <?php } ?>

                        <?= Html::endTag('div') ?>
                        <?php if ($bAdditionalColumn) { ?>
                            <div class="catalog-element-additional-right-container intec-grid-item-3 intec-grid-item-1024-1">
                                <?php if (
                                    $arFields['BRAND']['SHOW'] && $arVisual['BRAND']['ADDITIONAL']['SHOW'] &&
                                    $arVisual['BRAND']['ADDITIONAL']['POSITION'] === 'column'
                                )
                                    include(__DIR__.'/parts/brand.additional.php');

                                if ($arFields['DOCUMENTS']['SHOW'] && $arVisual['DOCUMENTS']['POSITION'] === 'column')
                                    include(__DIR__.'/parts/documents.small.php');

                                if ($arFields['RECOMMENDED']['SHOW'] && $arVisual['RECOMMENDED']['POSITION'] === 'column')
                                    include(__DIR__.'/parts/products.recommended.small.php');

                                if ($arFields['SIMILAR']['SHOW'] && $arVisual['SIMILAR']['POSITION'] === 'column')
                                    include(__DIR__.'/parts/products.similar.small.php');

                                if ($arFields['ASSOCIATED']['SHOW'] && $arVisual['ASSOCIATED']['POSITION'] === 'column')
                                    include(__DIR__.'/parts/products.associated.small.php');
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php if (!$arVisual['SECTIONS'] && $bInformation) { ?>
                    <div class="catalog-element-additional-container">
                        <?php if ($arVisual['INFORMATION']['BUY']['SHOW'] && $arVisual['INFORMATION']['BUY']['POSITION'] === 'wide')
                            include(__DIR__.'/parts/information.buy.php');

                        if ($arVisual['INFORMATION']['PAYMENT']['SHOW'] && $arVisual['INFORMATION']['PAYMENT']['POSITION'] === 'wide')
                            include(__DIR__.'/parts/information.payment.php');

                        if ($arVisual['INFORMATION']['SHIPMENT']['SHOW'] && $arVisual['INFORMATION']['SHIPMENT']['POSITION'] === 'wide')
                            include(__DIR__.'/parts/information.shipment.php');
                        ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?= Html::endTag('div') ?>
    <?php include(__DIR__.'/parts/script.php') ?>
<?= Html::endTag('div') ?>