<?php

	use Bitrix\Main\Loader;

	if (! defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED != true) die;

	Loader::registerAutoLoadClasses(null, array(
		'Menu' => '/local/components/utlab/sitemap/lib/classMenu.php',
		'IBl' => '/local/components/utlab/sitemap/lib/classIblock.php',
		'Seo' => '/local/components/utlab/sitemap/lib/Seo/classSeo.php',
		'NoIndex' => '/local/components/utlab/sitemap/lib/Seo/classNoIndex.php',
    	'Redirect' => '/local/components/utlab/sitemap/lib/Seo/classRedirect.php',
		'Xml' => '/local/components/utlab/sitemap/lib/xml/classXml.php',
		'XmlTypes' => '/local/components/utlab/sitemap/lib/xml/classXmlTypes.php',
	));

	Loader::includeModule('iblock');
	Loader::autoLoad('Menu');
	Loader::autoLoad('IBl');
	Loader::autoLoad('Xml');
	Loader::autoLoad('XmlTypes');
	
	class SiteMap extends CBitrixComponent
	{
		public function getResult()
		{		
			$this->arResult['ITEMS'] = array();
			//Собираем меню
			if($this->arParams['MENU_TYPES']){
				$menus = new Menu($this->arParams);				
				$this->arResult['ITEMS'] = array_merge(	
					$this->arResult['ITEMS'],				
					$menus->getMenus($this->arParams['MENU_TYPES'])
				);
			}
			
			//Собираем разделы ИБ
			if($this->arParams['IB_LIST']){	
				$ibl = new IBl($this->arParams);			
				$this->arResult['ITEMS'] = array_merge(
					$this->arResult['ITEMS'],
					$ibl->getSections($this->arParams['IB_LIST'])
				);
			}
			
			if($this->arParams['XML_FILE'] == 'Y'){// для задачи на cron, генерации простого файла и с разбиением на подфайлы
				if(!empty($this->arParams['LIMIT']) && $this->arParams['LIMIT'] > 0){// превышение лимита, несколько фалов
					$xml = new XmlTypes($this->arResult, $this->arParams);
				}else{// один файл
					$xml = new Xml($this->arResult, $this->arParams);					
				}
			}else{// вывод через шаблон
				return $this->arResult;
			}
		}
}
