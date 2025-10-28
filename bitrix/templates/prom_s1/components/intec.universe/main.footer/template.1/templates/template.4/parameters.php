<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 * @var string $siteTemplate
 */

$arReturn = [];
$arReturn['ICONS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_4_ICONS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ALFABANK' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_4_ICONS_ALFABANK'),
        'SBERBANK' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_4_ICONS_SBERBANK'),
        'VTBBANK' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_4_ICONS_VTBBANK'),
        'GAZPROMBANK' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_4_ICONS_GAZPROMBANK'),
        'QIWI' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_4_ICONS_QIWI'),
        'YANDEXMONEY' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_4_ICONS_YANDEXMONEY'),
        'MIR' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_4_ICONS_MIR'),
        'VISA' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_4_ICONS_VISA'),
        'MASTERCARD' => Loc::getMessage('C_FOOTER_TEMPLATE_1_VIEW_4_ICONS_MASTERCARD')
    ],
    'MULTIPLE' => 'Y',
    'ADDITIONAL_VALUES' => 'Y'
];

return $arReturn;