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
$isShowAll = "Y";

include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/top.php");
if (!CModule::IncludeModule('bitlate.proshop')) return false;
$banners = BitlateProUtils::getBannerType();?>
<?if ($arParams["CATALOG_MAIN_LIST"] == "Y"):?>
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
        <div class="advanced-container-medium catalog-wrapper">
            <article class="inner-container">
                <?if ($arParams["SECTIONS_CATALOG_SHOW_IN_SECTIONS"] == "Y"):?>
                    <div class="inner-menu catalog-filters columns show-for-xlarge">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:catalog.section.list",
                            "",
                            array(
                                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                                "SECTION_ID" => '',
                                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                "CACHE_TIME" => $arParams["CACHE_TIME"],
                                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                                "TOP_DEPTH" => $arParams["SECTION_TOP_MENU_DEPTH"],
                                "SECTION_USER_FIELDS" => array("UF_SECTION_HOVER_IMG", "UF_SECTION_BG_IMG", "UF_SECTION_STYLE", "UF_SECTION_BG_LINK", "UF_SECTION_BG_STYLE"),
                                "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                                "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
                                "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                                "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                                "PARAMS_STRING" => $arParams["PARAMS_STRING"],
                                "ADD_SECTIONS_CHAIN" => "N",
                                "MENU_OTHER_PIC" => $arParams["CATALOG_MENU_OTHER_PIC"],
                                "MENU_SHOW_DEEP" => $arParams["SECTION_LIST_MENU_SHOW_DEEP"],
                                "CATALOG_IN_FILTER_HIDE" => $arParams["SECTION_CATALOG_IN_FILTER_HIDE"],
                            ),
                            $component,
                            array("HIDE_ICONS" => "Y")
                        );?>
                        <?if ($banners['LEFT'] == 1 && \Bitrix\Main\Loader::includeModule('advertising')):?>
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:advertising.banner",
                                "main",
                                Array(
                                    "CACHE_TIME" => "0",
                                    "CACHE_TYPE" => "A",
                                    "NOINDEX" => "Y",
                                    "QUANTITY" => "1",
                                    "TYPE" => "BITLATE_LEFT",
                                )
                            );?>
                        <?endif;?>
                    </div>
                    <div class="inner-content catalog-category columns row float-right xlarge-up-2">
                <?else:?>
                    <div class="catalog-category columns row float-right xlarge-up-2">
                <?endif;?>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:catalog.section.list",
                        "catalog",
                        array(
                            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                            "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                            "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
                            "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                            "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
                            "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                            "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                            "ADD_SECTIONS_CHAIN" => "N"
                        ),
                        $component,
                        array("HIDE_ICONS" => "Y")
                    );?>
                </div>
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
<?else:
    include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/section_filter.php");
endif;?>