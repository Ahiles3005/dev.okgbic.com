<?$TEMPLATE_THEME = (in_array($PRODUCT_TYPE, array('slider', 'line'))) ? 'slider' : '';
$PAGE_ELEMENT_COUNT = (in_array($PRODUCT_TYPE, array('slider', 'line'))) ? '999999' : '15';?>
<?if ($INIT == "Y"):?>
	<div>
<?endif;?>
<?require($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/include/" . SITE_ID ."/product_tab.php");?>
<?if ($INIT == "Y"):?>
	</div>
<?endif;?>