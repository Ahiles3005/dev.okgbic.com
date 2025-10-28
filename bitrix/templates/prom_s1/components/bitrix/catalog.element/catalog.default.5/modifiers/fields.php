<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use \Bitrix\Main\Loader;
use \Bitrix\Highloadblock as HL;
/**
 * @var array $arResult
 * @var array $arParams
 * @var array $arVisual
 */

$arFields = [
    'ARTICLE' => [
        'SHOW' => false,
        'VALUE' => null
    ],
    'BRAND' => [
        'SHOW' => false,
        'VALUE' => []
    ],
    'MARKS' => [
        'SHOW' => false,
        'HIT' => 'N',
        'NEW' => 'N',
        'RECOMMEND' => 'N'
    ],
    'DOCUMENTS' => [
        'SHOW' => false,
        'VALUES' => []
    ],
    'VIDEO' => [
        'SHOW' => false,
        'VALUES' => []
    ],
    'REVIEWS' => [
        'SHOW' => false,
        'VALUES' => []
    ],
    'ARTICLES' => [
        'SHOW' => false,
        'VALUES' => []
    ],
    'ADDITIONAL' => [
        'SHOW' => false,
        'VALUES' => []
    ],
    'ASSOCIATED' => [
        'SHOW' => false,
        'VALUES' => []
    ],
    'RECOMMENDED' => [
        'SHOW' => false,
        'VALUES' => []
    ],
    'SIMILAR' => [
        'SHOW' => false,
        'VALUES' => []
    ],
    'SERVICES' => [
        'SHOW' => false,
        'VALUES' => []
    ]
];
$arProperty = [];

/** Артикул */
if (!empty($arParams['PROPERTY_ARTICLE'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_ARTICLE'],
        'VALUE'
    ]);

    if (!empty($arProperty)) {
        if (Type::isArray($arProperty))
            $arProperty = ArrayHelper::getFirstValue($arProperty);

        $arFields['ARTICLE']['VALUE'] = $arProperty;
    }

    if (!empty($arFields['ARTICLE']['VALUE']))
        $arFields['ARTICLE']['SHOW'] = $arVisual['ARTICLE']['SHOW'];
}

/** Бренд */
if (!empty($arParams['PROPERTY_BRAND'])) {
    /*$arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_BRAND'],
        'VALUE'
    ]);
*/
	$arBrand = '';
	$arHighloadProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_BRAND'],
    ]);
	$sTableName = $arHighloadProperty['USER_TYPE_SETTINGS']['TABLE_NAME'];
	
	if ( Loader::IncludeModule('highloadblock') && !empty($sTableName) && !empty($arHighloadProperty["VALUE"]) ){
		$hlblock = HL\HighloadBlockTable::getRow([
			'filter' => [
			  '=TABLE_NAME' => $sTableName
			],
		]);
		
		if ( $hlblock ){
			$entity = HL\HighloadBlockTable::compileEntity( $hlblock );
			$entityClass = $entity->getDataClass();
			
			$arRecords = $entityClass::getList([
			  'filter' => [
				'UF_XML_ID' => $arHighloadProperty["VALUE"]
			  ],
			]);
			foreach ($arRecords as $record){	
			  $arRecord = $record;

			  if ( !empty($arRecord['~UF_FILE']) )
			  {
				$arRecord['UF_FILE'] = CFile::getById($arRecord['~UF_FILE'])->fetch();
			  }

			  $arBrand = $arRecord;
			 
			}
		}
		
	
	}
	
	if(!empty($arBrand)){
		$arBrandPicture = null;
		$arBrand['UF_FILE'] = CFile::getById($arBrand['UF_FILE'])->fetch();
		$arBrandPicture = $arBrand['UF_FILE'];
		
	
		
		$sBrandText = null;
		if (!empty($arBrand['UF_DESCRIPTION']))
            $sBrandText = $arBrand['UF_DESCRIPTION'];
		
		
		$arProperty = [
			'NAME' => $arBrand['UF_NAME'],
			'PICTURE' => $arBrandPicture,
			'TEXT' => $sBrandText,
			'URL' => [
				'DETAIL' => $arBrand['UF_LINK'],
				'LIST' => $arBrand['UF_LINK']
			]
		];

        $arFields['BRAND']['VALUE'] = $arProperty;
		
	}
	
    /*if (!empty($arProperty)) {
        if (Type::isArray($arProperty))
            $arProperty = ArrayHelper::getFirstValue($arProperty);

        $arProperty = CIBlockElement::GetByID($arProperty);
        $arProperty->SetUrlTemplates('', '', '');
        $arProperty = $arProperty->GetNext();

        if (!empty($arProperty)) {
            $arBrandPicture = null;

            if (!empty($arProperty['PREVIEW_PICTURE']))
                $arBrandPicture = $arProperty['PREVIEW_PICTURE'];
            else if (!empty($arProperty['DETAIL_PICTURES']))
                $arBrandPicture = $arProperty['PREVIEW_PICTURE'];

            if (!empty($arBrandPicture)) {
                $arBrandPicture = Arrays::fromDBResult(CFile::GetByID($arBrandPicture))->asArray();

                if (!empty($arBrandPicture)) {
                    $arBrandPicture = ArrayHelper::getFirstValue($arBrandPicture);
                    $arBrandPicture['SRC'] = CFile::GetFileSRC($arBrandPicture);
                }
            }

            $sBrandText = null;

            if (!empty($arProperty['PREVIEW_TEXT']))
                $sBrandText = $arProperty['PREVIEW_TEXT'];
            else if (!empty($arProperty['DETAIL_TEXT']))
                $sBrandText = $arProperty['DETAIL_TEXT'];

            $arProperty = [
                'NAME' => $arProperty['NAME'],
                'PICTURE' => $arBrandPicture,
                'TEXT' => $sBrandText,
                'URL' => [
                    'DETAIL' => $arProperty['DETAIL_PAGE_URL'],
                    'LIST' => $arProperty['LIST_PAGE_URL']
                ]
            ];

            $arFields['BRAND']['VALUE'] = $arProperty;

            unset($arBrandPicture, $sBrandText);
        }
    }*/

    if (!empty($arFields['BRAND']['VALUE']))
        $arFields['BRAND']['SHOW'] = $arVisual['BRAND']['SHOW'];
}

/** Таблица размеров */
if (!empty($arParams['PROPERTY_SIZES_SHOW'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_SIZES_SHOW']
    ]);

    if (!empty($arProperty)) {
        $arProperty = CIBlockFormatProperties::GetDisplayValue(
            $arResult,
            $arProperty,
            false
        );

        if ($arResult['SIZES']['SHOW'] && $arResult['SIZES']['MODE'] === 'element')
            $arResult['SIZES']['SHOW'] = !empty($arProperty['DISPLAY_VALUE']);
    }
}

/** Файлы */
if (!empty($arParams['PROPERTY_DOCUMENTS'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_DOCUMENTS'],
        'VALUE'
    ]);

    if (!empty($arProperty)) {
        if (!Type::isArray($arProperty))
            $arProperty[] = $arProperty;

        $arProperty = Arrays::fromDBResult(CFile::GetList(['SORT' => 'ASC'], [
            '@ID' => implode(',', $arProperty)
        ]))->indexBy('ID');

        if (!$arProperty->isEmpty()) {
            $arProperty = $arProperty->asArray(function ($key, $arFile) {
                $arFile['SRC'] = CFile::GetFileSRC($arFile);

                return [
                    'key' => $key,
                    'value' => $arFile
                ];
            });

            $arFields['DOCUMENTS']['VALUES'] = $arProperty;
        }
    }

    if (!empty($arFields['DOCUMENTS']['VALUES']))
        $arFields['DOCUMENTS']['SHOW'] = $arVisual['DOCUMENTS']['SHOW'];
}

/** Метки товара */
$arProperties = [
    'HIT',
    'NEW',
    'RECOMMEND'
];

foreach ($arProperties as $sProperty) {
    if (!empty($arParams['PROPERTY_MARKS_'.$sProperty])) {
        $arProperty = ArrayHelper::getValue($arResult, [
            'PROPERTIES',
            $arParams['PROPERTY_MARKS_'.$sProperty],
            'VALUE'
        ]);

        if (!empty($arProperty)) {
            $arFields['MARKS']['VALUES'][$sProperty] = 'Y';

            if (!$arFields['MARKS']['SHOW'] && $arVisual['MARKS']['SHOW'])
                $arFields['MARKS']['SHOW'] = true;
        }
    }
}

/** Свойства множественной привязки */
$arProperties = [
    'VIDEO',
    'REVIEWS',
    'ARTICLES',
    'ADDITIONAL',
    'ASSOCIATED',
    'RECOMMENDED',
    'SIMILAR',
    'SERVICES'
];

foreach ($arProperties as $sProperty) {
    if (!empty($arParams['PROPERTY_'.$sProperty])) {
        $arProperty = ArrayHelper::getValue($arResult, [
            'PROPERTIES',
            $arParams['PROPERTY_'.$sProperty],
            'VALUE'
        ]);

        if (!empty($arProperty)) {
            if (Type::isArray($arProperty))
                $arFields[$sProperty]['VALUES'] = $arProperty;
            else
                $arFields[$sProperty]['VALUES'][] = $arProperty;
        }

        if (!empty($arFields[$sProperty]['VALUES']))
            $arFields[$sProperty]['SHOW'] = $arVisual[$sProperty]['SHOW'];
    }
}

unset($arProperties, $key, $sProperty);

$arResult['FIELDS'] = $arFields;

unset($arFields, $arProperty);