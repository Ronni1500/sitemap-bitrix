<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader;

Loader::autoLoad('NoIndex');
Loader::autoLoad('Redirect');
Loader::autoLoad('InitSeo');

class Seo{

    const DIRECTIVE_DISALLOW = 'disallow';
    public $redirects = array();
    public $noindex = array();

    public function __construct($robots = 'N', $redirects = 'N', $exlude = array()){          
        if($robots == 'Y'){
            $noindex = new NoIndex();
            $this->noindex = $noindex->getRobots();
        }
        if($redirects != 'N'){
            $redir = new Redirect($redirects);
            $this->redirects = $redir->getUrl();
        }
        if(!empty($exlude)){
            $this->exlude = $exlude;
        }
    }

    /**
     * @param string $url
     * @return string|boolean
     * вернёт либо конечную ссылку, либо признак disallow 
     */
    public function getActualUrl($url){        
        if(!$this->checkRobots($url) && !$this->checkExlude($url)){
            return $this->checkRedirect($url);
        }
        return false;
    }

    /**
     * @param string $url
     * @return boolean
    */
    public function checkExlude($url){
        if(in_array($url, $this->exlude)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param string $url
     * @return bool $disallow
     * проверит попадает ли ссылка под Disallow
     */
    public function checkRobots($url){      
        $disallow = false;        
        foreach($this->noindex[self::DIRECTIVE_DISALLOW] as $value){            
            if(preg_match('/'.str_replace('/', '\/', preg_quote($value)).'/', $url)){
                $disallow = true;
                continue;
            }
        }
        return $disallow;
    }

    /**
     * @param string $url
     * вернет конечную ссылку после редиректа из seomod Redirects или туже самую
     */
    public function checkRedirect($uri){
        if(array_key_exists($uri, $this->redirects)){
            return $this->redirects[$uri];
        }else{
            return $uri;
        }
    }
}