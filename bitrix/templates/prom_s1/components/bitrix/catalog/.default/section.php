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

$this->setFrameMode(true);

if (!isset($arParams['FILTER_VIEW_MODE']) || (string)$arParams['FILTER_VIEW_MODE'] == '')
	$arParams['FILTER_VIEW_MODE'] = 'VERTICAL';
$arParams['USE_FILTER'] = (isset($arParams['USE_FILTER']) && $arParams['USE_FILTER'] == 'Y' ? 'Y' : 'N');

$isVerticalFilter = ('Y' == $arParams['USE_FILTER'] && $arParams["FILTER_VIEW_MODE"] == "VERTICAL");
$isFilter = ($arParams['USE_FILTER'] == 'Y');
$isShowAll = "N";

include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/top.php");

include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/section_filter.php");
?>

<?//sotbit seometa meta start
global $sotbitSeoMetaTitle;
global $sotbitSeoMetaKeywords;
global $sotbitSeoMetaDescription;
global $sotbitSeoMetaBreadcrumbTitle;
global $sotbitSeoMetaH1;
if(!empty($sotbitSeoMetaH1))
{
$APPLICATION->SetTitle($sotbitSeoMetaH1);
}
if(!empty($sotbitSeoMetaTitle))
{
$APPLICATION->SetPageProperty("title", $sotbitSeoMetaTitle);
}
if(!empty($sotbitSeoMetaKeywords))
{
$APPLICATION->SetPageProperty("keywords", $sotbitSeoMetaKeywords);
}
if(!empty($sotbitSeoMetaDescription))
{
$APPLICATION->SetPageProperty("description", $sotbitSeoMetaDescription);
}
if(!empty($sotbitSeoMetaBreadcrumbTitle) ) {
$APPLICATION->AddChainItem($sotbitSeoMetaBreadcrumbTitle  );
}
//sotbit seometa meta end ?>