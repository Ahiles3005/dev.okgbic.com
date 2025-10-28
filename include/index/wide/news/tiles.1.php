<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\collections\Arrays;
use intec\core\helpers\Html;
use intec\core\io\Path;

/**
 * @var Arrays $blocks
 * @var array $block
 * @var array $data
 * @var string $page
 * @var Path $path
 * @global CMain $APPLICATION
 */

?>
<?= Html::beginTag('div', ['style' => [
    'margin-top' => '50px',
    'margin-bottom' => '50px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
	"intec.universe:main.news", 
	"template.3", 
	array(
		"IBLOCK_TYPE" => "news",
		"IBLOCK_ID" => "6",
		"ELEMENTS_COUNT" => "4",
		"SETTINGS_USE" => "Y",
		"LAZYLOAD_USE" => "N",
		"HEADER_BLOCK_SHOW" => "Y",
		"HEADER_BLOCK_POSITION" => "center",
		"HEADER_BLOCK_TEXT" => "Новости",
		"DESCRIPTION_BLOCK_SHOW" => "N",
		"DATE_SHOW" => "Y",
		"DATE_FORMAT" => "d.m.Y",
		"COLUMNS" => "4",
		"LINK_USE" => "Y",
		"FOOTER_SHOW" => "N",
		"SECTION_URL" => "/#SITE_DIR#/about/articles/",
		"DETAIL_URL" => "/#SITE_DIR#/about/articles/#ELEMENT_CODE#/",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"SORT_BY" => "ACTIVE_FROM",
		"ORDER_BY" => "DESC",
		"COMPONENT_TEMPLATE" => "template.3",
		"LIST_PAGE_URL" => "/#SITE_DIR#/about/articles/",
		"NAVIGATION_USE" => "N",
		"PROPERTY_TAGS" => "",
		"DATE_TYPE" => "DATE_ACTIVE_FROM",
		"LINK_BLANK" => "N"
	),
	false
); ?>
<?= Html::endTag('div') ?>