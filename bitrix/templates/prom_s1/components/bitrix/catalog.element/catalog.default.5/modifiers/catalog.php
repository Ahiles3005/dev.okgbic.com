<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

if (!empty($arResult['OFFERS'])) {
    $arResult['CAN_BUY'] = false;

    $arPrices = null;
    $arPrice = null;
    foreach ($arResult['OFFERS'] as &$arOffer) {
        if (!empty($arOffer['ITEM_PRICES']))
            if ($arPrices === null || $arPrices[0]['PRICE'] > $arOffer['ITEM_PRICES'][0]['PRICE'])
                $arPrices = $arOffer['ITEM_PRICES'];
		
        if (!empty($arOffer['MIN_PRICE']))
            if ($arPrice === null || $arPrice['DISCOUNT_VALUE'] > $arOffer['MIN_PRICE']['DISCOUNT_VALUE'])
                $arPrice = $arOffer['MIN_PRICE'];
	
        $arDopPrice = null;
        foreach($arOffer['PRICES'] as $price){
            if($price['PRICE_ID'] == 8 && $price['PRICE_ID'] != $arOffer['MIN_PRICE']['PRICE_ID']){
                $arDopPrice['PRICE'] = $price['PRINT_VALUE_VAT']; 
                $arDopPrice['TITLE'] = $price['TITLE']; 
            }
        }
        $arOffer['DOP_PRICE'] = $arDopPrice;
        unset($arOffer);
    }

    $arResult['MIN_PRICE'] = $arPrice;
    $arResult['ITEM_PRICES'] = $arPrices;
	//$arResult['DOP_PRICE'] = $arDopPrice;
	
    unset($arPrice);
    unset($arPrices);
}

/*
$arResult['DOP_PRICE'] = [];
foreach($arResult['PRICE_MATRIX']['MATRIX'] as $key => $dopPrice){
	if($key == 8){
		$disPrice = $dopPrice['ZERO-INF']['DISCOUNT_PRICE'];
		$curPrice = $dopPrice['ZERO-INF']['CURRENCY'];
		$arResult['DOP_PRICE']['PRICE'] = CurrencyFormat($disPrice, $curPrice);
		$arResult['DOP_PRICE']['TITLE'] = $arResult['PRICE_MATRIX']['COLS'][$key]['NAME_LANG'];
	}
};
*/

$arResult['DOP_PRICE'] = [];
foreach($arResult['PRICE_MATRIX']['MATRIX'] as $key => $dopPrice){
	if($key == 8 && $arResult['ITEM_PRICES'][0]['PRICE_TYPE_ID'] != $key){
		$disPrice = $dopPrice['ZERO-INF']['DISCOUNT_PRICE'];
		$curPrice = $dopPrice['ZERO-INF']['CURRENCY'];
		$arResult['DOP_PRICE']['PRICE'] = CurrencyFormat($disPrice, $curPrice);
		$arResult['DOP_PRICE']['TITLE'] = $arResult['PRICE_MATRIX']['COLS'][$key]['NAME_LANG'] ? $arResult['PRICE_MATRIX']['COLS'][$key]['NAME_LANG'] : getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_EMPTY_TIILE');
	}
		
	if($key == 14){
		$disPrice = $dopPrice['ZERO-INF']['DISCOUNT_PRICE'];
		$curPrice = $dopPrice['ZERO-INF']['CURRENCY'];
		$arResult['SPECIAL_PRICE']['PRICE'] = CurrencyFormat($disPrice, $curPrice);
		$arResult['SPECIAL_PRICE']['TITLE'] = $arResult['PRICE_MATRIX']['COLS'][$key]['NAME_LANG']? $arResult['PRICE_MATRIX']['COLS'][$key]['NAME_LANG'] : getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_EMPTY_TIILE');
	}
};



