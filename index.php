<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Очаковский завод ЖБИ, okgbi.ru");
$APPLICATION->SetPageProperty("title", "Завод ЖБИ в Москве: купить железобетонные изделия по цене производства. Продажа ЖБ изделий, прайс-лист.");
$APPLICATION->SetPageProperty("description", "Очаковский завод железобетонных изделий (ЖБИ) предлагает купить ЖБ конструкции по цене производителя. Продажа ЖБ изделий собственного производства, доставка ЖБИ продукции по Москве, Владимиру, Туле, Калуге, Твери и Рязани.");

/**
 * @global $APPLICATION
 */

$APPLICATION->SetTitle("Завод ЖБИ в Москве: купить железобетонные изделия по цене производства. Продажа ЖБ изделий, прайс-лист.");
$APPLICATION->SetPageProperty('canonical', ($APPLICATION->IsHTTPS() ? 'https://' : 'http://').SITE_SERVER_NAME.$arResult['SECTION_PAGE_URL']);
?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>
