<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

$bPartLeftShow = $arResult['MENU']['MAIN']['SHOW'];
$bPartRightShow =
    $arResult['SEARCH']['SHOW'] ||
    $arResult['EMAIL']['SHOW'] ||
    $arResult['PHONE']['SHOW'] ||
    $arResult['ADDRESS']['SHOW'] ||
    $arResult['FORMS']['CALL']['SHOW'] ||
    $arResult['ICONS']['SHOW'];

$bPartsShow =
    $bPartLeftShow ||
    $bPartRightShow;

$bPanelShow =
    $arResult['COPYRIGHT']['SHOW'] ||
    $arResult['SOCIAL']['SHOW'] ||
    $arResult['LOGOTYPE']['SHOW'];

?>
<div class="widget-view-4 intec-content-wrap">
    <div class="widget-wrapper intec-content">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <?php if ($bPartsShow) { ?>
                <div class="<?= Html::cssClassFromArray([
                    'widget-parts',
                    'intec-grid' => [
                        '',
                        'nowrap',
                        'a-h-start',
                        'a-v-start',
                        '768-wrap'
                    ]
                ]) ?>">
                    <div class="widget-part widget-part-left intec-grid-item intec-grid-item-768-1">
                        <?php if ($bPartLeftShow) { ?>
                            <?php include(__DIR__.'/../../parts/menu/main.columns.1.php') ?>
                        <?php } ?>
                    </div>
                    <?php if ($bPartRightShow) { ?>
                        <div class="widget-part widget-part-right intec-grid-item-auto intec-grid-item-768-1">
                            <?php if ($arResult['SEARCH']['SHOW']) { ?>
                                <div class="widget-search">
                                    <?php
                                        $arSearch = [
                                            'TEMPLATE' => 'input.3'
                                        ];

                                        include(__DIR__.'/../../parts/search.php');
                                    ?>
                                </div>
                            <?php } ?>
                            <?php if (
                                $arResult['PHONE']['SHOW'] ||
                                $arResult['EMAIL']['SHOW'] ||
                                $arResult['FORMS']['CALL']['SHOW'] ||
                                $arResult['ADDRESS']['SHOW']
                            ) { ?>
                                <div class="widget-part-items intec-grid intec-grid-wrap intec-grid-a-h-start intec-grid-a-v-center">
                                    <?php if ($arResult['EMAIL']['SHOW']) { ?>
                                        <div class="widget-part-item widget-email intec-grid-item-2 intec-grid-item-550-1">
                                            <span class="widget-part-item-icon">
                                                <i class="intec-ui-icon intec-ui-icon-mail-1"></i>
                                            </span>
                                            <a class="widget-part-item-text" href="mailto:<?= $arResult['EMAIL']['VALUE'] ?>">
                                                <?= $arResult['EMAIL']['VALUE'] ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <?php if ($arResult['PHONE']['SHOW']) { ?>
                                        <div class="widget-part-item widget-phone intec-grid-item-2 intec-grid-item-550-1">
                                            <span class="widget-part-item-icon">
                                                <i class="intec-ui-icon intec-ui-icon-phone-1"></i>
                                            </span>
                                            <a class="widget-part-item-text" href="tel:<?= $arResult['PHONE']['VALUE']['LINK'] ?>">
                                                <?= $arResult['PHONE']['VALUE']['DISPLAY'] ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <?php if ($arResult['ADDRESS']['SHOW']) { ?>
                                        <div class="widget-part-item widget-address intec-grid-item-2 intec-grid-item-550-1">
                                            <span class="widget-part-item-icon">
                                                <i class="intec-ui-icon intec-ui-icon-location-1"></i>
                                            </span>
                                            <span class="widget-part-item-text">
                                                <?= $arResult['ADDRESS']['VALUE'] ?>
                                            </span>
                                        </div>
                                    <?php } ?>
                                    <?php if ($arResult['FORMS']['CALL']['SHOW']) { ?>
                                        <div class="widget-part-item widget-form intec-grid-item-2 intec-grid-item-550-1">
                                            <?= Html::tag('div', Loc::getMessage('C_MAIN_FOOTER_TEMPLATE_1_VIEW_4_FORMS_CALL_BUTTON'), [
                                                'class' => [
                                                    'intec-ui' => [
                                                        '',
                                                        'control-button',
                                                        'mod-round-3',
                                                        'scheme-current',
                                                        'size-3'
                                                    ]
                                                ],
                                                'data' => [
                                                    'action' => 'forms.call.open'
                                                ]
                                            ]) ?>
                                            <?php include(__DIR__.'/../../parts/forms/call.php') ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <?php if ($arResult['ICONS']['SHOW']) { ?>
                                <div class="widget-icons intec-grid intec-grid-wrap intec-grid-a-h-center intec-grid-a-v-center intec-grid-i-8">
                                    <?php foreach ($arResult['ICONS']['ITEMS'] as $arItem) { ?>
                                    <?php if (!$arItem['SHOW']) continue ?>
                                        <div class="widget-icon intec-grid-item-auto" data-icon="<?= StringHelper::toLowerCase($arItem['CODE']) ?>">
                                            <div class="widget-icon-image"></div>
                                        </div>
                                    <?php } ?>
                                    <div><br><br>
            <div class="foot-dops">
                <div class="foot-dops__download">
                    <!-- <a href="/files/price_okgbi.xlsx" class="footer-info-mail" target="_blank">Скачать прайс-лист</a><br> -->

                    <a href="/price/OKGBI-Price-PB-3PB-PNO.pdf" class="footer-info-mail" target="_blank" id="price-pustotnie">Скачать прайс-лист на пустотные плиты</a><br><br>
                    <a href="/files/Catalog_BREND_pages.pdf" class="footer-info-mail" target="_blank">Скачать краткий каталог</a><br><br>
                    <a href="/files/Catalog_Distributive.pdf" class="footer-info-mail" target="_blank">Скачать полный каталог</a>
                </div>
                <div class="foot-dops__reqs">
                    ООО "Промышленная Компания "Очаковский Комбинат ЖБИ"<br>
                    ИНН 6722028140<br>
                    КПП 772901001<br>
                    ОГРН 1126722001141<br>
                    
                    Директор Дворецкий Илья Борисович
                </div>
            </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <div id="bx-composite-banner"></div>
            <?php if ($bPanelShow) { ?>
                <div class="widget-panel">
                    <div class="<?= Html::cssClassFromArray([
                        'widget-panel-items',
                        'intec-grid' => [
                            '',
                            'nowrap',
                            'a-h-start',
                            'a-v-center',
                            '1000-wrap'
                        ]
                    ]) ?>">
                        <?php if ($arResult['COPYRIGHT']['SHOW']) { ?>
                            <div class="widget-panel-item intec-grid-item intec-grid-item-768-1">
                                <div class="widget-copyright">
                                    <?= $arResult['COPYRIGHT']['VALUE'] ?>
                                    <?php if ($arResult['COPYRIGHT']['SHOW_SUBDOMAINS']) { ?>
                                        <div class="subdomains-footer-copyright"><?= $arResult['COPYRIGHT']['VALUE_SUBDOMAINS'] ?></div>
                                    <?php } ?>
<p><a href="/about/consent/">Политика конфиденциальности обработки персональных данных</a></p>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="widget-panel-item widget-panel-item-empty intec-grid-item intec-grid-item-768-1"></div>
                        <?php } ?>
                        <?php if ($arResult['SOCIAL']['SHOW']) { ?>
                            <div class="widget-panel-item intec-grid-item-auto intec-grid-item-shrink-1 intec-grid-item-768-1">
                                <!--noindex-->
                                <div class="widget-social">
                                    <div class="widget-social-items">
                                        <?php foreach ($arResult['SOCIAL']['ITEMS'] as $arItem) { ?>
                                            <?php if (!$arItem['SHOW']) continue ?>
                                            <div class="widget-social-item">
                                                <a rel="nofollow" href="<?= $arItem['LINK'] ?>" target="_blank" class="widget-social-item-icon  <?= $arItem['ICON'] ?>"></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!--/noindex-->
                            </div>
                        <?php } ?>
                        <?php if ($arResult['LOGOTYPE']['SHOW']) { ?>
                            <div class="widget-panel-item intec-grid-item intec-grid-item-768-1">
                                <div class="widget-logotype">
                                    <a href="<?= $arResult['LOGOTYPE']['LINK'] ?>" class="widget-logotype-wrapper">
                                        <?php include(__DIR__.'/../../parts/logotype.php') ?>
                                    </a>
                                </div>
                        <?php } else { ?>
                            <div class="widget-panel-item widget-panel-item-empty intec-grid-item intec-grid-item-768-1"></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="widget-notes">
                    Обращаем ваше внимание на то, что торговые предложения на интернет-сайте показывают возможный вариант исполнения и носят исключительно информационный характер и ни при каких условиях не является публичной офертой, определяемой положениями Статьи 437 (2) Гражданского кодекса Российской Федерации. Перед оплатой уточняйте необходимую информацию о продукте у менеджера.
                </div>
            <?php } ?>
        </div>
    </div>
</div>
