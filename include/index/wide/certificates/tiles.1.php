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
	"intec.universe:main.certificates", 
	"template.2", 
	array(
		"IBLOCK_TYPE" => "content",
		"IBLOCK_ID" => "128",
		"ELEMENTS_COUNT" => "5",
		"SETTINGS_USE" => "Y",
		"LAZYLOAD_USE" => "Y",
		"HEADER_SHOW" => "Y",
		"HEADER_POSITION" => "center",
		"HEADER_TEXT" => "Благодарственные письма",
		"DESCRIPTION_SHOW" => "N",
		"LINE_COUNT" => "5",
		"ALIGNMENT" => "center",
		"NAME_SHOW" => "Y",
		"FOOTER_SHOW" => "Y",
		"FOOTER_POSITION" => "center",
		"FOOTER_BUTTON_SHOW" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"SORT_BY" => "SORT",
		"ORDER_BY" => "ASC",
		"COMPONENT_TEMPLATE" => "template.2",
		"LIST_PAGE_URL" => "/about/pisma/",
		"FOOTER_BUTTON_TEXT" => "Показать все",
		"COLUMNS" => "5",
		"SLIDER_USE" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
); ?>
<?= Html::endTag('div') ?>