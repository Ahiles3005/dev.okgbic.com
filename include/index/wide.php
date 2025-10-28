<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;
use intec\core\collections\Arrays;
use intec\core\io\Path;

/**
 * @var Arrays $blocks
 * @var string $page
 * @var Closure $render($block, $data = [])
 * @var Path $path
 * @global CMain $APPLICATION
 */

$render($blocks->get('icons'));
$render($blocks->get('sections'));
$render($blocks->get('categories'));
$render($blocks->get('products'));
$render($blocks->get('shares'));
$render($blocks->get('services'));

?>
<?/*?>
<?= Html::beginTag('div') ?>
    <?php $APPLICATION->IncludeComponent(
	"intec.universe:main.widget", 
	"form.7.okgbi", 
	array(
		"SETTINGS_USE" => "Y",
		"LAZYLOAD_USE" => "N",
		"WEB_FORM_ID" => "1",
		"WEB_FORM_TITLE_SHOW" => "Y",
		"WEB_FORM_DESCRIPTION_SHOW" => "Y",
		"WEB_FORM_BACKGROUND" => "theme",
		"WEB_FORM_BACKGROUND_OPACITY" => "",
		"WEB_FORM_TEXT_COLOR" => "light",
		"WEB_FORM_POSITION" => "right",
		"WEB_FORM_ADDITIONAL_PICTURE_SHOW" => "Y",
		"WEB_FORM_ADDITIONAL_PICTURE" => "/images/main/form.1.picture.png",
		"WEB_FORM_ADDITIONAL_PICTURE_HORIZONTAL" => "center",
		"WEB_FORM_ADDITIONAL_PICTURE_VERTICAL" => "center",
		"WEB_FORM_ADDITIONAL_PICTURE_SIZE" => "contain",
		"BLOCK_BACKGROUND" => "/images/main/form.1.background.jpg",
		"BLOCK_BACKGROUND_PARALLAX_USE" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"COMPONENT_TEMPLATE" => "form.7.okgbi",
		"WEB_FORM_CONSENT_LINK" => "",
		"WEB_FORM_TITLE_POSITION" => "center",
		"WEB_FORM_DESCRIPTION_POSITION" => "center",
		"WEB_FORM_THEME" => "dark",
		"WEB_FORM_BUTTON_POSITION" => "center",
		"WEB_FORM_BACKGROUND_USE" => "N",
		"WEB_FORM_CONSENT_SHOW" => "parameters",
		"TITLE" => "",
		"DESCRIPTION" => "",
		"BUTTON_TEXT" => "",
		"POPUP_TITLE" => "",
		"FORM_ID" => "6",
		"WIDE" => "Y",
		"BORDER_STYLE" => "squared",
		"FORM_TEMPLATE" => "",
		"CONSENT_URL" => ""
	),
	false
); ?>
<?= Html::endTag('div') ?>
<?*/?>
<?php

$render($blocks->get('videos'));
$render($blocks->get('stages'));
$render($blocks->get('video'));
$render($blocks->get('staff'));
$render($blocks->get('certificates'));
$render($blocks->get('gallery'));
$render($blocks->get('faq'));
$render($blocks->get('about'));
$render($blocks->get('reviews'));
$render($blocks->get('articles'));
$render($blocks->get('news'));
$render($blocks->get('brands'));



$render($blocks->get('contacts'));

$render($blocks->get('advantages'));

?>
