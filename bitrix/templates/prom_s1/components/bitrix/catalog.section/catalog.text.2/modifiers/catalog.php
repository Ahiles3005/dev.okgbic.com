<?php if (!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die();

/**
* @var array $arResult
* @var array $arCodes
* @var array $arVisual
*/
   
$arSections = [];

foreach ($arResult['ITEMS'] as &$arItem) {
    //pre('catalog.php');
    
    $sectionId = $arItem['IBLOCK_SECTION_ID'];


    if(empty($arSections[$arItem['IBLOCK_SECTION_ID']])) {
        $arSection = CIBlockSection::GetList(
            [], 
            ['ID' => $sectionId,'IBLOCK_ID'=>$arResult['IBLOCK_ID']], 
            false,     ['UF_*'] )->Fetch();
        $arSections[$arItem['IBLOCK_SECTION_ID']] = $arSection;
    }             
        
    if (!empty($arItem['OFFERS'])) {
        $arItem['CAN_BUY'] = false;

        if ($arItem['ACTION'] === 'buy' && !$arVisual['OFFERS']['USE'])
            $arItem['ACTION'] = 'detail';

        $arPrices = null;
        $arPrice = null;
        $minOffersPrice = 0;
        $minOffersPricePrint = '';
        
        $bFoundOffer = false;
        foreach ($arItem['OFFERS'] as &$arOffer) {     
            //echo '<pre>'; print_r($arOffer); echo '</pre>';
            if (!empty($arOffer['ITEM_PRICES'])) {
                if ($arPrices === null || $arPrices[0]['PRICE'] > $arOffer['ITEM_PRICES'][0]['PRICE']
                ) {
                    $arPrices = $arOffer['ITEM_PRICES'];
                }
            }
            /*
            if (!empty($arOffer['MIN_PRICE'])) {
            if ($arPrice === null || $arOffer['MIN_PRICE']['PRICE_ID'] == 14 || 
            $arPrice['PRICE_ID'] != 14 && $arPrice['DISCOUNT_VALUE'] > $arOffer['MIN_PRICE']['DISCOUNT_VALUE']
            ) {
            $arPrice = $arOffer['MIN_PRICE'];
            }
            }
            */
            $arOfferPriceMin = null;
            if (!empty($arOffer['PRICES'])) {
                foreach($arOffer['PRICES'] as $arOfferPrice) {
                    if ($arOfferPriceMin === null || $arOfferPrice['PRICE_ID'] == 14 || 
                    $arOfferPriceMin['PRICE_ID'] != 14 && $arOfferPriceMin['DISCOUNT_VALUE'] > $arOfferPrice['DISCOUNT_VALUE']
                    ) {
                        $arOfferPriceMin = $arOfferPrice;
                    }
                }
                if ($arPrice === null || $arPrice['DISCOUNT_VALUE'] > $arOfferPriceMin['DISCOUNT_VALUE']) {
                    $arPrice = $arOfferPriceMin;
                }
            }

            $arDopPrice = null;
            $arSpecialPrice = null;
            foreach($arOffer['PRICES'] as $price){
                if($price['PRICE_ID'] == 8){
                    $arDopPrice['PRICE'] = $price['PRINT_VALUE_VAT']; 
                    $arDopPrice['TITLE'] =  $price['TITLE'] ? $price['TITLE'] : getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_EMPTY_TIILE'); 
                }
                if($price['PRICE_ID'] == 14){
                    $arSpecialPrice['PRICE'] = $price['PRINT_VALUE_VAT']; 
                    $arSpecialPrice['TITLE'] = $price['TITLE']; 
                }

                if(!empty($price['VALUE']) && ($minOffersPrice == 0 || $price['VALUE'] < $minOffersPrice)){
                    $minOffersPrice = $price['VALUE'];
                    $minOffersPricePrint = $price['PRINT_VALUE'];
                }
            }
            $arOffer['DOP_PRICE'] = $arDopPrice;
            $arOffer['SPECIAL_PRICE'] = $arSpecialPrice;
            

            foreach($arOffer["PROPERTIES"] as $k => $offProp) {

                if($offProp['VALUE_ENUM_ID'] == $arSections[$arItem['IBLOCK_SECTION_ID']]['UF_SKU_SEL_VAL_ID'] && $k == $arSections[$arItem['IBLOCK_SECTION_ID']]['UF_SKU_SEL_PROP']) {
                    $minOffersPrice = $price['VALUE'];
                    $minOffersPricePrint = $price['PRINT_VALUE'];    
                    $bFoundOffer = true;
                    break;
                }   
            }
                      
            if($bFoundOffer) break;

            unset($arOffer);
        }

        $arItem['MIN_PRICE'] = 100;//$arPrice;
        $arItem['ITEM_PRICES'] = $arPrices;
        $arItem['MIN_OFFERS_PRICE'] = $minOffersPrice;
        $arItem['MIN_OFFERS_PRICE_PRINT'] = $minOffersPricePrint;

        unset($arPrice);
        unset($arPrices);
    }

    $arItem['DOP_PRICE'] = [];

    foreach($arItem['PRICE_MATRIX']['MATRIX'] as $key => $dopPrice){
        if($key == 8 && $arItem['ITEM_PRICES'][0]['PRICE_TYPE_ID'] != $key){
            $disPrice = $dopPrice['ZERO-INF']['DISCOUNT_PRICE'];
            $curPrice = $dopPrice['ZERO-INF']['CURRENCY'];
            $arItem['DOP_PRICE']['PRICE'] = CurrencyFormat($disPrice, $curPrice);
            $arItem['DOP_PRICE']['TITLE'] = $arItem['PRICE_MATRIX']['COLS'][$key]['NAME_LANG'] ? $arItem['PRICE_MATRIX']['COLS'][$key]['NAME_LANG'] : getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_EMPTY_TIILE');
        }

        if($key == 14){
            $disPrice = $dopPrice['ZERO-INF']['DISCOUNT_PRICE'];
            $curPrice = $dopPrice['ZERO-INF']['CURRENCY'];
            $arItem['SPECIAL_PRICE']['PRICE'] = CurrencyFormat($disPrice, $curPrice);
            $arItem['SPECIAL_PRICE']['TITLE'] = $arItem['PRICE_MATRIX']['COLS'][$key]['NAME_LANG']? $arItem['PRICE_MATRIX']['COLS'][$key]['NAME_LANG'] : getMessage('C_CATALOG_ELEMENT_DEFAULT_5_TEMPLATE_EMPTY_TIILE');
        }
    };



    unset($arItem);
}
