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
	"intec.universe:main.sections", 
	"template.2", 
	array(
		"IBLOCK_TYPE" => "1s_catalog_okgbi",
		"IBLOCK_ID" => "138",
		"QUANTITY" => "N",
		"SECTIONS_MODE" => "id",
		"DEPTH" => "2",
		"ELEMENTS_COUNT" => "10",
		"SETTINGS_USE" => "Y",
		"LAZYLOAD_USE" => "N",
		"HEADER_SHOW" => "Y",
		"HEADER_POSITION" => "center",
		"HEADER_TEXT" => "Очаковский завод ЖБИ - каталог продукции",
		"DESCRIPTION_SHOW" => "N",
		"LINE_COUNT" => "3",
		"PICTURE_SIZE" => "small",
		"SUB_SECTIONS_SHOW" => "Y",
		"SUB_SECTIONS_MAX" => "10",
		"SECTION_URL" => "",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "3600000",
		"SORT_BY" => "SORT",
		"ORDER_BY" => "ASC",
		"COMPONENT_TEMPLATE" => "template.2",
		"LIST_PAGE_URL" => "",
		"SECTIONS" => array(
			0 => "",
			1 => "",
		),
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
); ?>
<?= Html::endTag('div') ?>