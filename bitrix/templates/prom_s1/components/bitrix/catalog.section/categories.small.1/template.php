<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];

$vItem = include(__DIR__.'/parts/item.php');

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-section',
        'c-catalog-section-categories-small-1'
    ],
    'data' => [
        'grid' => $arVisual['COLUMNS'],
        'slider' => 'false'
    ]
]) ?>
    <div class="intec-content intec-content-visible">
        <div class="intec-content-wrapper">
            <div class="catalog-section-content">
                <div class="intec-grid intec-grid-wrap intec-grid-a-v-stretch intec-grid-i-10 catalog-section-items">
                    <?php foreach ($arParams['UF_ASSOC_CATS_ITEMS'] as $arItem) { ?>
                        <?= Html::beginTag('div', [
                            'class' => Html::cssClassFromArray([
                                'catalog-section-item-container' => true,
                                'intec-grid-item' => [
                                    $arVisual['COLUMNS'] => true,
//                                    '1024-3' => $arVisual['COLUMNS'] >= 4,
                                    '768-2' => true,
//                                    '500-1' => true
                                ]
                            ], true)
                        ]) ?>
                            <?php $vItem($arItem) ?>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?= Html::endTag('div') ?>
