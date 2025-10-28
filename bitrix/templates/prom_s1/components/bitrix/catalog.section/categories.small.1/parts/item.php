<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var string $sTemplateId
 * @var array $arVisual
 */

?>
<?php return function (&$arItem) use (&$sTemplateId, &$arVisual) {

    $sId = $sTemplateId.'_'.$arItem['ID'];
    $sAreaId = $this->GetEditAreaId($sId);
    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

    $sPicture = $arItem['IMG']['VALUE'];

    if (!empty($sPicture)) {
        $sPicture = CFile::ResizeImageGet(
            $sPicture,
            [
                'width' => 200,
                'height' => 150
            ],
            BX_RESIZE_IMAGE_EXACT
//            BX_RESIZE_IMAGE_PROPORTIONAL
        );

        if (!empty($sPicture))
            $sPicture = $sPicture['src'];
    }

    if (empty($sPicture))
        $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

?>
    <div class="catalog-section-item" id="<?= $sAreaId ?>">
        <?= Html::beginTag('div', [
            'class' => [
                'intec-grid' => [
                    '',
                    'o-vertical',
                    'wrap',
                    'i-h-6',
                    'a-v-start'
                ]
            ]
        ]) ?>
            <?php if ($arItem['IMG']['SHOW']) { ?>
                <div class="intec-grid-item-auto">
                    <?= Html::beginTag('a', [
                        'class' => [
                            'catalog-section-item-picture',
                            'intec-image-effect'
                        ],
                        'href' => '/catalog/'.$arItem['CODE'].'/'
                    ]) ?>
                        <?= Html::img($sPicture, [
                            'srcset' => $arVisual['LAZYLOAD']['USE'] ? 
                                $arVisual['LAZYLOAD']['STUB'] : 
                                $sPicture,

                            'alt' => $arItem['NAME'],
                            'title' => $arItem['NAME'],
                            'loading' => 'lazy',
                            'data-lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                            'data-original-set' => $arVisual['LAZYLOAD']['USE'] ? 
                                $sPicture : null
                        ]) ?>
                    <?= Html::endTag('a') ?>
                </div>
            <?php } ?>
            <div class="intec-grid-item-auto">
                <div class="catalog-section-item-name">
                    <?= Html::tag('a', $arItem['NAME'], [
                        'class' => 'intec-cl-text-hover',
                        'alt' => $arItem['NAME'],
                        'title' => $arItem['NAME'],
                        'href' => '/catalog/'.$arItem['CODE'].'/'
                    ]) ?>
                </div>
            </div>
        <?= Html::endTag('div') ?>
    </div>
<?php } ?>