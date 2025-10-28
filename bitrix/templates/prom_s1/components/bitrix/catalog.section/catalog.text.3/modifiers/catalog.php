<?php if (!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arCodes
 * @var array $arVisual
 */

foreach ($arResult['ITEMS'] as &$arItem) {
    if (!empty($arItem['OFFERS'])) {
        $arItem['CAN_BUY'] = false;

        if ($arItem['ACTION'] === 'buy' && !$arVisual['OFFERS']['USE'])
            $arItem['ACTION'] = 'detail';

        $arPrices = null;
        $arPrice = null;

        foreach ($arItem['OFFERS'] as &$arOffer) {
            if (!empty($arOffer['ITEM_PRICES'])) {
                if ($arPrices === null || $arPrices[0]['PRICE'] > $arOffer['ITEM_PRICES'][0]['PRICE']) {
                    $arPrices = $arOffer['ITEM_PRICES'];
                }
            }

            if (!empty($arOffer['MIN_PRICE'])) {
                if ($arPrice === null || $arPrice['DISCOUNT_VALUE'] > $arOffer['MIN_PRICE']['DISCOUNT_VALUE']) {
                    $arPrice = $arOffer['MIN_PRICE'];
                }
            }

			
			foreach($arOffer['PRICES'] as $price){
				if($price['PRICE_ID'] == 8){
					$arDopPrice['PRICE'] = $price['PRINT_VALUE_VAT']; 
					$arDopPrice['TITLE'] =  $price['TITLE'] ? $price['TITLE'] : getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_EMPTY_TIILE'); 
					
				}
				
				if($price['PRICE_ID'] == 18){
					$arSpecialPrice['PRICE'] = $price['PRINT_VALUE_VAT']; 
					$arSpecialPrice['TITLE'] = $price['TITLE']; 
				}
			}
			$arOffer['DOP_PRICE'] = $arDopPrice;
			$arOffer['SPECIAL_PRICE'] = $arSpecialPrice;
			
            unset($arOffer);
        }

        $arItem['MIN_PRICE'] = $arPrice;
        $arItem['ITEM_PRICES'] = $arPrices;

        unset($arPrice);
        unset($arPrices);
    }
	
	$arItem['DOP_PRICE'] = [];

	foreach($arItem['PRICE_MATRIX']['MATRIX'] as $key => $dopPrice){
		if($key == 8){
			$disPrice = $dopPrice['ZERO-INF']['DISCOUNT_PRICE'];
			$curPrice = $dopPrice['ZERO-INF']['CURRENCY'];
			$arItem['DOP_PRICE']['PRICE'] = CurrencyFormat($disPrice, $curPrice);
			$arItem['DOP_PRICE']['TITLE'] = $arItem['PRICE_MATRIX']['COLS'][$key]['NAME_LANG'] ? $arItem['PRICE_MATRIX']['COLS'][$key]['NAME_LANG'] : getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_EMPTY_TIILE');
		}
		
		if($key == 18){
			$disPrice = $dopPrice['ZERO-INF']['DISCOUNT_PRICE'];
			$curPrice = $dopPrice['ZERO-INF']['CURRENCY'];
			$arItem['SPECIAL_PRICE']['PRICE'] = CurrencyFormat($disPrice, $curPrice);
			$arItem['SPECIAL_PRICE']['TITLE'] = $arItem['PRICE_MATRIX']['COLS'][$key]['NAME_LANG']? $arItem['PRICE_MATRIX']['COLS'][$key]['NAME_LANG'] : getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_EMPTY_TIILE');
		}
	};

	

    unset($arItem);
}