<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Application;


class NoIndex {
    const DIRECTIVE_DISALLOW = 'disallow';
    const DIRECTIVE_HOST = 'host';
    
    private $domain = '';
    private $arRobots;

    public function __construct(){
        try {
            $appInstance = Application::getInstance();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $context = $appInstance->getContext();
        $request = $context->getRequest();
        $host = $context->getServer()->getHttpHost();
        $host = $_SERVER['SERVER_NAME'];

        $this->domain = $request->isHttps() ? 'https://' : 'http://';
        $this->domain .=  $host;

        $this->setRobots();
    }

    /**
     * @return array
     * массив disallow
     */
    public function getRobots (){
        return $this->arRobots;
    }

    /**
     * устанавливает директории Disallow
     */
    private function setRobots() {
        $httpClient = new HttpClient();
        $content = $httpClient->get($this->domain . '/robots.txt');
        $rows = explode(PHP_EOL, $content);
        foreach ($rows as $row) {
            $row = preg_replace('{#.*}', '', $row);
            $parts = explode(':', $row, 2);
            if (count($parts) < 2) {
                continue;
            }

            $directive = strtolower(trim($parts[0]));
            $value = trim($parts[1]);

            switch ($directive) {
                case self::DIRECTIVE_HOST:
                    $this->arRobots[self::DIRECTIVE_HOST] = $value;
                    break;
                case self::DIRECTIVE_DISALLOW:
                    $this->arRobots[self::DIRECTIVE_DISALLOW][] = trim($value);
                    break;
            }

        }
    }
}