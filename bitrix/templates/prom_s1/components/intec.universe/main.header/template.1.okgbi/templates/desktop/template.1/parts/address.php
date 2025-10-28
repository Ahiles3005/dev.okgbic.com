<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\component\InnerTemplate;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

?>
<?php if ($arResult['ADDRESS']['SHOW']['DESKTOP']) { ?>
    <div class="widget-panel-item widget-panel-item-address">
        <div class="widget-panel-item-wrapper intec-grid intec-grid-a-v-center">
            <div class="widget-panel-item-icon intec-grid-item-auto intec-ui-icon intec-ui-icon-location-1 intec-cl-text"></div>
            <a href="/contacts/" rel="nofollow" class="widget-panel-item-text intec-grid-item-auto">
                <?= $arResult['ADDRESS']['VALUE'] ?>
            </a>
        </div>
    </div>
<?php } ?>