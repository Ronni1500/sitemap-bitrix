<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader;

Loader::includeModule('iblock');
Loader::autoLoad('Seo');

/**
 * для обработки статических страниц
 */
class StaticPage
{
	public $seo;    
    private $prefix = 'static_page';
    public $result;

    public function __construct($config){        
        $this->seo = new Seo($config['SEO_ROBOTS'], $config['SEO_REDIRECTS']);
    }

    public function getResult($urls){    	

    	foreach ($urls as $key => $value) {
            if($url = $this->seo->getActualUrl($value)){
        		$this->result[] = array(
                    'NAME'  => 'Страница',
                    'LINK'  => $url,
                    'DEPTH' => '1',
                    'TYPE'  => $this->prefix
                );                
            }
    	}
    	return $this->result;
    }
}