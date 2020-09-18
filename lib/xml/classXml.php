<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Application;

class Xml {
    private $indexAttributes = array(
        'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
        'xsi:schemaLocation' => 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd',
        'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
    );

    private $siteMapAttributes = array(
        'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
        'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
        'xsi:schemaLocation' => 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd',
    );
   
    private $urls = array(),
        $domain = '';
    private $types = array();
    private $countLinks = 0;
    private $listUrl = array(),
            $componentParams = array(),
            $fileName = '';
    /**
     * @param array $listUrl
     * @param array $componentParams
     * @param string $fileName
     */
    public function __construct($listUrl, $componentParams, $fileName = 'sitemap.xml'){
        try {
            $appInstance = Application::getInstance();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $context = $appInstance->getContext();
        $request = $context->getRequest();
        $host = $context->getServer()->getHttpHost();
        $this->domain = $request->isHttps() ? 'https' : 'http' . '://'. $this->host;
        $this->listUrl = $listUrl;
        $this->componentParams = $componentParams;
        $this->fileName = $fileName;
        $this->generateXml('urlset', $this->indexAttributes);
    }

    /**
     * @param $params
     * генерация выходного массива ссылок
     */
    private function setUrl($listUrls, $maxLevel = 0){
        foreach($listUrls as $key => $value){
            if($maxLevel != 0 && $value['DEPTH'] > $maxLevel) continue;
            if($value['LINK'] == '/'){
                $this->urls[] = $this->createElementForArrayXml(
                    $this->domain.$value['LINK'],
                    date('Y-m-d', strtotime('-1 day')),
                    'daily',
                    '1');
            }else{
                $this->urls[] = $this->createElementForArrayXml(
                    $this->domain.$value['LINK'],
                    date('Y-m-d', strtotime('-1 day')),
                    'weekly',
                    '0.8');
            }
        }
    }

    /**
     * @param string $name
     * @param array $attributes
     * @return array
     * создание индексного тега xml
     */
    private function createXml($name, $attributes){
        $xml = new DOMDocument();
        $xml->encoding = 'utf-8';
        $index = $xml->createElement($name);
        foreach ($attributes as $key => $attribute) {
            $index->setAttribute($key, $attribute);
        }
        $xml->appendChild($index);
        return array(
            'xml' => $xml,
            'index' => $index
        );
    }

    /**
     * @param array $listUrl
     * @param array $componentParams
     * генерация файла с урлами
     */
    protected function generateXml($mainTag, $attributes){        
        $this->countLinks = count($this->listUrl['ITEMS']);
        $this->setUrl($this->listUrl['ITEMS'], $this->componentParams['MAX_LEVEL']);
        $document = $this->createXML($mainTag, $attributes);
        $xml = $document['xml'];
        $index = $document['index'];
        foreach ($this->urls as $urlItem) {
            $url = $this->createElement($xml, $index, 'url');
            $this->createNodes($url, $xml, $urlItem);
        }
        $xml->save($_SERVER['DOCUMENT_ROOT'] . '/'. $this->fileName);
    }

    /**
     * @param string $link
     * @param string $data
     * @param string $changefreq
     * @param string $priority
     * @return array
     * генерация элемента массива для создания элемента XML
     */
    private function createElementForArrayXml($link, $data, $changefreq, $priority){
        return [
            'loc'       => $link,
            'lastmod'   => $data,
            'changefreq'=> $changefreq,
            'priority'  => $priority
        ];
    }

    /**
     * @param object $xml
     * @param object $index
     * @param string $elementName
     * @return object
     * создание родительского элемента
     */
    protected function createElement($xml, $index, $elementName){
        return $index->appendChild($xml->createElement($elementName));
    }

    /**
     * @param object $rootElement
     * @param object $xml
     * @param array $url
     * создает дочерние элементы в $rootElement
     */
    protected function createNodes($rootElement, $xml, $url){
        foreach ($url as $key => $value) {            
            $rootElement->appendChild($xml->createElement($key, $value));
        }
    }
}