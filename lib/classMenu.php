<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;

Loader::autoLoad('Seo');

class Menu{
    public $seo;
    private $urls = array();
    private $prefix = 'menu_';

    public function __construct($config){
        $this->seo = new Seo($config['SEO_ROBOTS'], $config['SEO_REDIRECTS'], $config['EXLUDE']);
    }

    /**
     * @param $arTypes
     * @return $result
     * возвращает массив из пунктов меню
     */
    public function getMenus($arTypes){
        global $APPLICATION;
        $result = array();
        foreach ($arTypes as $type) {
            $this->getMenu($type);
        }
        return $this->urls;
    }
    /**
     * @param $type
     * @return $result
     * возвращает пункты меню для определенного типа меню
     */
    public function getMenu($type){
        global $APPLICATION;
        foreach($APPLICATION->GetMenu($type)->arMenu as $menuItem){
            if($url = $this->seo->getActualUrl($menuItem[1])){                
                $this->urls[] = array(
                    'NAME'  => $menuItem[0],
                    'LINK'  => $url,
                    'DEPTH' => 1,
                    'TYPE' => $this->prefix.$type
                );
            }
        }
    }
}