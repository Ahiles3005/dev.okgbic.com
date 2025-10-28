<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader,
	Bitrix\Main\ModuleManager;?>
<?if (!isset($arParams['FILTER_VIEW_MODE']) || (string)$arParams['FILTER_VIEW_MODE'] == '')
	$arParams['FILTER_VIEW_MODE'] = 'VERTICAL';
$arParams['USE_FILTER'] = (isset($arParams['USE_FILTER']) && $arParams['USE_FILTER'] == 'Y' ? 'Y' : 'N');

$isVerticalFilter = ('Y' == $arParams['USE_FILTER'] && $arParams["FILTER_VIEW_MODE"] == "VERTICAL");
$isFilter = ($arParams['USE_FILTER'] == 'Y');

$banners = BitlateProUtils::getBannerType();?>
<?if ($arParams["REQUEST_LOAD"] != "Y"):?>
	<section class="catalog">
		<div class="inner-bg">
			<div class="advanced-container-medium">
				<nav>
					<?$APPLICATION->IncludeComponent("bitrix:breadcrumb","",Array(
							"START_FROM" => "0", 
							"PATH" => "", 
						)
					);?>
				</nav>
				<h1><?$APPLICATION->ShowTitle(false)?></h1>
				<?$APPLICATION->ShowProperty("NL_CATALOG_SECTION_DESCRIPTION")?>
			</div>
		</div>
		<?if ($banners['TOP'] == 1 && \Bitrix\Main\Loader::includeModule('advertising')):?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:advertising.banner",
				"main",
				Array(
					"CACHE_TIME" => "0",
					"CACHE_TYPE" => "A",
					"NOINDEX" => "Y",
					"QUANTITY" => "1",
					"TYPE" => "BITLATE_TOP",
				)
			);?>
		<?endif;?>
		<div class="advanced-container-medium catalog-wrapper<?if ($arParams["FILTER_VIEW_MODE"] != 'VERTICAL'):?> catalog-wrapper--horizontal<?endif;?>">
			<article class="inner-container">
<?endif;?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.search",
	"landing",
	array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
		"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
		"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
		"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
		"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
		"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
		"SECTION_URL" => $arParams["SECTION_URL"],
		"DETAIL_URL" => $arParams["DETAIL_URL"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		'DISPLAY_COMPARE' => (isset($arParams['USE_COMPARE']) ? $arParams['USE_COMPARE'] : ''),
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"PRICE_MULTY" => $arParams["PRICE_MULTY"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
		"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
		"CONVERT_CURRENCY" => "N",
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
		'HIDE_NOT_AVAILABLE_OFFERS' => isset($arParams["HIDE_NOT_AVAILABLE_OFFERS"]) ? $arParams["HIDE_NOT_AVAILABLE_OFFERS"] : '',
		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
		"FILTER_NAME" => "searchFilter",
		"FILTER_PRICE_CODE" => $arParams["FILTER_PRICE_CODE"],
		"FILTER_NUMBERS_SHOW" => $arParams["FILTER_NUMBERS_SHOW"],
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array(),
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"META_KEYWORDS" => "",
		"META_DESCRIPTION" => "",
		"BROWSER_TITLE" => "",
		"ADD_SECTIONS_CHAIN" => "N",
		"SET_TITLE" => "N",
		"SET_STATUS_404" => "N",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",

		"RESTART" => "N",
		"NO_WORD_LOGIC" => "Y",
		"USE_LANGUAGE_GUESS" => $arParams['USE_LANGUAGE_GUESS'],
		"CHECK_DATES" => "Y",

		'LABEL_PROP' => $arParams['LABEL_PROP'],
		'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
		'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

		'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
		'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
		'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
		'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
		'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
		'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
		'MESS_BTN_BUY_ALREADY' => $arParams['MESS_BTN_BUY_ALREADY'],
		'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
		'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
		'MESS_BTN_SUBSCRIBE_ALREADY' => $arParams['MESS_BTN_SUBSCRIBE_ALREADY'],
		'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
		'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],

		'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
		'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
		'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

		'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
		'ADD_TO_BASKET_ACTION' => $basketAction,
		'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
		'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
		'REQUEST_SEARCH_SECTION' => $arParams['REQUEST_SEARCH_SECTION'],
		'REQUEST_LOAD' => $arParams['REQUEST_LOAD'],
		"MOBILE_PRODUCT_COLUMN" => $arParams["MOBILE_PRODUCT_COLUMN"],
		"SHOW_PRODUCT_PREVIEW" => $arParams["SHOW_PRODUCT_PREVIEW"],
		'SKU_PICT_TYPE' => $arParams['SKU_PICT_TYPE'],
		"USE_LAZY_LOAD" => $arParams["USE_LAZY_LOAD"],
		"FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
		"IS_BANNER_LEFT" => $banners['LEFT'],
		"IS_BANNER_BOTTOM" => $banners['BOTTOM'],
		"IS_SHOW_FILTER" => ($isFilter) ? "Y" : "N",
		"TEMPLATE_FILTER" => $templateFilter = BitlateProUtils::getComponentTemplate("filter"),
		"SORT_LIST_CODES" => $arParams["SORT_LIST_CODES"],
		"SORT_LIST_FIELDS" => $arParams["SORT_LIST_FIELDS"],
		"SORT_LIST_ORDERS" => $arParams["SORT_LIST_ORDERS"],
		"SORT_LIST_NAME" => $arParams["SORT_LIST_NAME"],
		"REQUEST_SORT" => $arParams["REQUEST_SORT"],
		"REQUEST_VIEW" => $arParams["REQUEST_VIEW"],
		"PAGE_TO_LIST" => $arParams["PAGE_TO_LIST"],
		"REQUEST_PAGE_EL_COUNT" => $arParams["REQUEST_PAGE_EL_COUNT"],
		"SEF_FOLDER" => $arParams["SEF_FOLDER"],
		"SEF_URL_TEMPLATES" => $arParams["SEF_URL_TEMPLATES"],
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?>
<?if ($arParams["REQUEST_LOAD"] != "Y"):?>
		</div>
	</div>
	<?
	$GLOBALS['CATALOG_CURRENT_SECTION_ID'] = $intSectionID;
	unset($basketAction);
	?>
			</article>
		</div>
		<?if ($banners['BOTTOM'] == 1 && \Bitrix\Main\Loader::includeModule('advertising')):?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:advertising.banner",
				"main",
				Array(
					"CACHE_TIME" => "0",
					"CACHE_TYPE" => "A",
					"NOINDEX" => "Y",
					"QUANTITY" => "1",
					"TYPE" => "BITLATE_BOTTOM",
				)
			);?>
		<?endif;?>
	</section>
<?endif;?>