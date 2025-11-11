<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php




use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('intec.core')) {
    echo Loc::getMessage('template.errors.module', ['#MODULE#' => 'intec.core']);
    die();
}

use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;



/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

Loc::loadMessages(__FILE__);

$this->setFrameMode(true);


$arParams = ArrayHelper::merge([
    'ADDRESS_SHOW' => 'N',
    'ADDRESS' => null,
    'PHONES_SHOW' => 'N',
    'PHONES' => null,
    'EMAIL_SHOW' => 'N',
    'EMAIL' => null,
    'LOGOTYPE_SHOW' => 'N',
    'LOGOTYPE' => null,
    'LOGOTYPE_LINK' => null,
    'REGIONALITY_USE' => 'N',
    'AUTHORIZATION_SHOW' => 'N',
    'PROFILE_URL' => null,
    'SOCIAL_SHOW' => 'N',
    'SOCIAL_VK' => null,
    'SOCIAL_INSTAGRAM' => null,
    'SOCIAL_FACEBOOK' => null,
    'SOCIAL_YOUTUBE' => null,
    'BORDER_SHOW' => 'Y',
    'INFORMATION_VIEW' => 'view.1'
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/'
];

$arAddress = [
    'SHOW' => $arParams['ADDRESS_SHOW'] === 'Y',
    'VALUE' => $arParams['ADDRESS']
];

if (empty($arAddress['VALUE']))
    $arAddress['SHOW'] = false;

$arCity = [
    'SHOW' => $arParams['CITY_SHOW'] === 'Y',
    'VALUE' => $arParams['CITY']
];

if (empty($arCity['VALUE']))
    $arCity['SHOW'] = false;

$arAuthorization = [
    'SHOW' => $arParams['AUTHORIZATION_SHOW'] === 'Y',
    'VALUE' => $arParams['PROFILE_URL']
];

if (empty($arAuthorization['VALUE']))
    $arAuthorization['SHOW'] = false;

$arPhones = [
    'SHOW' => $arParams['PHONES_SHOW'] === 'Y',
    'VALUES' => $arParams['PHONES'],
    'SELECTED' => null
];

if (empty($arPhones['VALUES'])) {
    $arPhones['SHOW'] = false;
} else {
    if (Type::isArray($arPhones['VALUES'])) {
        $arPhones['VALUES'] = array_filter($arParams['PHONES']);

        foreach($arPhones['VALUES'] as $arPhone) {
            $arValues[] = [
                'DISPLAY' => $arPhone,
                'LINK' => StringHelper::replace($arPhone, [
                    '(' => '',
                    ')' => '',
                    ' ' => '',
                    '-' => ''
                ])
            ];
        }
        $arPhones['SELECTED'] = ArrayHelper::shift($arValues);

        $arPhones['VALUES'] = $arValues;
    } else {
        $arPhones['SELECTED'] = [
            'DISPLAY' => $arPhones['VALUES'],
            'LINK' => StringHelper::replace($arPhones['VALUES'], [
                '(' => '',
                ')' => '',
                ' ' => '',
                '-' => ''
            ])
        ];

        $arPhones['VALUES'] = [];
    }
}

$arEmail = [
    'SHOW' => $arParams['EMAIL_SHOW'] === 'Y',
    'VALUE' => $arParams['EMAIL']
];

if (empty($arEmail['VALUE']))
    $arEmail['SHOW'] = false;

$arSocial = [
    'SHOW' => $arParams['SOCIAL_SHOW'] === 'Y',
    'ITEMS' => []
];

$bSocialShow = false;

foreach ([
 'VK',
 'INSTAGRAM',
 'FACEBOOK',
 'YOUTUBE'
] as $sSocial) {
    $sValue = ArrayHelper::getValue($arParams, 'SOCIAL_'.$sSocial);
    $arSocialItem = [
        'SHOW' => !empty($sValue),
        'VALUE' => $sValue
    ];

    $bSocialShow = $bSocialShow || $arSocialItem['SHOW'];
    $arSocial['ITEMS'][$sSocial] = $arSocialItem;
}

$arSocial['SHOW'] = $arSocial['SHOW'] && $bSocialShow;

$arLogotype = [
    'SHOW' => $arParams['LOGOTYPE_SHOW'] === 'Y',
    'PATH' => $arParams['LOGOTYPE'],
    'LINK' => $arParams['LOGOTYPE_LINK']
];

if (empty($arLogotype['PATH'])) {
    $arLogotype['SHOW'] = false;
} else {
    $arLogotype['PATH'] = StringHelper::replaceMacros(
        $arLogotype['PATH'],
        $arMacros
    );
}

$sPrefix = 'SEARCH_';
$arSearchParams = [];

$sSearchType = 'popup';
$sSearchTemplate = 'popup.1';

foreach ($arParams as $sKey => $sValue)
    if (StringHelper::startsWith($sKey, $sPrefix)) {
        $sKey = StringHelper::cut($sKey, StringHelper::length($sPrefix));
        if ($sKey === 'TYPE') {
            if ($sValue === 'page')
                $sSearchTemplate = 'input.1';

            $sSearchType = $sValue;
            continue;
        }
        $arSearchParams[$sKey] = $sValue;
    }

$arInformationView = $arParams['INFORMATION_VIEW'];

$arRegionality = [
    'USE' => $arParams['REGIONALITY_USE'] === 'Y' && Loader::includeModule('intec.regionality')
];
?>
<?php $fRenderItem = function ($arItem, $iLevel, $arParent = null, $bActive = false) use (&$fRenderItem, &$arParams) {
    $sName = ArrayHelper::getValue($arItem, 'TEXT');
    $sLink = ArrayHelper::getValue($arItem, 'LINK');
    $arChildren = ArrayHelper::getValue($arItem, 'ITEMS');

    $bSelected = ArrayHelper::getValue($arItem, 'SELECTED');
    $bSelected = Type::toBoolean($bSelected);
    $bHasChildren = !empty($arChildren) && ($iLevel < $arParams['MAX_LEVEL']-1);

    $bActive = $arItem['ACTIVE'];
    $sTag = $bHasChildren || $bActive ? 'div' : 'a';
?>
    <?= Html::beginTag('div', [
        'class' => Html::cssClassFromArray([
            'menu-item' => [
                '' => true,
                'level-'.$iLevel => true,
                'selected' => $bSelected
            ]
        ], true),
        'data' => [
            'role' => 'item',
            'level' => $iLevel,
            'expanded' => 'false',
            'current' => 'false'
        ]
    ]) ?>
        <div class="menu-item-wrapper">
            <?= Html::beginTag($sTag, [
                'class' => Html::cssClassFromArray([
                    'menu-item-content' => true,
                    'intec-cl' => [
                        'text' => $bSelected,
                        'text-hover' => true
                    ]
                ], true),
                'href' => $sTag == 'a' ? $sLink : null,
                'data' => [
                    'action' => $bHasChildren ? 'menu.item.open' : 'menu.close'
                ]
            ]) ?>
                <div class="intec-grid intec-grid-nowrap intec-grid-i-h-10 intec-grid-a-v-center">
                    <div class="menu-item-text-wrap intec-grid-item intec-grid-item-shrink-1">
                        <div class="menu-item-text">
                            <?= $sName ?>
                        </div>
                    </div>
                    <?php if ($bHasChildren) { ?>
                        <div class="menu-item-icon-wrap intec-grid-item-auto">
                            <div class="menu-item-icon">
                                <i class="glyph-icon-right"></i>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?= Html::endTag($sTag) ?>
            <?php if ($bHasChildren) {

                $sChildTag = $bActive ? 'div' : 'a';

            ?>
                <div class="menu-item-items" data-role="items">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'menu-item' => [
                                '',
                                'level-'.($iLevel + 1),
                                'button'
                            ]
                        ],
                        'data' => [
                            'level' => $iLevel + 1
                        ]
                    ]) ?>
                        <div class="menu-item-wrapper">
                            <div class="menu-item-content intec-cl-text-hover" data-action="menu.item.close">
                                <div class="intec-grid intec-grid-nowrap intec-grid-i-h-10 intec-grid-a-v-center">
                                    <div class="menu-item-icon-wrap intec-grid-item-auto">
                                        <div class="menu-item-icon">
                                            <i class="glyph-icon-left"></i>
                                        </div>
                                    </div>
                                    <div class="menu-item-text-wrap intec-grid-item intec-grid-item-shrink-1">
                                        <div class="menu-item-text">
                                            <?= Loc::getMessage('C_MENU_MOBILE_2_BACK') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?= Html::endTag('div') ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'menu-item' => [
                                '',
                                'level-'.($iLevel + 1),
                                'title'
                            ]
                        ],
                        'data' => [
                            'level' => $iLevel + 1
                        ]
                    ]) ?>
                        <div class="menu-item-wrapper">
                            <?= Html::tag($sChildTag, $sName, [
                                'class' => 'menu-item-content',
                                'href' => $sChildTag == 'a' ? $sLink : null,
                                'data' => [
                                    'action' => 'menu.close'
                                ]
                            ]) ?>
                        </div>
                    <?= Html::endTag('div') ?>
                    <?php foreach ($arChildren as $arChild) {
                        $fRenderItem($arChild, $iLevel + 1, $arItem, $bActive);
                    } ?>
                </div>
            <?php } ?>
        </div>
    <?= Html::endTag('div') ?>
<?php } ?>
<?php if (!empty($arResult)) { ?>
        <div>
    <?php foreach ($arResult as $arItem) {
        $fRenderItem($arItem, 0);
    } ?>
        </div>
<?php } ?>


