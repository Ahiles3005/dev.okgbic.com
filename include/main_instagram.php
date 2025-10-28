<?
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
Loader::includeModule('bitlate.proshop');
$countNews = Option::get('bitlate.proshop', "NL_SLIDER_NEWS_PAGE_ELEMENT_COUNT", 0, SITE_ID);
$blockTitle = Option::get('bitlate.proshop', "NL_MAIN_INSTAGRAM_TITLE", false, SITE_ID);?>

<?$APPLICATION->IncludeComponent(
	"bitlatepro:instagram.list", 
	".default", 
	array(
		"INSTAGRAM_LOGIN" => "bitlate.bitrixshops",
		"INSTAGRAM_PAGE_ELEMENT_COUNT" => "8",
		"BG_TRANSPARENT" => "Y",
		"PAGER_TITLE" => $blockTitle,
		"COMPONENT_TEMPLATE" => ".default",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>