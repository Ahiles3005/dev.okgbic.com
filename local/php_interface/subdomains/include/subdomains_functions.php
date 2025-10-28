<?php
global $subdomains_config;
$subdomains_config = require(dirname(__FILE__) . '/subdomains_config.php');

function subdomains_export(){
	$domains = file_get_contents(dirname(dirname(__FILE__)) . '/subdomains62f.csv');
	$domains = explode("\r\n", $domains);

	file_put_contents(dirname(dirname(__FILE__)) . '/subdomains.txt', "[\r\n");
	foreach($domains as $item){
		$item = explode(";", $item);
		file_put_contents(dirname(dirname(__FILE__)) . '/subdomains.txt', "		'" . str_replace('.okgbi.ru', '', $item[0]) . "' => [\r\n", FILE_APPEND);
		file_put_contents(dirname(dirname(__FILE__)) . '/subdomains.txt', "			'city' => '" . $item[1] . "',\r\n", FILE_APPEND);
		file_put_contents(dirname(dirname(__FILE__)) . '/subdomains.txt', "			'city_vp' => '" . $item[2] . "',\r\n", FILE_APPEND);
		file_put_contents(dirname(dirname(__FILE__)) . '/subdomains.txt', "			'city_dp' => '" . $item[3] . "',\r\n", FILE_APPEND);
		file_put_contents(dirname(dirname(__FILE__)) . '/subdomains.txt', "			'city_pp' => '" . $item[4] . "',\r\n", FILE_APPEND);
		file_put_contents(dirname(dirname(__FILE__)) . '/subdomains.txt', "		],\r\n", FILE_APPEND);
	}
	file_put_contents(dirname(dirname(__FILE__)) . '/subdomains.txt', "],\r\n", FILE_APPEND);
	return $domains;
}

function subdomains_links($conf = []){
	$html = '<a href="https://' . $conf['domain']['site'] . '">' . $conf['domain']['city'] . "</a>\r\n";
	foreach($conf['regions'] as $s => $item){
		$html .= '<a href="https://' . $s . '.' . $conf['domain']['site'] . '">' . $item['city'] . "</a>\r\n";
	}

	return $html;
}

function subdomains_regions_content($conf = []){
	$host = str_replace('.' . $conf['domain']['site'], '', $_SERVER['HTTP_HOST']);
	if($_SERVER['HTTP_HOST'] !== $conf['domain']['site'] && isset($conf['regions'][$host]) 
		&& isset($conf['subdomain']['page']['regions']['content'])
	) {
		$html = str_replace('[#city#]', $conf['regions'][$host]['city'], $conf['subdomain']['page']['regions']['content']);
	} else {
		$html = $conf['domain']['page']['regions']['content'];
	}

	return $html;
}

function subdomains_on_prolog(){
	global $subdomains_config;
	$conf = $subdomains_config;
	$host = str_replace('.' . $conf['domain']['site'], '', $_SERVER['HTTP_HOST']);
	if($_SERVER['HTTP_HOST'] !== $conf['domain']['site'] && isset($conf['regions'][$host])){
		$page = $GLOBALS['APPLICATION']->GetCurDir();
		$page_ar = explode('/', $page);
		//Определим id активных групп
		$subdomains_sect_id = [];
		if(CModule::IncludeModule("iblock")) {
			$arFilter = ['IBLOCK_ID' => 138, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'UF_ACTIVE_SUBDOMAIN_VALUE' => $host];
			$rsSections = CIBlockSection::GetList([], $arFilter, false, ['ID']);
			while ($arSection = $rsSections->GetNext()) {
				$subdomains_sect_id[] = $arSection['ID'];
			}
		}
		$GLOBALS['arrSubdomainSectionId'] = $subdomains_sect_id;
		$GLOBALS['SUBDOMAINS']['SUBDOMAIN'] = $host;

		if($page == '/'){ //home
			$GLOBALS['arrSectionFilter'] = ['UF_ACTIVE_SUBDOMAIN_VALUE' => $host];
			$GLOBALS['arrFilter'][] = ['IBLOCK_SECTION_ID' => $subdomains_sect_id];

		} elseif($page == '/regions/'){

		} elseif($page == '/catalog/'){
			$GLOBALS['arrSectionFilter'] = ['UF_ACTIVE_SUBDOMAIN_VALUE' => $host];
			$GLOBALS['arrFilter'][] = ['IBLOCK_SECTION_ID' => $subdomains_sect_id];

		} elseif($page_ar[1] == 'catalog' && count($page_ar) == 4){ //category
			$GLOBALS['arrSectionFilter'] = ['UF_ACTIVE_SUBDOMAIN_VALUE' => $host];
			$GLOBALS['arrFilter'][] = ['IBLOCK_SECTION_ID' => $subdomains_sect_id];

		} elseif($page_ar[1] == 'catalog' && count($page_ar) == 5){ //tovar
			$GLOBALS['arrSectionFilter'] = ['UF_ACTIVE_SUBDOMAIN_VALUE' => $host];
			$GLOBALS['arrFilter'][] = ['IBLOCK_SECTION_ID' => $subdomains_sect_id];

		}
	}
}

function subdomains_on_epilog(){
	global $subdomains_config;
	$conf = $subdomains_config;
	$host = str_replace('.' . $conf['domain']['site'], '', $_SERVER['HTTP_HOST']);
	if($_SERVER['HTTP_HOST'] !== $conf['domain']['site'] && isset($conf['regions'][$host])){
		$canonical = $GLOBALS['APPLICATION']->GetPageProperty('canonical');
		$GLOBALS['APPLICATION']->SetPageProperty('canonical', 
			str_replace('://'.$conf['domain']['site'], '://'.$_SERVER['HTTP_HOST'], $canonical)
		);

		$page = $GLOBALS['APPLICATION']->GetCurDir();
		$page_ar = explode('/', $page);

		if($page == '/'){ //home
			$GLOBALS['APPLICATION']->SetPageProperty('title', 
				str_replace([
						'[#city#]',
						'[#city_vp#]',
						'[#city_dp#]',
						'[#city_pp#]',
					], [ 
						$conf['regions'][$host]['city'],
						$conf['regions'][$host]['city_vp'],
						$conf['regions'][$host]['city_dp'],
						$conf['regions'][$host]['city_pp'],
					], 
					$conf['subdomain']['page']['home']['title']
				)
			);
			$GLOBALS['APPLICATION']->SetPageProperty('description', 
				str_replace([
						'[#city#]',
						'[#city_vp#]',
						'[#city_dp#]',
						'[#city_pp#]',
					], [ 
						$conf['regions'][$host]['city'],
						$conf['regions'][$host]['city_vp'],
						$conf['regions'][$host]['city_dp'],
						$conf['regions'][$host]['city_pp'],
					], 
					$conf['subdomain']['page']['home']['description']
				)
			);

		} elseif($page == '/regions/'){
			$GLOBALS['APPLICATION']->SetPageProperty('title', 
				str_replace('[#city#]', $conf['regions'][$host]['city'], $conf['subdomain']['page']['regions']['title'])
			);
			$GLOBALS['APPLICATION']->SetPageProperty('description', 
				str_replace('[#city#]', $conf['regions'][$host]['city'], $conf['subdomain']['page']['regions']['description'])
			);

		} elseif($page == '/catalog/'){
			$GLOBALS['APPLICATION']->SetPageProperty('title', 
				str_replace('[#city_pp#]', $conf['regions'][$host]['city_pp'], $conf['subdomain']['page']['catalog']['title'])
			);
			$GLOBALS['APPLICATION']->SetPageProperty('description', 
				str_replace('[#city#]', $conf['regions'][$host]['city'], $conf['subdomain']['page']['catalog']['description'])
			);

		} elseif($page_ar[1] == 'catalog' && count($page_ar) == 4){ //category
			if(!empty($conf['regions'][$host]['category_title']) && !empty($conf['regions'][$host]['category_description'])) {
				if(CModule::IncludeModule("iblock")) {
					$arFilter = ['IBLOCK_ID' => 138, 'CODE' => $page_ar[2], 'GLOBAL_ACTIVE' => 'Y'];
					$rsSections = CIBlockSection::GetList([], $arFilter, false, [
						$conf['regions'][$host]['category_title'],
						$conf['regions'][$host]['category_description'],
					]);
					if($arSection = $rsSections->Fetch()) {
						$category_title = $arSection[$conf['regions'][$host]['category_title']];
						$category_description = $arSection[$conf['regions'][$host]['category_description']];
					}
				}
			}
			if(!empty($category_title)){
				$GLOBALS['APPLICATION']->SetPageProperty('title', $category_title);
			} else {
				$GLOBALS['APPLICATION']->SetPageProperty('title', 
					str_replace([
							'[#city#]',
							'[#city_vp#]',
							'[#city_dp#]',
							'[#city_pp#]',
							'[#category_name#]'
						], [ 
							$conf['regions'][$host]['city'],
							$conf['regions'][$host]['city_vp'],
							$conf['regions'][$host]['city_dp'],
							$conf['regions'][$host]['city_pp'],
							$GLOBALS['APPLICATION']->GetTitle()
						], 
						$conf['subdomain']['page']['category']['title']
					)
				);
			}
			if(!empty($category_description)){
				$GLOBALS['APPLICATION']->SetPageProperty('description', $category_description);
			} else {
				$GLOBALS['APPLICATION']->SetPageProperty('description', 
					str_replace([
							'[#city#]',
							'[#city_vp#]',
							'[#city_dp#]',
							'[#city_pp#]',
							'[#category_name#]'
						], [ 
							$conf['regions'][$host]['city'],
							$conf['regions'][$host]['city_vp'],
							$conf['regions'][$host]['city_dp'],
							$conf['regions'][$host]['city_pp'],
							$GLOBALS['APPLICATION']->GetTitle()
						], 
						$conf['subdomain']['page']['category']['description']
					)
				);
			}

		} elseif($page_ar[1] == 'catalog' && count($page_ar) == 5){ //tovar
			//Определим товар
			$res = CIBlockElement::GetList([], 
				['IBLOCK_ID' => 138, 'CODE' => $page_ar[3], 'SECTION_CODE' => $page_ar[2]], false, false, 
				["ID", "IBLOCK_ID", "NAME", 'PREVIEW_TEXT', 'IBLOCK_SECTION_ID']
			);
			$arFields = [];
			if($ob = $res->GetNextElement()){
				$arFields = $ob->GetFields();
			}
			if(!empty($arFields['PREVIEW_TEXT'])){
				$tovar_name = $arFields['PREVIEW_TEXT'];
			} else {
				$tovar_name = $GLOBALS['APPLICATION']->GetTitle();
			}
			if(empty($arFields['IBLOCK_SECTION_ID'])){
				$cat = $GLOBALS['APPLICATION']->arAdditionalChain;
				$cat = $cat[array_key_last($cat)]['TITLE']; //последний эл-т
			} else {
				$res = CIBlockSection::GetList([], ['IBLOCK_ID' => 138, 'ID' => $arFields['IBLOCK_SECTION_ID']], false, ['NAME']);
				$arSect = $res->GetNext();
				$cat = $arSect['NAME'];
			}

			$GLOBALS['APPLICATION']->SetPageProperty('title', 
				str_replace([
						'[#city#]',
						'[#city_vp#]',
						'[#city_dp#]',
						'[#city_pp#]',
						'[#tovar_name#]'
					], [ 
						$conf['regions'][$host]['city'],
						$conf['regions'][$host]['city_vp'],
						$conf['regions'][$host]['city_dp'],
						$conf['regions'][$host]['city_pp'],
						$tovar_name
					], 
					$conf['subdomain']['page']['tovar']['title']
				)
			);
			$GLOBALS['APPLICATION']->SetPageProperty('description', 
				str_replace([
						'[#city#]',
						'[#city_vp#]',
						'[#city_dp#]',
						'[#city_pp#]',
						'[#tovar_name#]',
						'[#category_name#]'
					], [ 
						$conf['regions'][$host]['city'],
						$conf['regions'][$host]['city_vp'],
						$conf['regions'][$host]['city_dp'],
						$conf['regions'][$host]['city_pp'],
						$tovar_name,
						$cat
					], 
					$conf['subdomain']['page']['tovar']['description']
				)
			);

		}
	}
}

function subdomains_city(){
	global $subdomains_config;
	$conf = $subdomains_config;
	$host = str_replace('.' . $conf['domain']['site'], '', $_SERVER['HTTP_HOST']);
	if($_SERVER['HTTP_HOST'] !== $conf['domain']['site'] && isset($conf['regions'][$host]) 
		&& !empty($conf['regions'][$host]['city'])
	){
		$html = $conf['regions'][$host]['city'];
	} else {
		$html = $conf['domain']['city'];
	}
	return $html;
}

function subdomains_email($html){
	global $subdomains_config;
	$conf = $subdomains_config;
	$host = str_replace('.' . $conf['domain']['site'], '', $_SERVER['HTTP_HOST']);
	if($_SERVER['HTTP_HOST'] !== $conf['domain']['site'] && isset($conf['regions'][$host]) 
		&& !empty($conf['regions'][$host]['email'])
	){
		$html = $conf['regions'][$host]['email'];
	}
	return $html;
}

function subdomains_address($html){
	global $subdomains_config;
	$conf = $subdomains_config;
	$host = str_replace('.' . $conf['domain']['site'], '', $_SERVER['HTTP_HOST']);
	if($_SERVER['HTTP_HOST'] !== $conf['domain']['site'] && isset($conf['regions'][$host]) 
		&& !empty($conf['regions'][$host]['address'])
	){
		$html = $conf['regions'][$host]['address'];
	}
	return $html;
}

function subdomains_phone($html){
	global $subdomains_config;
	$conf = $subdomains_config;
	$host = str_replace('.' . $conf['domain']['site'], '', $_SERVER['HTTP_HOST']);
	if($_SERVER['HTTP_HOST'] !== $conf['domain']['site'] && isset($conf['regions'][$host]) 
		&& !empty($conf['regions'][$host]['phone'])
	){
		$html = $conf['regions'][$host]['phone'];
	}
	return $html;
}

function subdomains_contact_id($id = 0){
	global $subdomains_config;
	$conf = $subdomains_config;
	$host = str_replace('.' . $conf['domain']['site'], '', $_SERVER['HTTP_HOST']);
	if($_SERVER['HTTP_HOST'] !== $conf['domain']['site'] && isset($conf['regions'][$host]) 
		&& !empty($conf['regions'][$host]['contact_id'])
	){
		$id = $conf['regions'][$host]['contact_id'];
	}
	return $id;
}

function subdomains_pages_links(){
	global $subdomains_config;
	$conf = $subdomains_config;
	$host = str_replace('.' . $conf['domain']['site'], '', $_SERVER['HTTP_HOST']);
	$page = $GLOBALS['APPLICATION']->GetCurDir();
	if($_SERVER['HTTP_HOST'] !== $conf['domain']['site']){
		$html = '<a href="https://' . $conf['domain']['site'] . $page . '" rel="nofollow noopener">' . $conf['domain']['city'] . "</a>\r\n";
	} else {
		$html = '<div>' . $conf['domain']['city'] . "</div>\r\n";
	}
	foreach($conf['regions'] as $s => $item){
		if($host !== $s){
			$html .= '<a href="https://' . $s . '.' . $conf['domain']['site'] . $page . '" rel="nofollow noopener">' . $item['city'] . "</a>\r\n";
		} else {
			$html .= '<div>' . $item['city'] . "</div>\r\n";
		}
	}

	return $html;
}

function subdomains_footer_copyright(){
	global $subdomains_config;
	$conf = $subdomains_config;
	$host = str_replace('.' . $conf['domain']['site'], '', $_SERVER['HTTP_HOST']);
	if($_SERVER['HTTP_HOST'] !== $conf['domain']['site'] && isset($conf['regions'][$host]) 
		&& !empty($conf['subdomain']['footer']['copyright'])
	){
		$html = str_replace('[#city_pp#]', $conf['regions'][$host]['city_pp'], $conf['subdomain']['footer']['copyright']);
	} else {
		$html = '';
	}
	return $html;
}

function subdomains_sitemap(){
	global $subdomains_config;
	$conf = $subdomains_config;

	if(empty($GLOBALS['SUBDOMAINS']['SUBDOMAIN'])){
		$xml = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $conf['domain']['sitemap']['src']);
	} else {
		$host = $GLOBALS['SUBDOMAINS']['SUBDOMAIN'];
		if(isset($conf['regions'][$host]['sitemap']) && $conf['regions'][$host]['sitemap'] == 'auto') {
			$xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n"
				.'<url><loc>https://'.$_SERVER['HTTP_HOST'].'/</loc></url>'."\n"
				.'<url><loc>https://'.$_SERVER['HTTP_HOST'].'/catalog/</loc></url>'."\n";

			$arFilter = ['IBLOCK_ID' => 138, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'UF_ACTIVE_SUBDOMAIN_VALUE' => $host];
			$rsSections = CIBlockSection::GetList([], $arFilter, false, ['ID', 'TIMESTAMP_X', 'SECTION_PAGE_URL']);
			while ($arSection = $rsSections->GetNext()) {
				$xml .= '<url><loc>https://'.$_SERVER['HTTP_HOST'].$arSection['SECTION_PAGE_URL'].'</loc>'."\n"
					.'<lastmod>'.date_create_from_format('d.m.Y H:i:s', $arSection['TIMESTAMP_X'])->format('Y-m-d').'</lastmod></url>'."\n";
			}

			$arFilter = ['IBLOCK_ID' => 138, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'IBLOCK_SECTION_ID' => $GLOBALS['arrSubdomainSectionId']];
			$rsElements = CIBlockElement::GetList([], $arFilter, false, false, ['ID', 'TIMESTAMP_X', 'DETAIL_PAGE_URL']);
			while ($arElement = $rsElements->GetNext()) {
				$xml .= '<url><loc>https://'.$_SERVER['HTTP_HOST'].$arElement['DETAIL_PAGE_URL'].'</loc>'."\n"
					.'<lastmod>'.date_create_from_format('d.m.Y H:i:s', $arElement['TIMESTAMP_X'])->format('Y-m-d').'</lastmod></url>'."\n";
			}

			$xml .= '</urlset>';
		} else {
			$xml = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $conf['subdomain']['sitemap']['src']);
			$xml = str_replace('https://'.$conf['domain']['site'], 'https://'.$_SERVER['HTTP_HOST'], $xml);
		}
	}

	return $xml;
}

function subdomains_robots(){
	global $subdomains_config;
	$conf = $subdomains_config;
	$host = str_replace('.' . $conf['domain']['site'], '', $_SERVER['HTTP_HOST']);
	if($_SERVER['HTTP_HOST'] !== $conf['domain']['site'] && isset($conf['regions'][$host])){
		if(isset($conf['regions'][$host]['robots']['src'])){
			$txt = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $conf['regions'][$host]['robots']['src']);
		} else {
			$txt = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $conf['subdomain']['robots']['src']);
		}
		$txt = str_replace('https://'.$conf['domain']['site'], 'https://'.$_SERVER['HTTP_HOST'], $txt);
	} else {
		$txt = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $conf['domain']['robots']['src']);
	}
	return $txt;
}
