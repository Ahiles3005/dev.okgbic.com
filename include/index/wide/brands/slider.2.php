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
    'margin-top' => '100px',
    'margin-bottom' => '100px'
]]) ?>
    <?php $APPLICATION->IncludeComponent(
	"intec.universe:main.brands", 
	"template.3", 
	array(
		"IBLOCK_TYPE" => "content",
		"IBLOCK_ID" => "134",
		"SECTIONS_MODE" => "id",
		"SETTINGS_USE" => "Y",
		"LAZYLOAD_USE" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"SORT_BY" => "SORT",
		"ORDER_BY" => "ASC",
		"LINK_USE" => "N",
		"LIST_PAGE_URL" => "/brands/",
		"LINK_BLANK" => "Y",
		"HEADER_SHOW" => "Y",
		"HEADER_POSITION" => "center",
		"HEADER_TEXT" => "Нам доверяют",
		"SLIDER_USE" => "Y",
		"SLIDER_NAVIGATION" => "Y",
		"SLIDER_DOTS" => "N",
		"SLIDER_LOOP" => "Y",
		"SLIDER_AUTO_USE" => "N",
		"LINE_COUNT" => "6",
		"FOOTER_SHOW" => "N",
		"EFFECT_PRIMARY" => "shadow",
		"EFFECT_SECONDARY" => "none",
		"TRANSPARENCY" => "0",
		"BORDER_SHOW" => "Y",
		"SHOW_ALL_BUTTON_DISPLAY" => "top",
		"SHOW_ALL_BUTTON_TEXT" => "Все бренды",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"COMPONENT_TEMPLATE" => "template.3",
		"SECTIONS" => array(
			0 => "",
			1 => "",
		),
		"ELEMENTS_COUNT" => "",
		"DESCRIPTION_SHOW" => "N"
	),
	false
); ?>
<?= Html::endTag('div') ?>