<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var bool $bSkuDynamic
 */

?>
<?php $vGallery = function (&$arItem, $bOffer = false) use (&$arResult, &$arVisual) { ?>
    <?php if (empty($arItem['GALLERY']) && $bOffer) return ?>
    <?= Html::beginTag('div', [
        'class' => 'catalog-element-gallery',
        'data' => [
            'role' => 'gallery',
            'offer' => $bOffer ? $arItem['ID'] : 'false'
        ]
    ]) ?>
        <?= Html::beginTag('div', [
            'class' => 'catalog-element-gallery-pictures',
            'data' => [
                'role' => 'gallery.pictures',
                'action' => $arVisual['GALLERY']['ACTION'],
                'zoom' => $arVisual['GALLERY']['ZOOM'] ? 'true' : 'false'
            ]
        ]) ?>
            <?= Html::beginTag('div', [
                'class' => Html::cssClassFromArray([
                    'catalog-element-gallery-pictures-slider' => true,
                    'owl-carousel' => count($arItem['GALLERY']) > 1
                ], true),
                'data-role' => count($arItem['GALLERY']) > 1 ? 'gallery.pictures.slider' : null
            ]) ?>
                <?php if (!empty($arItem['GALLERY'])) { ?>
                    <?php foreach ($arItem['GALLERY'] as $kPicture => $arPicture) {

                        $sPicture[$kPicture] = CFile::ResizeImageGet($arPicture, [
                            'width' => 480,
                            'height' => 480
                        ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                    ?>
                        <div class="catalog-element-gallery-pictures-slider-item" data-role="gallery.pictures.item">
                            <?= Html::beginTag($arVisual['GALLERY']['ACTION'] === 'source' ? 'a' : 'div', [
                                'class' => 'catalog-element-gallery-pictures-slider-item-picture',
                                'href' => $arVisual['GALLERY']['ACTION'] === 'source' ? $arPicture['SRC'] : null,
                                'target' => $arVisual['GALLERY']['ACTION'] === 'source' ? '_blank' : null,
//                                'itemprop' => 'image',
                                'data-role' => 'gallery.pictures.item.picture',
                                'data-src' => $arVisual['GALLERY']['ACTION'] === 'popup' || $arVisual['GALLERY']['ZOOM'] ? $arPicture['SRC'] : null
                            ]) ?>
                                <?= Html::img(
//					$arVisual['LAZYLOAD']['USE'] && $kPicture ? 
//					$arVisual['LAZYLOAD']['STUB'] : 
//					$sPicture[$kPicture]['src'] 
					$arPicture['SRC']
				, [

                                    'srcset' => $arVisual['LAZYLOAD']['USE'] && $kPicture ? 
					$arVisual['LAZYLOAD']['STUB'] : 
					$sPicture[$kPicture]['src'],

                                    'alt' => $arResult['NAME'],
                                    'title' => $arResult['NAME'],
//                                    'itemprop' => 'image',
                                    'loading' => $arVisual['LAZYLOAD']['USE'] && $kPicture ? 'lazy' : null,
                                    'data-lazyload-use' => $arVisual['LAZYLOAD']['USE'] && $kPicture ? 'true' : 'false',
                                    'data-original-set' => $arVisual['LAZYLOAD']['USE'] && $kPicture ? 
					$sPicture[$kPicture]['src'] : null
//					$arPicture['SRC'] : null
                                ]) ?>
                                <meta itemprop="image" content="<?= 
//					$sPicture[$kPicture]['src'] 
					$arPicture['SRC']
				?>"/>
                                <div class="intec-aligner"></div>
                            <?= Html::endTag($arVisual['GALLERY']['ACTION'] === 'source' ? 'a' : 'div') ?>
                        </div>
                    <?php } ?>
                    <?php unset($arPicture, 
//			$sPicture, 
			$kPicture
                    ) ?>
                <?php } else { ?>
                    <div class="catalog-element-gallery-pictures-slider-item">
                        <div class="catalog-element-gallery-pictures-slider-item-picture">
                            <?= Html::img($arVisual['LAZYLOAD']['USE'] && 0 ? $arVisual['LAZYLOAD']['STUB'] : SITE_TEMPLATE_PATH.'/images/picture.missing.png', [
                                'alt' => $arResult['NAME'],
                                'title' => $arResult['NAME'],
                                'loading' => 'lazy',
//                                'itemprop' => 'image',
                                'data-lazyload-use' => $arVisual['LAZYLOAD']['USE'] && 0 ? 'true' : 'false',
                                'data-original' => $arVisual['LAZYLOAD']['USE'] && 0 ? SITE_TEMPLATE_PATH.'/images/picture.missing.png' : null
                            ]) ?>
                            <div class="intec-aligner"></div>
                        </div>
                    </div>
                <?php } ?>
            <?= Html::endTag('div') ?>
        <?= Html::endTag('div') ?>
        <?php if ($arVisual['GALLERY']['PREVIEW'] && count($arItem['GALLERY']) > 1) { ?>
            <div class="catalog-element-gallery-preview" data-role="gallery.preview">
                <div class="catalog-element-gallery-preview-slider owl-carousel" data-role="gallery.preview.slider">
                    <?php $bFirst = true ?>
                    <?php foreach ($arItem['GALLERY'] as $kPicture => $arPicture) {
/*
                        $sPicture = CFile::ResizeImageGet($arPicture, [
                            'width' => 64,
                            'height' => 64
                        ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT)
*/
                    ?>
                        <?= Html::beginTag('div', [
                            'class' => 'catalog-element-gallery-preview-slider-item',
                            'data' => [
                                'role' => 'gallery.preview.slider.item',
                                'active' => $bFirst ? 'true' : 'false'
                            ]
                        ]) ?>
                            <div class="catalog-element-gallery-preview-slider-item-picture">
                                <?= Html::img($arPicture['SRC']
				, [
                                    'srcset' => $arVisual['LAZYLOAD']['USE'] ? 
					$arVisual['LAZYLOAD']['STUB'] : 
					$sPicture[$kPicture]['src'],
                                    'alt' => $arResult['NAME'],
                                    'title' => $arResult['NAME'],
                                    'loading' => 'lazy',
                                    'data-lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'data-original-set' => $arVisual['LAZYLOAD']['USE'] ? $sPicture[$kPicture]['src'] : null
//                                    'data-original' => $arVisual['LAZYLOAD']['USE'] ? $arPicture['SRC'] : null
                                ]) ?>
                                <div class="intec-aligner"></div>
                            </div>
                        <?= Html::endTag('div') ?>
                        <?php if ($bFirst) $bFirst = false ?>
                    <?php } ?>
                </div>
                <div class="catalog-element-gallery-preview-navigation" data-role="gallery.preview.navigation"></div>
            </div>
        <?php } ?>
    <?= Html::endTag('div') ?>
<?php } ?>
<div class="catalog-element-gallery-container catalog-element-main-block">
    <?php $vGallery($arResult);

    if ($bSkuDynamic) {
        foreach ($arResult['OFFERS'] as &$arOffer)
            $vGallery($arOffer, true);

        unset($arOffer);
    } ?>
</div>
<?php unset($vGallery) ?>