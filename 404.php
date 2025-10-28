<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");

?><div class="intec-content">
	<div class="intec-content-wrapper intec-404">
		<div class="row">
			<div class="col-md-6 xs-12">
				<div class="image-404">
 <img src="<?= SITE_DIR ?>images/404.png">
				</div>
			</div>
			<div class="col-md-6 xs-12">
				<div class="text-404">
					<div class="header-text">
						 Ошибка 404
					</div>
					<div class="header2-text">
						 Страница не найдена
					</div>
					<div class="text">
						 Неправильно набран адрес или такой страницы не существует <br>
					<br>
					</div>
					<div>
 <a href="<?=SITE_DIR?>" class="intec-button intec-button-cl-common intec-button-md "> Перейти на главную </a> <br>
 <br>
					</div>
				</div>
			</div>
		</div>
 <br>
 <br>
<?$APPLICATION->IncludeComponent(
	"intec.universe:main.sections", 
	"template.2", 
	array(
		"CACHE_TIME" => "0",
		"CACHE_TYPE" => "A",
		"DEPTH" => "2",
		"DESCRIPTION_SHOW" => "N",
		"ELEMENTS_COUNT" => "10",
		"HEADER_SHOW" => "Y",
		"IBLOCK_ID" => "138",
		"IBLOCK_TYPE" => "1s_catalog_okgbi",
		"LAZYLOAD_USE" => "N",
		"LINE_COUNT" => "3",
		"LIST_PAGE_URL" => "",
		"ORDER_BY" => "ASC",
		"PICTURE_SIZE" => "small",
		"QUANTITY" => "N",
		"SECTIONS" => array(
			0 => "",
			1 => "",
		),
		"SECTIONS_MODE" => "id",
		"SECTION_URL" => "",
		"SETTINGS_USE" => "N",
		"SORT_BY" => "SORT",
		"SUB_SECTIONS_SHOW" => "Y",
		"COMPONENT_TEMPLATE" => "template.2",
		"HEADER_POSITION" => "center",
		"HEADER_TEXT" => "Каталог продукции",
		"SUB_SECTIONS_MAX" => "10"
	),
	false
);?>
	</div>
</div>
 <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>