<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
    return "";

$strReturn = '';

$strReturn .= '<div class="ns-bitrix c-breadcrumb c-breadcrumb-default">
        <div class="breadcrumb-wrapper intec-content intec-content-visible">
            <div class="breadcrumb-wrapper-2 intec-content-wrapper" itemscope="" itemtype="http://schema.org/BreadcrumbList">';

$currentUrl = $APPLICATION->GetCurPage();
$arResult = ArrayHelper::merge(
    [[
        'TITLE' => Loc::getMessage('BREADCRUMB_MAIN_TITLE'),
        'LINK' => SITE_DIR
    ]],
    $arResult
);
$itemSize = count($arResult);

for($index = 0; $index < $itemSize; $index++)
{
    $title = htmlspecialcharsex($arResult[$index]["TITLE"]);
    $arrow = ($index > 0? '<span class="brand-pal">/</span>' : '');

    if($arResult[$index]["LINK"] <> "" && $arResult[$index]["LINK"] <> $currentUrl)
    {
        $strReturn .=  $arrow.'
			<div class="breadcrumb-item" id="bx_breadcrumb_'.$index.'" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
				<a class="bx-breadcrumb-item-link brand-link" href="'.$arResult[$index]["LINK"].'" title="'.$title.'" itemprop="item">
					<span class="name-brand" itemprop="name">'.$title.'</span>
				</a>
				<meta itemprop="position" content="'.($index + 1).'" />
			</div>';
    }
    else
    {
        $strReturn .= $arrow.'
			<div class="breadcrumb-item">
				<span class="name-brand " itemprop="name">'.$title.'</span>
			</div>';
    }
}

$strReturn .= '</div></div></div>';

return $strReturn;
