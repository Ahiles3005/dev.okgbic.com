<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<? $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/fonts/localfonts.css", true); ?>

<?php

use intec\core\helpers\FileHelper;

global $APPLICATION;
global $USER;
global $directory;
global $properties;
global $template;
global $part;

if (empty($template))
    return;

//pre($part);
?>
        <?php include($directory.'/parts/'.$part.'/footer.php'); ?>
        <?php if (FileHelper::isFile($directory.'/parts/custom/body.end.php')) include($directory.'/parts/custom/body.end.php') ?>
</div>


<!-- /Yandex.Metrika counter -->






    </body>
</html>
<?php if (FileHelper::isFile($directory.'/parts/custom/end.php')) include($directory.'/parts/custom/end.php') ?>
