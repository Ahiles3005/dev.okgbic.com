<?php

use intec\constructor\models\build\layout\Renderer;

/**
 * @var Renderer $this
 */

$zones = $this->getLayout()->getZones();

?>
<?php if ($this->getIsRenderAllowed()) { ?>
<div class="intec-layout intec-layout-wide">
    <div class="intec-layout-part intec-layout-part-header">
<?php } ?>
        <?php $this->renderZone($zones->get('header')) ?>
<?php if ($this->getIsRenderAllowed()) { ?>
    </div>
    <div class="intec-layout-part intec-layout-part-content">
<?php } ?>
        <?php $this->renderZone($zones->get('default')) ?>
<?php if ($this->getIsRenderAllowed()) { ?>
    </div>
    <div class="intec-layout-part intec-layout-part-footer">
<?php } ?>
        <?php $this->renderZone($zones->get('footer')) ?>
<?php if ($this->getIsRenderAllowed()) { ?>
    </div>
</div>
<?php } ?>