<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Application;

class Redirect {

    private $dbc;
    private $sqlHelper;
    private $listUrl = array();
    private $iblockRedirects;

    public function __construct($iblockRedirects){
        $this->dbc = Application::getConnection();
        $this->sqlHelper = $this->dbc->getSqlHelper(); 
        $this->iblockRedirects = $iblockRedirects;
        $this->setUrl();       
    }

    /**
     * @return array
     * массив редиректов
     */
    public function getUrl (){
        return $this->listUrl;
    }

    /**
     * редиректы из SeoMod
     */
    private function setUrl(){
        if (CModule::IncludeModule('iblock')) {
            $elements_redir = CIBlockElement::GetList(
                array(), 
                array(
                    'IBLOCK_ID' => $this->iblockRedirects, 
                    'ACTIVE' => 'Y'                    
                ), 
                false, 
                false, 
                array('ID', 'IBLOCK_ID', 'NAME', 'PROPERTY','PROPERTY_NEWURL','PROPERTY_SITE'));
            while($ar_redir = $elements_redir->GetNext()) {
                  
                $this->listUrl[$ar_redir['NAME']] = $ar_redir['PROPERTY_NEWURL_VALUE'];
            }
        } 
    }
}