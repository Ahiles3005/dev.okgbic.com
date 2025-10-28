<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Encoding;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arSections = $arParams['SECTIONS'];
$arElements = $arParams['ELEMENTS'];
$arElements['PARAMETERS']['INCLUDE_SUBSECTIONS'] = "Y";
$arElements['SHOW'] = true;

if (empty($arElements['TEMPLATE']))
    $arElements['SHOW'] = false;

if ($arElements['SHOW']) {
    $sPrefix = 'LIST_';

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        if ($sKey === 'TEMPLATE')
            continue;

        $arElements['PARAMETERS'][$sKey] = $mValue;
    }
}

$arElements['PARAMETERS'] = ArrayHelper::merge([
    'FILTER_NAME' => 'arrFilter'
], $arElements['PARAMETERS']);

$arFilter = $arParams['FILTER'];

?>
<div id="<?= $sTemplateId ?>" class="catalog-search">
    <?php

    $oRequest = Core::$app->request;

    if ((($oRequest->getIsAjax() || isset($_SERVER['HTTP_BX_AJAX'])) && $oRequest->get('ajax') === 'y')) {

       if ($sQuery = $oRequest->get('q')) {
           $sQuery = Encoding::convert($sQuery, null, Encoding::UTF8);

           $_REQUEST['q'] = $sQuery;
       }

    }


    $GLOBALS['searchPageFilter']['!ITEM_ID'] = 'S%'; //exclude sections from search results

    ?>
    <?php $this->SetViewTarget('component_search');?>
        <?php $arElements['ID'] = $APPLICATION->IncludeComponent(
            'bitrix:search.page',
            'catalog',
            [
                'RESTART' => $arParams['RESTART'],
                'NO_WORD_LOGIC' => $arParams['NO_WORD_LOGIC'],
                'USE_LANGUAGE_GUESS' => $arParams['USE_LANGUAGE_GUESS'],
                'CHECK_DATES' => $arParams['CHECK_DATES'],
                'arrFILTER' => ['iblock_'.$arParams['IBLOCK_TYPE']],
                'arrFILTER_iblock_'.$arParams['IBLOCK_TYPE'] => [$arParams['IBLOCK_ID']],
                'USE_TITLE_RANK' => 'N',
                'DEFAULT_SORT' => 'rank',
                'FILTER_NAME' => 'searchPageFilter',
                'SHOW_WHERE' => 'N',
                'arrWHERE' => [],
                'SHOW_WHEN' => 'N',
                'PAGE_RESULT_COUNT' => 9999,
                'DISPLAY_TOP_PAGER' => 'N',
                'DISPLAY_BOTTOM_PAGER' => 'N',
                'PAGER_TITLE' => '',
                'PAGER_SHOW_ALWAYS' => 'N',
                'PAGER_TEMPLATE' => 'N',
            ],
            $component,
            ['HIDE_ICONS' => 'N']
        ) ?>
    <?php $this->EndViewTarget();

    if (!Type::isArray($arElements['ID']))
        $arElements['ID'] = [];

    if (!count($arElements['ID'])){
        $arElements['SHOW'] = false;
    }

    $GLOBALS['smartPreFilter']['=ID'] = $arElements['ID'];

    $arColumns = [
        'SHOW' => ($arFilter['SHOW'] && $arFilter['TYPE'] === 'vertical')
    ];

    $sFilterName = $arElements['PARAMETERS']['FILTER_NAME'];

    $arSectFilter = Array('IBLOCK_ID'=>138, 'ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y', 'NAME'=>$_REQUEST["q"]);
    if (isset($GLOBALS['arrSectionFilter'])) {
        $arSectFilter = ArrayHelper::merge($arSectFilter, $GLOBALS['arrSectionFilter']);
    }
    $db_list = CIBlockSection::GetList(Array($by=>$order), $arSectFilter, true, ['ID', 'CODE', 'NAME', 'SECTION_PAGE_URL', 'UF_*']);
    $sections = [];
    while($ar_result = $db_list->GetNext()){
        $sections[$ar_result['ID']] = $ar_result;
    }

    if ($arElements['SHOW'] || count($sections)) {
        if (0 && $arFilter['SHOW'] && $arFilter['TYPE'] === 'horizontal') { ?>
            <!--noindex-->
            <?php $APPLICATION->IncludeComponent(
                'bitrix:catalog.smart.filter',
                $arFilter['TEMPLATE'],
                $arFilter['PARAMETERS'],
                $component
            ) ?>
            <!--/noindex-->
        <?php } ?>
        <?= Html::beginTag('div', [
            'class' => 'catalog-content',
            'data' => [
                'role' => !$arColumns['SHOW'] ? 'content' : null
            ]
        ]) ?>
            <?php
//pre($GLOBALS['searchPageFilter']);
//pre($arSections);
            if ($arColumns['SHOW']) { ?>
                <div class="catalog-content-left intec-content-left">
                    <?php if ($arSections['SHOW']) {
                        $arSections['PARAMETERS']['ELEMENTS_ID'] = $arElements['ID'];
                        $arSections['PARAMETERS']['DEPTH'] = '3';

                        $arSections['RESULT'] = $APPLICATION->IncludeComponent(
                            "intec.universe:search.sections",
                            $arSections['TEMPLATE'],
                            $arSections['PARAMETERS'],
                            $component
                        );

                        if (!empty($arSections['RESULT']))
                            foreach ($arSections['RESULT'] as &$arSection)
                                if ($arSection['CURRENT'] === 'Y') {
                                    $GLOBALS[$sFilterName]['IBLOCK_SECTION_ID'] = $arSection['ID'];
                                    break;
                                }
                    } ?>
                    <?php if ($arFilter['SHOW']) { ?>
                        <?php $APPLICATION->IncludeComponent(
                            'bitrix:catalog.smart.filter',
                            $arFilter['TEMPLATE'],
                            $arFilter['PARAMETERS'],
                            $component
                        ) ?>
                    <?php } ?>
                </div>
                <div class="catalog-content-right intec-content-right">
            <?php } ?>
                <?$APPLICATION->ShowViewContent('component_search');?>
                <div class="catalog-content-right-wrapper intec-content-right-wrapper" data-role="content">
                <?php if(count($sections)){ ?>
                    <div class="catalog-element-additional-block-content">
                        <h2>Категории</h2>
                        <?php foreach($sections as $k => $v){ ?>
                            <p><a href="<?= $v['SECTION_PAGE_URL'] ?>"><?= $v['NAME']?></a></p>
                        <?php } ?>
                    </div>
                <?php } ?>
                    <?php $APPLICATION->ShowViewContent('panel_sort_search');

                    if ($arFilter['SHOW']) { ?>
                        <div class="catalog-filter-mobile" data-role="filter">
                            <?php $APPLICATION->IncludeComponent(
                                'bitrix:catalog.smart.filter',
                                'vertical.1',
                                ArrayHelper::merge($arFilter['PARAMETERS'], [
                                    'MOBILE' => 'Y'
                                ]),
                                $component
                            ) ?>
                        </div>
                    <?php } ?>
<?
//print_r($arElements['SHOW']);
//print_r($_REQUEST["q"]);?>
                    <?php //$GLOBALS[$sFilterName]['ID'] = $arElements['ID']; ?>
<?
if(isset($GLOBALS['arrSubdomainSectionId'])){
	$GLOBALS[$sFilterName][]['IBLOCK_SECTION_ID'] = $GLOBALS['arrSubdomainSectionId'];
}
//print_r($_REQUEST["q"]);
unset($GLOBALS[$sFilterName]['FACET_OPTIONS']);
//unset($GLOBALS[$sFilterName]['=ID']);
//pre($sections);
//pre($GLOBALS['arrSubdomainSectionId']);
?>
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:catalog.section',
                        $arElements['TEMPLATE'],
                        $arElements['PARAMETERS'],
                        $component,
                        ['HIDE_ICONS' => 'Y']
                    ) ?>

            <?php if ($arColumns['SHOW']) { ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php } ?>
        <?= Html::endTag('div') ?>
    <?php } else { ?>
        <?$APPLICATION->ShowViewContent('component_search');?>
        <div class="catalog-search-message">
            <?= Loc::getMessage('C_CATALOG_SEARCH_DEFAULT_NOT_FOUND') ?>
        </div>
    <?php } ?>
    <script type="text/javascript">
        (function () {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var filter = $('[data-role="filter"]', root);

            filter.state = false;
            filter.button = $('[data-role="catalog.filter.button"]', root);
            filter.button.on('click', function () {
                if (filter.state) {
                    filter.hide();
                } else {
                    filter.show();
                }

                filter.state = !filter.state;
            });
        })();
    </script>
</div>