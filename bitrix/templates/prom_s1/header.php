<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
if (strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') > 0) {
    define('IS_LIGHTHOUSE', true);
}
use intec\Core;
use intec\core\helpers\FileHelper;
use intec\core\helpers\JavaScript;
use intec\constructor\Module as Constructor;
use intec\constructor\models\Build;
use intec\constructor\models\build\Template;

Loc::loadMessages(__FILE__);

if (!Loader::includeModule('intec.core')) {
    echo Loc::getMessage('template.errors.module', ['#MODULE#' => 'intec.core']);
    die();
}

if (!Loader::includeModule('intec.prom')) {
    echo Loc::getMessage('template.errors.module', ['#MODULE#' => 'intec.prom']);
    die();
}

if (
    !Loader::includeModule('intec.constructor') &&
    !Loader::includeModule('intec.constructorlite')
) {
    echo Loc::getMessage('template.errors.modules', ['#MODULES#' => '"intec.constructor", "intec.constructorlite"']);
    die();
}

global $APPLICATION;
global $USER;
global $directory;
global $properties;
global $template;
global $part;

$GLOBALS["PAGE"] = explode("/", $APPLICATION->GetCurPage());
foreach($GLOBALS["PAGE"] as $k=>$page){
    if($page == 'index.php'){unset($GLOBALS['PAGE'][$k]);}
}
IntecProm::Initialize();

$request = Core::$app->request;
$build = Build::getCurrent();

if (empty($build)) {
    echo Loc::getMessage('template.errors.build');
    die();
}

Core::setAlias('@intec/template', __DIR__.'/classes');
require('helper/functions.php');

$areas = $APPLICATION->GetShowIncludeAreas();
$APPLICATION->SetShowIncludeAreas(false);
$menu = new CMenu('left');
$page = $build->getPage();
$properties = $page->getProperties();
$properties
    ->setRange([
        'template-images-lazyload-stub' => SITE_TEMPLATE_PATH.'/images/picture.loading.svg',
        'template-breadcrumb-show' => true,
        'template-title-show' => true,
        'template-page-type' => null
    ])
    ->setRange($APPLICATION->IncludeComponent(
        'intec.universe:system.settings',
        '.default',
        [
            'MODE' => 'configure'
        ],
        false,
        ['HIDE_ICONS' => 'Y']
    ))
    ->setRange([
        'template-menu-show' =>
            $page->getProperties()->get('template-menu-show') &&
            $menu->Init($APPLICATION->GetCurDir(), true) &&
            !empty($menu->arMenu),
        'base-settings-show' => IntecProm::SettingsDisplay(null, SITE_ID),
        'yandex-metrika-use' => IntecProm::YandexMetrikaUse(null, SITE_ID),
        'yandex-metrika-id' => IntecProm::YandexMetrikaId(null, SITE_ID),
        'yandex-metrika-click-map' => IntecProm::YandexMetrikaClickMap(null, SITE_ID),
        'yandex-metrika-track-hash' => IntecProm::YandexMetrikaTrackHash(null, SITE_ID),
        'yandex-metrika-track-links' => IntecProm::YandexMetrikaTrackLinks(null, SITE_ID),
        'yandex-metrika-track-webvisor' => IntecProm::YandexMetrikaWebvisor(null, SITE_ID)
    ]);

if ($APPLICATION->GetCurPage(false) === '/') {
    if ($properties->get('template-page-type') === null) {
        if ($properties->get('pages-main-template') === 'narrow.left') {
            $properties->set('template-page-type', 'narrow');
        } else {
            $properties->set('template-page-type', 'flat');
        }
    }
}

$APPLICATION->SetShowIncludeAreas($areas);
$page->execute(['state' => 'loading']);

unset($areas);
unset($menu);

/** @var Template $template */
$template = $build->getTemplate();

if (empty($template))
    return;

foreach ($template->getPropertiesValues() as $key => $value)
    $properties->set($key, $value);

unset($value);
unset($key);

if (!Constructor::isLite())
    $template->populateRelation('build', $build);

$directory = $build->getDirectory();

if (FileHelper::isFile($directory.'/parts/custom/initialize.php'))
    include($directory.'/parts/custom/initialize.php');

include($directory.'/parts/metrika.php');

include($directory.'/parts/actions.php');

if (FileHelper::isFile($directory.'/parts/custom/start.php'))
    include($directory.'/parts/custom/start.php');

$APPLICATION->AddBufferContent([
    'intec\\template\\Marking',
    'openGraph'
]);

$page->execute(['state' => 'loaded']);
$part = Constructor::isLite() ? 'lite' : 'base';  
use \Bitrix\Main\Page\Asset;

//Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/plugins/jquery.inputmask/inputmask.min.js");
//Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/plugins/jquery.inputmask/jquery.inputmask.min.js");

?><!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
<head>
	<?php if (FileHelper::isFile($directory.'/parts/custom/header.start.php')) include($directory.'/parts/custom/header.start.php') ?>
	<title><?php $APPLICATION->ShowTitle() ?></title>
	<?php

	//$APPLICATION->ShowHead();
	echo '<meta http-equiv="Content-Type" content="text/html; charset='.LANG_CHARSET.'">'."\n";
	$APPLICATION->ShowMeta("robots", false, false);
	//$this->ShowMeta("keywords", false, false);
	$APPLICATION->ShowMeta("description", false, false);
	$APPLICATION->ShowLink("canonical", null, false);
	$APPLICATION->ShowCSS(true, false);
	$APPLICATION->ShowHeadStrings();
	$APPLICATION->ShowHeadScripts();

         ?>
	<meta name="viewport" content="initial-scale=1.0, width=device-width">
	<?php $APPLICATION->ShowMeta('og:type', 'og:type') ?>
	<?php $APPLICATION->ShowMeta('og:title', 'og:title') ?>
	<?php $APPLICATION->ShowMeta('og:description', 'og:description') ?>
	<?php $APPLICATION->ShowMeta('og:image', 'og:image') ?>
	<?php $APPLICATION->ShowMeta('og:url', 'og:url') ?>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" href="/favicon.png">

	<link rel="apple-touch-icon" href="/apple-touch-icon.png" />
	<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png" />
	<link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png" />
	<link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png" />
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png" />

	<script>
	(function () {
		universe.site.id = <?= JavaScript::toObject(SITE_ID) ?>;
		universe.site.directory = <?= JavaScript::toObject(SITE_DIR) ?>;
		universe.template.id = <?= JavaScript::toObject(SITE_TEMPLATE_ID) ?>;
		universe.template.directory = <?= JavaScript::toObject(SITE_TEMPLATE_PATH) ?>;
	})();
	</script>
<?php if (!Constructor::isLite()) { ?>
	<style type="text/css"><?= $template->getCss() ?></style>
	<style type="text/css"><?= $template->getLess() ?></style>
	<script><?= $template->getJs() ?></script>
<?php } ?>
	<?php if (FileHelper::isFile($directory.'/parts/custom/header.end.php')) include($directory.'/parts/custom/header.end.php') ?>
	<?include($directory.'/parts/assets.php');?>
<? if ($remoteSR) { ?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TPB2R6S');</script>
<!-- End Google Tag Manager -->
<? } ?>
</head>
<body class="public intec-adaptive">
	<?php if (FileHelper::isFile($directory.'/parts/custom/body.start.php')) include($directory.'/parts/custom/body.start.php') ?>
	<?php $APPLICATION->IncludeComponent(
		'intec.universe:system',
		'basket.manager',
		array(
			'BASKET' => 'Y',
			'COMPARE' => 'Y',
			'COMPARE_NAME' => 'compare',
			'CACHE_TYPE' => 'N'
		),
		false,
		array('HIDE_ICONS' => 'Y')
	); ?>
	<?php if (
		$properties->get('base-settings-show') == 'all' ||
		$properties->get('base-settings-show') == 'admin' && $USER->IsAdmin()
	) { ?>
		<?php $APPLICATION->IncludeComponent(
			"intec.universe:system.settings",
			".default",
			array(
				"MODE" => "render",
				"MENU_ROOT_TYPE" => "top",
				"MENU_CHILD_TYPE" => "left",
				"COMPONENT_TEMPLATE" => ".default",
				"VARIABLES_ACTION" => "system-settings-action",
				"VARIABLES_VARIANT" => "variant"
			),
			false,
			array(
				"HIDE_ICONS" => "N"
			)
		); ?>
	<? } ?>
	<?php include($directory.'/parts/'.$part.'/header.php'); ?>
