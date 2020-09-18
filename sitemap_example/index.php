<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Карта сайта");
?>

<?
	$APPLICATION->IncludeComponent(
		'utlab:sitemap',
		'',
		array(
			'MENU_TYPES' => array(// типы меню
				'top_left',
				'top_right',
			),
			'IB_LIST' 		=> array(// ID инфоблоков
				3, 19
			),
			'STATIC' 		=> array(// статические страницы

			),
			'EXLUDE'		=> array(// странцицы исключения

			),
			'NEED_ELEMENTS' => 'Y',// добавлять элементы инфоблока
			'SEO_REDIRECTS' => 79,// использовать редиректы из seoMod
			'SEO_ROBOTS'	=> 'Y',// применять исключения disallow из robots.txt
			'XML_FILE'      => 'N',// создавать статический файл, если используется cron
			'MAX_LEVEL'		=> 10,// максимальный уровень вложености для ссылок карты сайта
			'LIMIT'			=> 0, // количество ссылок при котором произойдёт разбиение на файлы
			'XML'			=> 'N' // статичные страницы только для xml шаблона
		)
	);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>