<?
use Bitrix\Main\Loader;

Loader::autoLoad('Xml');

class XmlTypes extends Xml{
    private $siteMapAttributes = array(
        'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9'
    );

    private $urls = array(),
            $typesOfUrls = array(),
            $listUrl = array(),
            $componentParams = array(),
            $types = array();

    public function __construct($listUrl = array(), $componentParams = array()){
        $this->listUrl = $listUrl;
        $this->componentParams = $componentParams;
        $this->generateXmlTypes();
    }

    /**
     * @param array $listUrls
     * генерация выходного массива ссылок и xml структуры родительского файла
     */
    private function setUrl(){
        foreach($this->listUrls as $key => $value){
            $this->typesOfUrls[$value['TYPE']]['ITEMS'][] = $value;
            if(!in_array($value['TYPE'], $this->types)){
                $this->types[] = $value['TYPE'];
                $this->urls[] = [
                    'loc' => $this->domain.'/sitemap_'.$value['TYPE'].'.xml',
                ];
            }
        }
    }
    /**
     * @param array $listUrl
     * @param array $componentParams
     * генерация родительского файла с урлами на дочерние файлы
     */
    private function generateXmlTypes(){
        $this->setUrl();
        parent::generateXml('sitemapindex', $this->siteMapAttributes);
        foreach($this->typesOfUrls as $key => $value){            
            $newXml = new Xml($value, $componentParams, 'sitemap_'.$key.'.xml');
        }
    }
}