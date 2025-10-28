<?php

use intec\core\helpers\Html;
use intec\constructor\models\build\layout\Renderer;
use intec\constructor\models\build\layout\renderers\EditorRenderer;

/**
 * @var Renderer $this
 */

$isInEditor = $this instanceof EditorRenderer;
$zones = $this->getLayout()->getZones();

?>
<?php if ($this->getIsRenderAllowed()) { ?>
<div class="intec-template">
    <?= Html::beginTag('div', [
        'class' => [
            'intec-template-layout',
            'intec-content-wrap'
        ],
        'data' => [
            'name' => 'narrow',
            'editor' => $isInEditor ? 'true' : 'false'
        ]
    ]) ?>
        <div class="intec-template-layout-header">
<?php } ?>
            <?php $this->renderZone($zones->get('header')) ?>
<?php if ($this->getIsRenderAllowed()) { ?>
        </div>
        <div class="intec-template-layout-page intec-content">
            <div class="intec-template-layout-page-wrapper intec-content-wrapper">
                <div class="intec-template-layout-content">
<?php } ?>
                    <?php $this->renderZone($zones->get('default')) ?>
<?php if ($this->getIsRenderAllowed()) { ?>
                </div>
            </div>
        </div>
        <div class="intec-template-layout-footer">
<?php } ?>
            <?php $this->renderZone($zones->get('footer')) ?>
<?php if ($this->getIsRenderAllowed()) { ?>
        </div>
    <?= Html::endTag('div') ?>
</div>
<?php } ?>