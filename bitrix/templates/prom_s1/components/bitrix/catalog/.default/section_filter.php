<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader,
    Bitrix\Main\ModuleManager;?>
<?if (!CModule::IncludeModule('bitlate.proshop')) return false;
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
                <div class="inner-description"><?$APPLICATION->ShowProperty("NL_CATALOG_SECTION_DESCRIPTION")?></div>
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
        <div class="advanced-container-medium catalog-wrapper <?if ($arParams["FILTER_VIEW_MODE"] == 'VERTICAL'):?>catalog-wrapper--vertical<?else:?>catalog-wrapper--horizontal<?endif;?>">
            <article class="inner-container">
<?endif;?>
<?if ($isFilter):
    $templateFilter = BitlateProUtils::getComponentTemplate("filter");?>
    <?if ($arParams["REQUEST_LOAD"] != "Y"):?>
        <nav class="inner-menu columns show-for-xlarge" id="catalog-filter-wrapper">
    <?endif;?>
        <?$APPLICATION->IncludeComponent(
            "bitrix:catalog.smart.filter",
            $templateFilter,
            array(
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "SECTION_ID" => ($isShowAll == "Y") ? '' : $arCurSection['ID'],
                "PARENT_SECTION_ID" => $arCurSection['IBLOCK_SECTION_ID'],
                "FILTER_NAME" => $arParams["FILTER_NAME"],
                "PRICE_CODE" => $arParams["FILTER_PRICE_CODE"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "SAVE_IN_SESSION" => "N",
                "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
                "XML_EXPORT" => "Y",
                "SECTION_TITLE" => "NAME",
                "SECTION_DESCRIPTION" => "DESCRIPTION",
                'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
                'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                "SEF_MODE" => "Y",
                "SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
                "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                "REQUEST_SORT" => $arParams['REQUEST_SORT'],
                "REQUEST_VIEW" => $arParams['REQUEST_VIEW'],
                "REQUEST_PAGE_EL_COUNT" => $arParams['REQUEST_PAGE_EL_COUNT'],
                "REQUEST_LOAD" => $arParams["REQUEST_LOAD"],
                "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
                "TOP_MENU_DEPTH" => $arParams["SECTION_TOP_MENU_DEPTH"],
                "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
                "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                "ADD_SECTIONS_CHAIN" => "N",
                "SHOW_ALL_WO_SECTION" => $isShowAll,
                "PARAMS_STRING" => $arParams["PARAMS_STRING"],
                "CATALOG_SHOW_IN_FILTER" => $arParams["SECTION_CATALOG_SHOW_IN_FILTER"],
                "CATALOG_IN_FILTER_VIEW" => $arParams["SECTION_CATALOG_IN_FILTER_VIEW"],
                "CATALOG_IN_FILTER_HIDE" => $arParams["SECTION_CATALOG_IN_FILTER_HIDE"],
                "CATALOG_SHOW_SUBSECTION" => $arParams["SECTION_CATALOG_SHOW_SUBSECTION"],
                "CATALOG_MENU_OTHER_PIC" => $arParams["SECTION_CATALOG_MENU_OTHER_PIC"],
                "CATALOG_MENU_PIC" => $arParams["SECTION_CATALOG_MENU_PIC"],
                "MENU_SHOW_DEEP" => $arParams["SECTION_LIST_MENU_SHOW_DEEP"],
                "FILTER_NUMBERS_SHOW" => $arParams["FILTER_NUMBERS_SHOW"],
                "DISPLAY_ELEMENT_COUNT" => $arParams["DISPLAY_ELEMENT_COUNT"],
            ),
            $component,
            array('HIDE_ICONS' => 'Y')
        );?>
    <?if ($arParams["REQUEST_LOAD"] != "Y"):?>
            <?if ($arParams["FILTER_VIEW_MODE"] == 'VERTICAL' && $banners['LEFT'] == 1):?>
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
        </nav>
    <?endif;?>
<?endif?>
<?
if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
    $basketAction = (isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '');
else
    $basketAction = (isset($arParams['SECTION_ADD_TO_BASKET_ACTION']) ? $arParams['SECTION_ADD_TO_BASKET_ACTION'] : '');

$intSectionID = 0;?>
    <?if ($arParams["REQUEST_LOAD"] != "Y"):?>
        <div class="inner-content columns row float-right">
            <?$landingResult = $APPLICATION->IncludeComponent(
                "bitlatepro:landing.list",
                "",
                Array(
                    "PARAMS_STRING" => $APPLICATION->GetCurPageParam(),
                    "URL_STRING" => $APPLICATION->GetCurDir(),
                    "SEF_FOLDER" => $arParams["SEF_FOLDER"],
                    "SEF_URL_TEMPLATES" => $arParams["SEF_URL_TEMPLATES"],
                    "REQUEST_LOAD" => $arParams["REQUEST_LOAD"],
                )
            );?>
            <div class="catalog-reload">
    <?endif;?>
        <?if ($arParams["SECTION_CATALOG_SHOW_SUBSECTION"] == "Y"):?>
            <?$APPLICATION->IncludeComponent(
                "bitlatepro:catalog.section.id.list",
                "subcategory",
                array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "SECTION_ID" => $arCurSection["ID"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                    "TOP_DEPTH" => 1,
                    "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                    "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
                    "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                    "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                    "ADD_SECTIONS_CHAIN" => "N",
                    "USE_LAZY_LOAD" => $arParams["USE_LAZY_LOAD"],
                ),
                $component,
                array("HIDE_ICONS" => "Y")
            );?>
        <?endif;?>
        <?$arSortView = $APPLICATION->IncludeComponent(
            "bitlatepro:catalog.sort.view",
            "",
            array(
                "SORT_CODES" => $arParams['SORT_LIST_CODES'],
                "SORT_FIELDS" => $arParams['SORT_LIST_FIELDS'],
                "SORT_ORDERS" => $arParams['SORT_LIST_ORDERS'],
                "SORT_NAME" => $arParams['SORT_LIST_NAME'],
                "REQUEST_SORT" => $arParams['REQUEST_SORT'],
                "REQUEST_VIEW" => $arParams['REQUEST_VIEW'],
                "REQUEST_LOAD" => $arParams["REQUEST_LOAD"],
            ),
            $component,
            array('HIDE_ICONS' => 'Y')
        );?>
        <?$arPageTo = $APPLICATION->IncludeComponent(
            "bitlatepro:catalog.page.to.calc",
            "",
            array(
                "PAGE_TO_LIST" => $arParams['PAGE_TO_LIST'],
                "PAGE_ELEMENT_COUNT" => $arParams['PAGE_ELEMENT_COUNT'],
                "REQUEST_PAGE_EL_COUNT" => $arParams['REQUEST_PAGE_EL_COUNT'],
                "REQUEST_LOAD" => $arParams['REQUEST_LOAD'],
            ),
            $component,
            array('HIDE_ICONS' => 'Y')
        );?>
        <?$pageNum = 1;
        if ("Y" == $arParams["CATALOG_PAGE_BACK"] && $arParams["REQUEST_LOAD"] != "Y" && $_REQUEST && "Y" != $_REQUEST["PAGE"]) {
            foreach ($_REQUEST as $paramName => $paramValue) {
                if (strpos($paramName, 'PAGEN_') !== false) {
                    $pageNum = $paramValue;
                    $pageParam = $paramName;
                }
            }
        }
        if ($pageNum > 1) {?>
            <div class="last-page hide">
                <?require("section_list.php");?>
            </div>
            <div class="full-page">
                <?$arPageTo["PAGE_ELEMENT_COUNT"] = $arPageTo["PAGE_ELEMENT_COUNT"] * $pageNum;?>
                <?require("section_list.php");?>
            </div>
        <?} else {?>
            <?require("section_list.php");
        }?>
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