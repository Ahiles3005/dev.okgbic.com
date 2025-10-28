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
<?php if ($arResult['SUBDOMAINS']) { ?>
<div class="widget-panel-item widget-panel-item-city">
  <div class="widget-panel-item-wrapper intec-grid intec-grid-a-v-center">
    <div class="widget-panel-item-icon intec-grid-item-auto glyph-icon-location_2 intec-cl-text"></div>
    <span class="widget-panel-item-text intec-grid-item-auto" data-toggle="modal" data-target="#cities" data-backdrop="1"><?= $arResult['CITY'] ?></span>
  </div>
</div>
<?php } ?>