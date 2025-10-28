<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;

$arResult['CONSENT'] = [
    'SHOW' => false,
    'URL' => null
];

if (!Loader::includeModule('intec.core'))
    return;

if (!Loader::includeModule('intec.constructor') && !Loader::includeModule('intec.constructorlite'))
    return;

$arConsent = [
    'SHOW' => false,
    'URL' => ArrayHelper::getValue($arParams, 'CONSENT_URL')
];

$oBuild = Build::getCurrent();

if (!empty($oBuild)) {
    $oPage = $oBuild->getPage();
    $oProperties = $oPage->getProperties();
    $arConsent['SHOW'] = $oProperties->get('base-consent');
}

if (!empty($arConsent['URL'])) {
    $arConsent['URL'] = StringHelper::replaceMacros($arConsent['URL'], [
        'SITE_DIR' => SITE_DIR
    ]);
} else {
    $arConsent['SHOW'] = false;
}

$arResult['CONSENT'] = $arConsent;

foreach ($arResult['QUESTIONS'] as $questionID => &$question) {
   
	if(strstr($question['HTML_CODE'], 'telClass')){
		$question["STRUCTURE"][0]["FIELD_TYPE"] = "tel";
		$question['HTML_CODE'] = str_replace('type="text"', 'type="tel"', $question['HTML_CODE']);
	}
/*     if(strpos('form_text_', $question['HTML_CODE']) === false){
	if($question["STRUCTURE"][0]["FIELD_TYPE"] = "email"){
		$question['HTML_CODE'] = str_replace('type="text"', 'type="email"', $question['HTML_CODE']);
	}

    pre($question);
} */
}