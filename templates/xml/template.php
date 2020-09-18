<?header("Content-Type: text/xml");
	use Bitrix\Main\Application;
	$appInstance = null;
	try {
		$appInstance = Application::getInstance();
	} catch (\Exception $e) {
		echo $e->getMessage();
	}
	$context = $appInstance->getContext();
	$request = $context->getRequest();
	$host = $context->getServer()->getHttpHost();
?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
		xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" 
		xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
	<url>
		<loc><?= $request->isHttps() ? 'https' : 'http' ?>://<?= $host ?></loc>
		<lastmod><?= date('Y-m-d', strtotime('-1 day')) ?></lastmod>
		<priority>1</priority>
		<changefreq>daily</changefreq>
	</url>
	<?php foreach ($arResult['ITEMS'] as $item) { ?>
		<?if($item['DEPTH'] > $arParams['MAX_LEVEL']) continue;?>
		<url>
			<loc><?= $request->isHttps() ? 'https' : 'http' ?>://<?= $host ?><?= $item['LINK'] ?></loc>
			<lastmod><?= date('Y-m-d', strtotime('-1 day')) ?></lastmod>
			<priority>0.8</priority>
			<changefreq>weekly</changefreq>
		</url>
	<?php } ?>
</urlset>