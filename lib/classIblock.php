<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader;

Loader::includeModule('iblock');
Loader::autoLoad('Seo');

class IBl {

    public $seo;
    public $config;
    private $resutl = array();
    private $prefix = 'iblock_';

    public function __construct($config){  
        $this->config = $config;      
        $this->seo = new Seo($config['SEO_ROBOTS'], $config['SEO_REDIRECTS'], $config['EXLUDE']);
    }

    /**
     * @param $ibList
     * @return $result
     */
    public function getSections($ibList){
            $result = array();
            foreach ($ibList as $iBlockId) {
                $rsSections = CIBlockSection::GetTreeList(
                    array(
                        'IBLOCK_ID' => $iBlockId,
                        'ACTIVE' => 'Y', 
                        'GLOBAL_ACTIVE' => 'Y'
                    ),
                    array('ID', 'IBLOCK_ID', 'NAME', 'SECTION_PAGE_URL', 'DEPTH_LEVEL')
                );
                while ($section = $rsSections->GetNext()) {
                    if($url = $this->seo->getActualUrl($section['SECTION_PAGE_URL'])){ 
                        $this->result[] = array(
                            'NAME'  => $section['NAME'],
                            'LINK'  => $url,
                            'DEPTH' => $section['DEPTH_LEVEL'],
                            'TYPE'  => $this->prefix.$iBlockId
                        );                    
                        if($this->config['NEED_ELEMENTS'] == 'Y'){                            
                            $this->getElementsSection($iBlockId, $section['ID']);
                        }
                    }
                }
            }
            return $this->result;
        }
        
        /**
         * @param $section
         * @return $result
         * вернёт элементы раздела или false eсли елементов нет
         */
        public function getElementsSection($iBlockId, $section){
            $rsElement = CIBlockElement::GetList(
                $arOrder  = array("SORT" => "ASC"),
                $arFilter = array(
                    'IBLOCK_ID' => $iBlockId,
                    'SECTION_ID' => $section,
                    'ACTIVE' => 'Y', 
                    'GLOBAL_ACTIVE' => 'Y'
                ),
                false,
                false,
                $arSelectFields = array("ID", "NAME", "DETAIL_PAGE_URL","DEPTH_LEVEL")
            );
            if($rsElement->SelectedRowsCount()){
                while($arElement = $rsElement->getNext()) {
                    if($url = $this->seo->getActualUrl($arElement['DETAIL_PAGE_URL'])){
                        $this->result[] = array(
                            'NAME'  => $arElement['NAME'],
                            'LINK'  => $url,
                            'DEPTH' => $arElement['DEPTH_LEVEL'],
                            'TYPE'  => $this->prefix.$iBlockId
                        );
                    }
                }
            }
        }
}