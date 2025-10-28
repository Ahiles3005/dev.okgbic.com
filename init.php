<?
define('PRODUCT_DEFAULT_RATING', 3);
define("BX_PULL_SKIP_INIT", true);

header('Last-Modified: '.gmdate('D, d M Y H:i:s'). ' GMT');

include_once($_SERVER['DOCUMENT_ROOT']."/local/php_interface/subdomains/include/subdomains_functions.php");
AddEventHandler('main', 'OnEpilog', array('CMainHandlers', 'OnEpilogHandler'));

class CMainHandlers {
    public static function OnEpilogHandler() {
        subdomains_on_epilog();

        if (isset($_GET['PAGEN_1']) && intval($_GET['PAGEN_1'])>0) {
            $title = $GLOBALS['APPLICATION']->GetTitle();
            $meta_description = "Очаковский комбинат ЖБИ предлагает купить ".$title;
            $GLOBALS['APPLICATION']->SetPageProperty('title', $title.'. Страница '.intval($_GET['PAGEN_1']));
            $GLOBALS['APPLICATION']->SetPageProperty('description', $meta_description.'. Страница '.intval($_GET['PAGEN_1']));
        }
    }
}

AddEventHandler("search", "BeforeIndex", "BeforeIndexHandler");

function BeforeIndexHandler($arFields) {
    $arrIblock = array(16); //ID инфоблоков, для которых производить модификацию
    $arDelFields = array("DETAIL_TEXT"); //стандартные поля, которые нужно исключить

    if (CModule::IncludeModule('iblock') && $arFields["MODULE_ID"] == 'iblock'){ 
        if (in_array($arFields["PARAM2"], $arrIblock) && intval($arFields["ITEM_ID"]) > 0){

            $dbElement = CIblockElement::GetByID($arFields["ITEM_ID"] );
            if ($arElement = $dbElement->Fetch()){
                foreach ($arDelFields as $value){
                    if (isset ($arElement[$value] ) && strlen($arElement[$value] ) > 0){
                        $arElement[$value] = strip_tags($arElement[$value]);
                        $arElement[$value] = str_replace(array(' '), " ", $arElement[$value]);
                        $arElement[$value] = preg_replace('|[\s]+|s', ' ', $arElement[$value]);
                        $arFields["BODY"] = str_replace($arElement[$value], "", preg_replace('|[\s]+|s', ' ', $arFields["BODY"]));
                    }
                }
            }
        }

        if ($arFields["PARAM2"] == 138 && intval($arFields["ITEM_ID"]) > 0){ //товары
            $arElement = CIblockElement::GetList([], 
                [
                    'ID' => $arFields["ITEM_ID"],
                    'IBLOCK_ID' => 138,
                    'ACTIVE' => 'Y',
                    'SECTION_GLOBAL_ACTIVE' => 'Y',
                ], false, false, ['ID']
            )->Fetch();
            if (!$arElement){
                unset($arFields["BODY"]);
                unset($arFields["TITLE"]);
            }
        }
    }

    return $arFields;
}

include_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/wsrubi.smtp/classes/general/wsrubismtp.php");

/* Отправка пост запросов в https://mazdata.ru/jbi-site */
$POST_DATA = array(
	"site_name" => "okgbi",
	"contact_name" => "Имя пользователя из формы",
	"contact_email" => "email пользователя из формы",
	"contact_phone" => "телефон пользователя из формы",
	"note_text" => "комментарий из формы, или состав заказа из корзины",
	"utm_referer" => "",
	"utm_term" => "",
	"utm_medium" => "",
	"utm_source" => "",
	"utm_campaign" => ""
);
AddEventHandler('form', 'onBeforeResultAdd', 'my_onBeforeResultAdd'); //Отправка из формы в шапке
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "OnAfterIBlockElementAddHandler"); //Отправка при покуплке в 1 клик
AddEventHandler('main', 'OnBeforeEventSend', "my_OnBeforeEventSend"); //Отправка со страницы контакты
AddEventHandler("sale", "OnOrderAdd", "OnOrderAddHandler"); //Отправка при оформлении заказа
AddEventHandler('form', 'onBeforeResultAdd', 'roistatSendForm'); //roistat
//Заполняем и отправляем массив при отправки формы из шапки
function my_onBeforeResultAdd($WEB_FORM_ID, &$arFields, &$arrVALUES){
	//Массив для отправки
	global $APPLICATION;
	if ($WEB_FORM_ID == 1){
		//Заносим имя и телефон в массив
		$POST_DATA["contact_name"] = $arrVALUES["form_text_1"];
		$POST_DATA["contact_phone"] = $arrVALUES["form_text_2"];
		//Отправляем массив
		SEND_ARRAY($POST_DATA);
	}


}
//roistat
function roistatSendForm($WEB_FORM_ID, &$arFields, &$arrVALUES) {
    $formName = 'Заказать звонок';
    $name = '';
    $phone = '';
    $comment = '';
    $email = '';

    switch ($WEB_FORM_ID) {
        case 1 :
            $formName = 'Узнать цену';
            $name =  $arrVALUES["form_text_1"];
            $phone =  $arrVALUES["form_text_2"];
            $comment = "Товар: {$arrVALUES['form_text_36']}\nАртикул: {$arrVALUES['form_hidden_37']}\nСсылка: {$arrVALUES['form_hidden_38']}";
            break;
        case 2 :
            $name =  $arrVALUES["form_text_3"];
            $phone =  $arrVALUES["form_text_4"];
            break;
        case 3 :
            $formName = 'Обратная связь';
            $name =  $arrVALUES["form_text_5"];
            $phone =  $arrVALUES["form_text_7"];
            $email = $arrVALUES["form_email_8"];
            $comment = $arrVALUES["form_textarea_6"];
            break;
        case 4 :
            $formName = 'Нашли дешевле';
            $comment = '';
            if(!empty($arrVALUES["form_text_31"])){
                $comment .= "Ссылка на более дешевый товар: {$arrVALUES['form_text_31']}\n";
            }

            if(!empty($arrVALUES["form_text_9"])){
                $comment .= "Товар: {$arrVALUES['form_text_9']}\n";
            }

            $name =  $arrVALUES["form_text_10"];
            $phone =  $arrVALUES["form_text_12"];
            $email = $arrVALUES["form_email_11"];
            break;

    }

    sendRoistatLead($formName, $comment, $name, $phone, $email);
}

function sendRoistatLead($formName, $comment = '', $name = '', $phone = '', $email = '', $order_id = '') {
    $roistatData = array(
        'roistat' => isset($_COOKIE['roistat_visit']) ? $_COOKIE['roistat_visit'] : 'nocookie',
        'key'     => 'OThiZTIwMzQ3NDJjZWZlOWVmOWYzNTM2NTFiMjE2NDE6MTgyNDkz', // Ключ для интеграции с CRM, указывается в настройках интеграции с CRM.
        'title'   => 'Заявка с формы ' . $formName, // Название сделки
        'comment' => $comment, // Комментарий к сделке
        'name'    => $name, // Имя клиента
        'email'   => $email, // Email клиента
        'phone'   => $phone, // Номер телефона клиента
        'fields'  => array(
            'referrer' => '{referrer}',
            'landing_page' => '{landingPage}',
            'source' => '{source}',
            'utm_source' => '{utmSource}',
            'utm_medium' => '{utmMedium}',
            'utm_campaign' => '{utmCampaign}',
            'utm_term' => '{utmTerm}',
            'utm_content' => '{utmContent}',
            'city' => '{city}',
            'form' => $formName,
        ),
    );

    if(!empty($order_id)){
        $roistatData['fields']['order_id'] = $order_id;
    }

    curl_get_contents("https://cloud.roistat.com/api/proxy/1.0/leads/add?" . http_build_query($roistatData));
}

function curl_get_contents($url) {
    // Initiate the curl session
    $ch = curl_init();
    // Set the URL
    curl_setopt($ch, CURLOPT_URL, $url);
    // Removes the headers from the output
    curl_setopt($ch, CURLOPT_HEADER, 0);
    // Return the output instead of displaying it directly
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Execute the curl session
    $output = curl_exec($ch);
    // Close the curl session
    curl_close($ch);
    // Return the output as a variable
    return $output;
}
//roistat

function getPositionNoteTemplate($name, $quantity, $price, $link)
{
	return "Товар: " . $name
			." | Количество: " . $quantity
			." | Цена за штуку: " . $price
			." | Ссылка на товар: https://www.okgbi.ru" . $link;
}
if(!function_exists("pre")) {
    function pre($var, $die = false, $all = false)
    {
        global $USER;
        if ($USER->IsAdmin() || $all == true) {
            ?><?mb_internal_encoding('utf-8');?>

            <font style="text-align: left; font-size: 12px">
                <pre><? print_r($var) ?></pre>
            </font><br>
            <?
        }
        if ($die) {
            die;
        }
    }
}
function OnAfterIBlockElementAddHandler(&$arFields){
	if($arFields["ID"] > 0 and $arFields["IBLOCK_ID"] == 11){
		//Заносим имя и телефон в массив
		$POST_DATA["contact_name"] = $arFields["PROPERTY_VALUES"]["NAME"];
		$POST_DATA["contact_phone"] = $arFields["PROPERTY_VALUES"]["PHONE"];
		//Добавляем продукт

		//echo '<pre>'; print_r($_REQUEST); die;

		if(count($arFields["PROPERTY_VALUES"]["PRODUCTS"] > 1)){
			foreach($arFields["PROPERTY_VALUES"]["PRODUCTS"] as $i => $value){
				$res = CIBlockElement::GetByID($value);
				if($ar_res = $res->GetNext()) {
					$POST_DATA["note_text"] .= "\n" . getPositionNoteTemplate($ar_res["NAME"], $arFields["PROPERTY_VALUES"]["PRODUCTS_PARAMS"][$i]['quantity'], $arFields["PROPERTY_VALUES"]["PRODUCTS_PARAMS"][$i]['one_price'], $ar_res['DETAIL_PAGE_URL']);
				}
			}
		}else{
			$product_id = $arFields["PROPERTY_VALUES"]["PRODUCTS"][0];
			$res = CIBlockElement::GetByID($product_id);
			if($ar_res = $res->GetNext()) {
				$POST_DATA["note_text"] = getPositionNoteTemplate($ar_res["NAME"], $arFields["PROPERTY_VALUES"]["PRODUCTS_PARAMS"][0]['quantity'], $arFields["PROPERTY_VALUES"]["PRODUCTS_PARAMS"][$i]['one_price'], $ar_res['DETAIL_PAGE_URL']);
			}
		}
		//Отправляем массив
		SEND_ARRAY($POST_DATA);
	}
}

//Заполняем и отправляем массив на странице контакты
function my_OnBeforeEventSend($arFields, $arTemplate){
	if($arTemplate["ID"] == 7){
		$POST_DATA["contact_name"] = $arFields["AUTHOR"];
		$POST_DATA["contact_email"] = $arFields["AUTHOR_EMAIL"];
		$POST_DATA["note_text"] = $arFields["TEXT"];
		SEND_ARRAY($POST_DATA);
	}
}
//После оформления заказа получаем нужные данные из массива заказа и отправляем
function OnOrderAddHandler($id, $arFields){
	$note_text = "";
	if(count($arFields["BASKET_ITEMS"]) > 1){
		foreach($arFields["BASKET_ITEMS"] as $item){
			$QUANTITY = rtrim($item["QUANTITY"], '0');
			$PRICE = rtrim($item["PRICE"], '0') * $QUANTITY;

			$ElementID = $item["PRODUCT_ID"]; // ID предложения
			$mxResult = CCatalogSku::GetProductInfo($ElementID);
			if (is_array($mxResult)) {
				$PRODUCT_ID = $mxResult['ID']; // ID товара родителя
			} else {
				$PRODUCT_ID = $ElementID; // если не нашло, обычный товар
			}
			$res = CIBlockElement::GetProperty(138, $PRODUCT_ID, array("sort" => "asc"), array("CODE" => "KOD_ATTR_S"));
			if ($ob = $res->GetNext()){
				$KOD = $ob['VALUE'];
			} else {
				$KOD = '';
			}
			$note_text .= "Продукт: " . $item["NAME"] . " Код: " . $KOD . " Кол-во: " . $QUANTITY . " Цена: " . $PRICE . " Ссылка: https://www.okgbi.ru" . $item["DETAIL_PAGE_URL"] . "\n";
		}
	}else{
		$QUANTITY = rtrim($arFields["BASKET_ITEMS"][0]["QUANTITY"], '0');
		$PRICE = rtrim($arFields["PRICE"], '0');

		$ElementID = $arFields["BASKET_ITEMS"][0]["PRODUCT_ID"]; // ID предложения
		$mxResult = CCatalogSku::GetProductInfo($ElementID);
		if (is_array($mxResult)) {
			$PRODUCT_ID = $mxResult['ID']; // ID товара родителя
		} else {
			$PRODUCT_ID = $ElementID; // если не нашло, обычный товар
		}
		$res = CIBlockElement::GetProperty(138, $PRODUCT_ID, array("sort" => "asc"), array("CODE" => "KOD_ATTR_S"));
		if ($ob = $res->GetNext()){
			$KOD = $ob['VALUE'];
		} else {
			$KOD = '';
		}
		$note_text = "Продукт: " . $arFields["BASKET_ITEMS"][0]["NAME"] . " Код: " . $KOD . " Кол-во: " . $QUANTITY . " Цена: " . $PRICE . " Ссылка: https://www.okgbi.ru" . $arFields["BASKET_ITEMS"][0]["DETAIL_PAGE_URL"] . "\n";
	}
	$note_text .= "Ссылка на заказ в панели управления https://www.okgbi.ru/bitrix/admin/sale_order_view.php?ID=".$arFields["ID"]."&filter=Y&set_filter=Y&lang=ru";

	$POST_DATA = array(
		"contact_name" => $arFields["ORDER_PROP"][20],
		"contact_email" => $arFields["ORDER_PROP"][2],
		"contact_phone" => $arFields["ORDER_PROP"][3],
		"note_text" => $note_text
	);


	if(!empty($arFields['PAY_SYSTEM_ID'])){
	    $payment_name = $arFields['PAY_SYSTEM_ID'] ? CSalePaySystem::GetByID($arFields['PAY_SYSTEM_ID'])['NAME'] : '';
        $note_text .= "\nСпособ оплаты: $payment_name\n";
    }

    if(!empty($arFields['DELIVERY_ID'])){
        $delivery_name = $arFields['DELIVERY_ID'] ? CSaleDelivery::GetByID($arFields['DELIVERY_ID'])['NAME'] : '';
        $note_text .= "Способ доставки: $delivery_name\n";
    }

    if(!empty($_REQUEST['ORDER_PROP_7'])){
        $note_text .= "Адрес доставки: {$_REQUEST['ORDER_PROP_7']}\n";
    }

    if(!empty($_REQUEST['ORDER_DESCRIPTION'])){
        $note_text .= "Комментарий: {$_REQUEST['ORDER_DESCRIPTION']}\n";
    }

	sendRoistatLead('Корзина', $note_text, $POST_DATA['contact_name'],$POST_DATA['contact_phone'],$POST_DATA['contact_email'],$arFields["ID"]);

	SEND_ARRAY($POST_DATA);
}

//Функция отправки запроса, в неё нужно передать массив для отправки
function SEND_ARRAY($data){
/*
	$data["site_name"] = "okgbi";
	//Заносим метки в массив если они есть
	global $APPLICATION;
	if($APPLICATION->get_cookie("UTM_REFERER", "BITRIX_SM")) $data["utm_referer"] = $APPLICATION->get_cookie("UTM_REFERER", "BITRIX_SM");
	if($APPLICATION->get_cookie("UTM_TERM", "BITRIX_SM")) $data["utm_term"] = $APPLICATION->get_cookie("UTM_TERM", "BITRIX_SM");
	if($APPLICATION->get_cookie("UTM_MEDIUM", "BITRIX_SM")) $data["utm_medium"] = $APPLICATION->get_cookie("UTM_MEDIUM", "BITRIX_SM");
	if($APPLICATION->get_cookie("UTM_SOURCE", "BITRIX_SM")) $data["utm_source"] = $APPLICATION->get_cookie("UTM_SOURCE", "BITRIX_SM");
	if($APPLICATION->get_cookie("UTM_CAMPAIGN", "BITRIX_SM")) $data["utm_campaign"] = $APPLICATION->get_cookie("UTM_CAMPAIGN", "BITRIX_SM");
	if(isset($_REQUEST['ClientID'])) $data["yandex_cid"] = $_REQUEST['ClientID'];

	$fields = "";
	foreach($data as $key => $value){
		$fields .= $key ."=" . $value . "&";
	}
	$fields_string = substr($fields, 0, -1);

	//
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://mazdata.ru/jbi-site"); //адрес отправки
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec($ch);
	curl_close ($ch);
*/
}
/*
AddEventHandler("catalog", "OnPriceDelete", "DeleteCatalogPrice");
function DeleteCatalogPrice($ID, $arFields=array()){
	file_put_contents($l_path, "\nDelete " . date('Y-m-d H:i:s ') . print_r($ID, 1), FILE_APPEND);
	file_put_contents($l_path, "\nDelete " . date('Y-m-d H:i:s ') . print_r($arFields, 1), FILE_APPEND);
}
*/

AddEventHandler("catalog", "OnSuccessCatalogImport1C",  "UpdatePricesOnSuccessCatalogImport1C");
function UpdatePricesOnSuccessCatalogImport1C($arPropertyValues, $ABS_FILE_NAME){
	$res = CPrice::GetList(
		array(),
		array()
	);
	$i = 0;
	while ($arr = $res->Fetch()) {
//		echo '<pre>'.++$i.': '.print_r($arr, 1).'</pre>';
		UpdateCatalogPrice($arr['ID'], $arr);
	}

	$l_path = realpath(dirname(__FILE__)).'/UpdatePricesOnSuccessCatalogImport1C.log';
	empty($l_path) ? '' : file_put_contents($l_path, "\n".date('Y-m-d H:i:s ') . 'UpdatePricesOnSuccessCatalogImport1C - ' . $ABS_FILE_NAME, FILE_APPEND);
}

//AddEventHandler("catalog", "OnPriceAdd", "UpdateCatalogPrice");
//AddEventHandler("catalog", "OnPriceUpdate", "UpdateCatalogPrice");
function UpdateCatalogPrice($ID, $arFields){
	$price_ids = array(3, 8);
	$price_base_id = 19; //розничная
	$price_spec_id = 14; //Цена на сайте от

//	$l_path = realpath(dirname(__FILE__)).'/test.log';
	empty($l_path) ? '' : file_put_contents($l_path, "\n" . date('Y-m-d H:i:s ') . print_r($ID, 1), FILE_APPEND);
	empty($l_path) ? '' : file_put_contents($l_path, "\n" . date('Y-m-d H:i:s ') . print_r($arFields, 1), FILE_APPEND);

	if($arFields['CATALOG_GROUP_ID'] != $price_spec_id && !in_array($arFields['CATALOG_GROUP_ID'], $price_ids)) {
		return;
	}

	$res = CPrice::GetList(
		array(),
		array("PRODUCT_ID" => $arFields['PRODUCT_ID'])
	);
	$price = 0;
	$arPriceId = array();
	while ($arr = $res->Fetch()) {
		empty($l_path) ? '' : file_put_contents($l_path, "\n" . date('Y-m-d H:i:s ') . print_r($arr, 1), FILE_APPEND);
		if($arr['CATALOG_GROUP_ID'] == $price_spec_id) {
			if(is_numeric($arr['PRICE']) && $arr['PRICE'] > 0 && $arr['CURRENCY'] == 'RUB') {
				$price_spec = $arr['PRICE'];
			}
		} elseif($arr['CATALOG_GROUP_ID'] == $price_base_id) {
			$price_base = $arr['PRICE'];
			$base_id = $arr['ID'];
		} elseif(in_array($arr['CATALOG_GROUP_ID'], $price_ids)) {
			$arPriceId[] = $arr['ID'];
			if(is_numeric($arr['PRICE']) && $arr['PRICE'] > 0 && $arr['CURRENCY'] == 'RUB' && ($price == 0 || $arr['PRICE'] < $price)) {
				$price = $arr['PRICE'];
			}
		}
	}
	if(isset($price_spec)) {
		if(count($arPriceId)) {
			//удалим текущие цену при наличии спеццены
			foreach($arPriceId as $price_id) {
				$res = CPrice::Delete($price_id);
				empty($l_path) ? '' : file_put_contents($l_path, "\n" . date('Y-m-d H:i:s ') . 'Удаление (' . $res . ') ' . print_r($price_id, 1), FILE_APPEND);
			}
		}
		$price = $price_spec;
	}
	if(isset($price_base) && $price_base == $price) {//цена не изменилась, обновлять нечего
		return;
	}

	$rsFields = Array(
		"PRODUCT_ID" => $arFields['PRODUCT_ID'],
		"CATALOG_GROUP_ID" => $price_base_id, // Указываем ID цены, можно посмотреть в админке: Магазин - Настройки - Типы цен
		"PRICE" => $price,
		"CURRENCY" => "RUB",
		"QUANTITY_FROM" => false,
		"QUANTITY_TO" => false
	);

	if(isset($base_id)) {
		$res = CPrice::Update($base_id, $rsFields);
		empty($l_path) ? '' : file_put_contents($l_path, "\n" . date('Y-m-d H:i:s ') . 'Изменение (' . $res . ') ' . print_r($rsFields, 1), FILE_APPEND);
	} else {
		$res = CPrice::Add($rsFields);
		empty($l_path) ? '' : file_put_contents($l_path, "\n" . date('Y-m-d H:i:s ') . 'Добавление (' . $res . ') ' . print_r($rsFields, 1), FILE_APPEND);
	}
}

\Bitrix\Main\EventManager::getInstance()->addEventHandler('search', 'BeforeIndex',
    array('\Olegpro\Classes\Handlers\Search\Stemming', 'beforeIndex')
);
 
\Bitrix\Main\EventManager::getInstance()->addEventHandler('search', 'OnBeforeIndexUpdate',
    array('\Olegpro\Classes\Handlers\Search\Stemming', 'beforeIndexUpdate')
);
 
\Bitrix\Main\EventManager::getInstance()->addEventHandler('search', 'OnAfterIndexAdd',
    array('\Olegpro\Classes\Handlers\Search\Stemming', 'beforeIndexUpdate')
);
 
\Bitrix\Main\Loader::registerAutoLoadClasses(null, array(
    '\Olegpro\Classes\Handlers\Search\Stemming' => '/local/php_interface/classes/handlers/search/stemming.php',
));