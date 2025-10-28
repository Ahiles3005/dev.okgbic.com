<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;

$this->setFrameMode(true);

Loader::includeModule('bitlate.proshop');
if ($arParams["REQUEST_LOAD"] != "Y") {
	include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/top.php");
}

if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
{
	$basketAction = (isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? array($arParams['COMMON_ADD_TO_BASKET_ACTION']) : array());
}
else
{
	$basketAction = (isset($arParams['DETAIL_ADD_TO_BASKET_ACTION']) ? $arParams['DETAIL_ADD_TO_BASKET_ACTION'] : array());
}?>
<?if ($arParams["REQUEST_LOAD"] != "Y"):?>
	<section class="product" itemscope itemtype="http://schema.org/Product">
		<div class="inner-bg">
			<div class="advanced-container-medium">
				<nav>
					<?$APPLICATION->IncludeComponent("bitrix:breadcrumb","",Array(
							"START_FROM" => "0", 
							"PATH" => "", 
						)
					);?>
				</nav>
				<article class="inner-container product-container">
<?else:
	$APPLICATION->ShowAjaxHead();?>
	<div class="fancybox-block fancybox-block-preview" style="display: block;">
		<div class="advanced-container-medium" id="product_preview">
			<div class="inner-container product-container">
<?endif;?>
<?$elementId = $APPLICATION->IncludeComponent(
	"bitrix:catalog.element",
	BitlateProUtils::getComponentTemplate('ELEMENT'),
	array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
		"META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
		"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"CHECK_SECTION_ID_VARIABLE" => (isset($arParams["DETAIL_CHECK_SECTION_ID_VARIABLE"]) ? $arParams["DETAIL_CHECK_SECTION_ID_VARIABLE"] : ''),
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"SHOW_404" => $arParams["SHOW_404"],
		"FILE_404" => $arParams["FILE_404"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"PRICE_MULTY" => $arParams["PRICE_MULTY"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
		"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
		"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
		"LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
		"LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
		"LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
		"LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],

		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],

		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
		'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
		'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
		'USE_ELEMENT_COUNTER' => $arParams['USE_ELEMENT_COUNTER'],
		'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
		"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
		'STRICT_SECTION_CHECK' => (isset($arParams['DETAIL_STRICT_SECTION_CHECK']) ? $arParams['DETAIL_STRICT_SECTION_CHECK'] : ''),
		'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
		'ADD_VIDEO_PROP' => $arParams['ADD_VIDEO_PROP'],
		'LABEL_PROP' => $arParams['LABEL_PROP'],
		'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
		'OFFER_ADD_VIDEO_PROP' => $arParams['OFFER_ADD_VIDEO_PROP'],
		'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
		'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
		'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
		'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
		'SHOW_MAX_QUANTITY' => $arParams['DETAIL_SHOW_MAX_QUANTITY'],
		'MESS_BTN_BUY_1_CLICK' => $arParams['MESS_BTN_BUY_1_CLICK'],
		'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
		'MESS_BTN_BUY_ALREADY' => $arParams['MESS_BTN_BUY_ALREADY'],
		'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
		'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
		'MESS_BTN_SUBSCRIBE_ALREADY' => $arParams['MESS_BTN_SUBSCRIBE_ALREADY'],
		'MESS_BTN_COMPARE' => $arParams['MESS_BTN_COMPARE'],
		'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
		'MESS_SET' => $arParams['MESS_SET'],
		'MESS_BTN_SET' => $arParams['MESS_BTN_SET'],
		'MESS_COMPLETE' => $arParams['MESS_COMPLETE'],
		'USE_VOTE_RATING' => $arParams['DETAIL_USE_VOTE_RATING'],
		'VOTE_DISPLAY_AS_RATING' => (isset($arParams['DETAIL_VOTE_DISPLAY_AS_RATING']) ? $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'] : ''),
		'USE_COMMENTS' => $arParams['DETAIL_USE_COMMENTS'],
		'MESSAGES_PER_PAGE' => $arParams['MESSAGES_PER_PAGE'],
		'USE_CAPTCHA' => $arParams['USE_CAPTCHA'],
		'BLOG_USE' => (isset($arParams['DETAIL_BLOG_USE']) ? $arParams['DETAIL_BLOG_USE'] : ''),
		'BLOG_URL' => (isset($arParams['DETAIL_BLOG_URL']) ? $arParams['DETAIL_BLOG_URL'] : ''),
		'BLOG_EMAIL_NOTIFY' => (isset($arParams['DETAIL_BLOG_EMAIL_NOTIFY']) ? $arParams['DETAIL_BLOG_EMAIL_NOTIFY'] : ''),
		'VK_USE' => (isset($arParams['DETAIL_VK_USE']) ? $arParams['DETAIL_VK_USE'] : ''),
		'VK_API_ID' => (isset($arParams['DETAIL_VK_API_ID']) ? $arParams['DETAIL_VK_API_ID'] : 'API_ID'),
		'FB_USE' => (isset($arParams['DETAIL_FB_USE']) ? $arParams['DETAIL_FB_USE'] : ''),
		'FB_APP_ID' => (isset($arParams['DETAIL_FB_APP_ID']) ? $arParams['DETAIL_FB_APP_ID'] : ''),
		'BRAND_USE' => (isset($arParams['DETAIL_BRAND_USE']) ? $arParams['DETAIL_BRAND_USE'] : 'N'),
		'BRAND_PROP_CODE' => (isset($arParams['DETAIL_BRAND_PROP_CODE']) ? $arParams['DETAIL_BRAND_PROP_CODE'] : ''),
		'DISPLAY_NAME' => (isset($arParams['DETAIL_DISPLAY_NAME']) ? $arParams['DETAIL_DISPLAY_NAME'] : ''),
		'ADD_DETAIL_TO_SLIDER' => (isset($arParams['DETAIL_ADD_DETAIL_TO_SLIDER']) ? $arParams['DETAIL_ADD_DETAIL_TO_SLIDER'] : ''),
		'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
		"ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
		"ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
		"DISPLAY_PREVIEW_TEXT_MODE" => (isset($arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE']) ? $arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE'] : ''),
		"DETAIL_PICTURE_MODE" => (isset($arParams['DETAIL_DETAIL_PICTURE_MODE']) ? $arParams['DETAIL_DETAIL_PICTURE_MODE'] : ''),
		'ADD_TO_BASKET_ACTION' => $basketAction,
		'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
		'DISPLAY_COMPARE' => (isset($arParams['USE_COMPARE']) ? $arParams['USE_COMPARE'] : ''),
		'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
		'SHOW_BASIS_PRICE' => (isset($arParams['DETAIL_SHOW_BASIS_PRICE']) ? $arParams['DETAIL_SHOW_BASIS_PRICE'] : 'Y'),
		'BACKGROUND_IMAGE' => (isset($arParams['DETAIL_BACKGROUND_IMAGE']) ? $arParams['DETAIL_BACKGROUND_IMAGE'] : ''),
		'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
		'SET_VIEWED_IN_COMPONENT' => (isset($arParams['DETAIL_SET_VIEWED_IN_COMPONENT']) ? $arParams['DETAIL_SET_VIEWED_IN_COMPONENT'] : ''),
		"USE_MIN_AMOUNT" => $arParams['USE_MIN_AMOUNT'],
		"MIN_AMOUNT" => $arParams['MIN_AMOUNT'],
		"MAX_AMOUNT" => $arParams['MAX_AMOUNT'],
		"USE_STORE" => $arParams["USE_STORE"],
		"STORES" => $arParams["STORES"],
		"USER_FIELDS" => $arParams['USER_FIELDS'],
		"FIELDS" => $arParams['FIELDS'],
		"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
		"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
		"MESS_GENERAL_STORE" => $arParams['MESS_GENERAL_STORE'],
		"STORE_PATH" => $arParams['STORE_PATH'],
		"SHOW_PRODUCT_SET" => $arParams['SHOW_PRODUCT_SET'],
		"REQUEST_LOAD" => $arParams["REQUEST_LOAD"],
		"SKU_PICT_TYPE" => $arParams["SKU_PICT_TYPE"],
		
		'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
		'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
		'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),
	),
	$component
);?>
<?if ($arParams["REQUEST_LOAD"] != "Y"):?>
<?
$GLOBALS["CATALOG_CURRENT_ELEMENT_ID"] = $elementId;
unset($basketAction);
if ($elementId > 0)
{
	$recommendedData = array();
	$recommendedCacheId = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);

	$obCache = new CPHPCache();
	if ($obCache->InitCache(36000, serialize($recommendedCacheId), '/catalog/recommended'))
	{
		$recommendedData = $obCache->GetVars();
	}
	elseif ($obCache->StartDataCache())
	{
		if (Loader::includeModule('catalog'))
		{
			$arSKU = CCatalogSku::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
			$recommendedData['OFFER_IBLOCK_ID'] = (!empty($arSKU) ? $arSKU['IBLOCK_ID'] : 0);
			$recommendedData['IBLOCK_LINK'] = '';
			$recommendedData['ALL_LINK'] = '';
			$rsProps = CIBlockProperty::GetList(
				array('SORT' => 'ASC', 'ID' => 'ASC'),
				array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'PROPERTY_TYPE' => 'E', 'ACTIVE' => 'Y')
			);
			$found = false;
			while ($arProp = $rsProps->Fetch())
			{
				if ($found)
				{
					break;
				}

				if ($arProp['CODE'] == '')
				{
					$arProp['CODE'] = $arProp['ID'];
				}

				$arProp['LINK_IBLOCK_ID'] = intval($arProp['LINK_IBLOCK_ID']);
				if ($arProp['LINK_IBLOCK_ID'] != 0 && $arProp['LINK_IBLOCK_ID'] != $arParams['IBLOCK_ID'])
				{
					continue;
				}

				if ($arProp['LINK_IBLOCK_ID'] > 0)
				{
					if ($recommendedData['IBLOCK_LINK'] == '')
					{
						$recommendedData['IBLOCK_LINK'] = $arProp['CODE'];
						$found = true;
					}
				}
				else
				{
					if ($recommendedData['ALL_LINK'] == '')
					{
						$recommendedData['ALL_LINK'] = $arProp['CODE'];
					}
				}
			}

			if ($found)
			{
				if (defined('BX_COMP_MANAGED_CACHE'))
				{
					global $CACHE_MANAGER;
					$CACHE_MANAGER->StartTagCache('/catalog/recommended');
					$CACHE_MANAGER->RegisterTag('iblock_id_'.$arParams['IBLOCK_ID']);
					$CACHE_MANAGER->EndTagCache();
				}
			}
		}

		$obCache->EndDataCache($recommendedData);
	}

	if (!empty($recommendedData))
	{
		if (isset($arParams['USE_BIG_DATA']) && $arParams['USE_BIG_DATA'] == 'Y')
		{?>
			<?
			$bigdataTitle = Option::get('bitlate.proshop', "NL_SLIDER_BIGDATA_TITLE", false, SITE_ID);
			$APPLICATION->IncludeComponent(
				'bitrix:catalog.section',
				"bigdata",
				array(
					'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
					'IBLOCK_ID' => $arParams['IBLOCK_ID'],
					'SECTION_ID' => "",
					'SECTION_CODE' => "",
					'ELEMENT_SORT_FIELD' => 'shows',
					'ELEMENT_SORT_ORDER' => 'desc',
					'ELEMENT_SORT_FIELD2' => 'sort',
					'ELEMENT_SORT_ORDER2' => 'asc',
					'PROPERTY_CODE' => $arParams['DETAIL_PROPERTY_CODE'],
					'PROPERTY_CODE_MOBILE' => $arParams['DETAIL_PROPERTY_CODE_MOBILE'],
					'INCLUDE_SUBSECTIONS' => $arParams['INCLUDE_SUBSECTIONS'],
					'BASKET_URL' => $arParams['BASKET_URL'],
					'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
					'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
					'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],
					'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
					'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
					'CACHE_TYPE' => $arParams['CACHE_TYPE'],
					'CACHE_TIME' => $arParams['CACHE_TIME'],
					'CACHE_FILTER' => $arParams['CACHE_FILTER'],
					'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
					'DISPLAY_COMPARE' => $arParams['USE_COMPARE'],
					'PRICE_CODE' => $arParams['PRICE_CODE'],
					'PRICE_MULTY' => $arParams['PRICE_MULTY'],
					'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
					'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
					'PAGE_ELEMENT_COUNT' => 0,
					'LINE_ELEMENT_COUNT' => 5,
					'FILTER_IDS' => array($elementId),

					"SET_TITLE" => "N",
					"SET_BROWSER_TITLE" => "N",
					"SET_META_KEYWORDS" => "N",
					"SET_META_DESCRIPTION" => "N",
					"SET_LAST_MODIFIED" => "N",
					"ADD_SECTIONS_CHAIN" => "N",

					'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
					'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
					'ADD_PROPERTIES_TO_BASKET' => (isset($arParams['ADD_PROPERTIES_TO_BASKET']) ? $arParams['ADD_PROPERTIES_TO_BASKET'] : ''),
					'PARTIAL_PRODUCT_PROPERTIES' => (isset($arParams['PARTIAL_PRODUCT_PROPERTIES']) ? $arParams['PARTIAL_PRODUCT_PROPERTIES'] : ''),
					'PRODUCT_PROPERTIES' => $arParams['PRODUCT_PROPERTIES'],

					'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
					'OFFERS_FIELD_CODE' => $arParams['DETAIL_OFFERS_FIELD_CODE'],
					'OFFERS_PROPERTY_CODE' => $arParams['DETAIL_OFFERS_PROPERTY_CODE'],
					'OFFERS_SORT_FIELD' => $arParams['OFFERS_SORT_FIELD'],
					'OFFERS_SORT_ORDER' => $arParams['OFFERS_SORT_ORDER'],
					'OFFERS_SORT_FIELD2' => $arParams['OFFERS_SORT_FIELD2'],
					'OFFERS_SORT_ORDER2' => $arParams['OFFERS_SORT_ORDER2'],
					'OFFERS_LIMIT' => $arParams['DETAIL_OFFERS_LIMIT'],

					'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
					'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['element'],
					'USE_MAIN_ELEMENT_SECTION' => $arParams['USE_MAIN_ELEMENT_SECTION'],
					'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
					'CURRENCY_ID' => $arParams['CURRENCY_ID'],
					'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
					'HIDE_NOT_AVAILABLE_OFFERS' => $arParams['HIDE_NOT_AVAILABLE_OFFERS'],

					'LABEL_PROP' => $arParams['LABEL_PROP'],
					'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
					'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
					'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
					'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
					'PRODUCT_BLOCKS_ORDER' => $arParams['DETAIL_PRODUCT_BLOCKS_ORDER'],
					'PRODUCT_ROW_VARIANTS' => "[{'VARIANT':'4','BIG_DATA':true},{'VARIANT':'1','BIG_DATA':false}]",
					'ENLARGE_PRODUCT' => "PROP", //$arParams['DETAIL_ENLARGE_PRODUCT'],
					'ENLARGE_PROP' => isset($arParams['DETAIL_ENLARGE_PROP']) ? $arParams['DETAIL_ENLARGE_PROP'] : '',
					'SHOW_SLIDER' => $arParams['DETAIL_SHOW_SLIDER'],
					'SLIDER_INTERVAL' => isset($arParams['DETAIL_SLIDER_INTERVAL']) ? $arParams['DETAIL_SLIDER_INTERVAL'] : '',
					'SLIDER_PROGRESS' => isset($arParams['DETAIL_SLIDER_PROGRESS']) ? $arParams['DETAIL_SLIDER_PROGRESS'] : '',

					'DISPLAY_TOP_PAGER' => 'N',
					'DISPLAY_BOTTOM_PAGER' => 'N',
					'HIDE_SECTION_DESCRIPTION' => 'Y',

					'RCM_TYPE' => isset($arParams['BIG_DATA_RCM_TYPE']) ? $arParams['BIG_DATA_RCM_TYPE'] : 'bestsell',
					'RCM_PROD_ID' => $elementId,
					'SHOW_FROM_SECTION' => 'Y',

					'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
					'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
					'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
					'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
					'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
					'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
					'SHOW_MAX_QUANTITY' => $arParams['LIST_SHOW_MAX_QUANTITY'],
					'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
					'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
					'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
					'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
					'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
					'MESS_BTN_BUY_ALREADY' => (isset($arParams['~MESS_BTN_BUY_ALREADY']) ? $arParams['~MESS_BTN_BUY_ALREADY'] : ''),
					'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
					'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
					'MESS_BTN_SUBSCRIBE_ALREADY' => (isset($arParams['~MESS_BTN_SUBSCRIBE_ALREADY']) ? $arParams['~MESS_BTN_SUBSCRIBE_ALREADY'] : ''),
					'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
					'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
					'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

					'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
					'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
					'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

					'TEMPLATE_THEME' => 'slider',
					'ADD_TO_BASKET_ACTION' => $basketAction,
					'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
					'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
					'COMPARE_NAME' => $arParams['COMPARE_NAME'],
					'BACKGROUND_IMAGE' => '',
					'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
					'SLIDER_ZINDEX' => 1,
					'PAGER_TITLE' => $bigdataTitle,
					"SHOW_PRODUCT_PREVIEW" => $arParams["SHOW_PRODUCT_PREVIEW"],
					"SKU_PICT_TYPE" => $arParams["SKU_PICT_TYPE"],
					"USE_LAZY_LOAD" => $arParams["USE_LAZY_LOAD"],
				)
			);
			?>
		<?}
	}
}
?>
	</section>
<?else:?>
			</div>
		</div>
	</div>
<?endif;?>