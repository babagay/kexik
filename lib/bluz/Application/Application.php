<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Application;

use Bluz\Acl\Acl;
use Bluz\Acl\AclException;
use Bluz\Application\Exception\ApplicationException;
use Bluz\Application\Exception\RedirectException;
use Bluz\Application\Exception\ReloadException;
use Bluz\Auth\Auth;
use Bluz\Cache\Cache;
use Bluz\Common\Exception;
use Bluz\Common\Helper;
use Bluz\Common\Nil;
use Bluz\Common\Singleton;
use Bluz\Config\Config;
use Bluz\Config\ConfigException;
use Bluz\Db\Db;
use Bluz\EventManager\EventManager;
//use Bluz\Logger\Logger;
use Bluz\Logger\MyLogger;
use Bluz\Mailer\Mailer;
use Bluz\Messages\Messages;
use Bluz\Registry\Registry;
use Bluz\Request;
use Bluz\Router\Router;
use Bluz\Session\Session;
use Bluz\Translator\Translator;
use Bluz\View\Layout;
use Bluz\View\View;

//use Core\FilterKeeper;
use \Core\Helper\Registry as AppRegistry;

/**
 * Application
 *
 * @category Bluz
 * @package  Application
 *
 * @method void denied()
 * @method void redirect(\string $url)
 * @method void wordEnd(\string $word, int $number)
 * @method void redirectTo(\string $module, \string $controller, array $params = array())
 * @method void reload()
 * @method \Bluz\Auth\AbstractRowEntity user()
 *
 * @author   Anton Shevchuk
 * @created  06.07.11 16:25
 */
class Application
{
    use Singleton;
    //use Helper;
    // FIXME введена переменная $this->helper

    /**
     * @var self
     */
    //protected static $instance;

    /**
     * @var Acl
     */
    protected $acl;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Db
     */
    protected $db;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var Layout
     */
    protected $layout;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @var Messages
     */
    protected $messages;

    /**
     * Application path
     *
     * @var string
     */
    protected $path;

    protected $core_path;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Request\AbstractRequest
     */
    protected $request;

    /*
     *
     */
    protected $helper;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var string
     */
    protected $environment;

    /**
     * Debug application
     * @var boolean
     */
    protected $debugFlag = false;

    /**
     * Use layout flag
     * @var boolean
     */
    protected $layoutFlag = true;

    /**
     * JSON response flag
     * @var boolean
     */
    protected $jsonFlag = false;

    /**
     * Widgets closures
     * @var array
     */
    protected $widgets = array();

    /**
     * api closures
     * @var array
     */
    protected $api = array();

    protected $helpers = array();

    /**
     * Temporary variable for save dispatch result
     * @var null
     */
    protected $dispatchResult = null;

    protected function __construct()
    {

    }

    function __invoke()
    {

    }

    protected function __clone()
    {

    }

    protected function __wakeup()
    {

    }

    /*
     * call application helper
     */
    function __call($name, $data)
    {
        if(!array_key_exists($name,$this->helpers)){
            $helperInclude = include PATH_CORE . "/Helper/" . ucfirst($name) . ".php";

            if(is_callable($helperInclude)){
                $this->helpers[$name] = $helperInclude;
                return $this->$name($data);
            } else {
                throw new \Exception("Helper '$name' not found in " . PATH_CORE . "/Helper/" . $name);
            }
        } else {
            if (isset($this->helpers[$name]) && is_callable($this->helpers[$name])) {

                return call_user_func_array($this->helpers[$name], $data);
            } else {
                throw new \Exception("Helper '$name' is not exists in " . __METHOD__);
            }
        }
    }

    /**
     * init
     *
     * @param string $environment Array format only!
     * @throws \Exception
     * @return Application
     */
    public function init($environment = 'production')
    {
        $this->environment = $environment;

        try {

            // Кладём в реестр eventManager
            \Core\Helper\Registry::getInstance() -> eventManager = $this->getEventManager();

            // setup configuration for current environment and save to Registry
            \Core\Helper\Registry::getInstance() -> config = $this->getConfig($environment);

            if ($debug = $this->getConfigData('debug')) {
                $this->debugFlag = $debug;
            }

            $this->log('app:init');

            // initial default helper path
            $this->helper = new Helper();
            $this->helper   ->addHelperPath(PATH_CORE . '/Helper/');
            $this->helper   ->addHelperPath(dirname(__FILE__) . '/Helper/');

            // Кладём в реестр Helper
            \Core\Helper\Registry::getInstance() -> helper = $this->helper;

            // TODO: добавить путь для хелперов
            // [!] Думаю, этот путь к хелперам шаблонов задается в другом месте
            // $this->helper   ->addHelperPath(PATH_CORE . "/View/layouts/helper");

            // session start inside
            $this->getSession();

            // initial Translator
            $this->getTranslator();

            // initial DB configuration  and save to Registry
            \Core\Helper\Registry::getInstance() -> db = $this->getDb();

        } catch (Exception $e) {
            throw new ApplicationException("Application can't be loaded: " . $e->getMessage());
        }
        return $this;
    }



    /**
     * log message, working with logger
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log($message, array $context = array())
    {
           $this->getLogger()->info($message, $context);
    }

    static function logStatic($message, array $context = array())
    {
           self::getLoggerStatic()->info($message, $context);
    }


    /**
     * load config file
     *
     * @param string|null $environment
     * @return Config
     */
    public function getConfig($environment = null)
    {
        if (!$this->config) {
            $this->config = new Config();
            $this->config->setPath($this->getCorePath() . '/configs');
            $this->config->load($environment);
        }
        return $this->config;
    }

    /**
     * config
     *
     * @param string|null $section of config
     * @param string|null $subsection of config
     * @return array
     */
    public function getConfigData($section = null, $subsection = null)
    {
        return $this->getConfig()->getData($section, $subsection);
    }

    /**
     * getAcl
     *
     * @return Acl
     */
    public function getAcl()
    {
        if (!$this->acl) {
            $this->acl = new \Bluz\Acl\Acl();
        }
        return $this->acl;
    }

    /**
     * getAuth
     *
     * @return Auth
     */
    public function getAuth()
    {
        if (!$this->auth && $conf = $this->getConfigData('auth')) {
            $this->auth = new Auth();
            $this->auth->setOptions($conf);
        }
        return $this->auth;
    }

    /**
     * if enabled return configured Cache or Nil otherwise
     *
     * @return Cache|Nil
     */
    public function getCache()
    {
        if (!$this->cache) {
            $conf = $this->getConfigData('cache');
            if (!isset($conf['enabled']) or !$conf['enabled']) {
                $this->cache = new Nil();
            } else {
                $this->cache = new \Bluz\Cache\Cache();
                $this->cache->setOptions($conf);
            }
        }
        return $this->cache;
    }

    /**
     * getDb
     *
     * @return Db
     */
    public function getDb()
    {
        if (!$this->db && $conf = $this->getConfigData('db')) {
            $this->db = new Db();
            $this->db->setOptions($conf);


        }
        return $this->db;
    }

    /**
     * getEventManager
     *
     * @return EventManager
     */
    public function getEventManager()
    {
        if (!$this->eventManager) {
            $this->eventManager = new \Bluz\EventManager\EventManager();
        }
        return $this->eventManager;
    }

    /**
     * getLayout
     *
     * @return Layout
     */
    public function getLayout()
    {
        if (!$this->layout && $conf = $this->getConfigData('layout')) {
            $this->layout = new Layout();
            $this->layout->setOptions($conf);
        }
        return $this->layout;
    }

    /**
     * resetLayout
     *
     */
    public function resetLayout()
    {
        $this->layout = null;
    }

    /**
     * load logger
     *
     * @return MyLogger
     */
    public function getLogger()
    {
        if (!$this->logger) {
            $conf = $this->getConfigData('logger');
            if (!isset($conf['enabled']) or !$conf['enabled']) {
                $this->logger = new \Bluz\Common\Nil();
            } else {
                $this->logger = new MyLogger();
            }
        }
        return $this->logger;
    }

    /**
     * @return \Core\FilterKeeper2|null
     */
    function getFilterKeeper()
    {
        return   \Core\FilterKeeper2::getInstance();
    }

    /*
     *
     */
    static  function getLoggerStatic()
    {
        return  new MyLogger();
    }

    /**
     * getMailer
     *
     * @throws ConfigException
     * @return Mailer
     */
    public function getMailer()
    {
        if (!$this->mailer) {
            if ($conf = $this->getConfigData('mailer')) {
                $this->mailer = new Mailer();
                $this->mailer->setOptions($conf);
            } else {
                throw new ConfigException(
                    "Missed `mailer` options in configuration file. <br/>\n" .
                    "Read more: <a href='https://github.com/bluzphp/framework/wiki/Mailer'>".
                    "https://github.com/bluzphp/framework/wiki/Mailer"."</a>"
                );
            }
        }
        return $this->mailer;
    }

    /**
     * hasMessages
     *
     * @return boolean
     */
    public function hasMessages()
    {
        if ($this->messages != null) {
            return ($this->messages->count() > 0);
        } else {
            return false;
        }
    }

    /**
     * getMessages
     *
     * @return Messages
     */
    public function getMessages()
    {
        if (!$this->messages) {
            $this->messages = new Messages();
        }
        return $this->messages;
    }

    /**
     *
     * getPath
     *
     * app()->getPath() возвращает C:\wamp\www\zoqa
     *
     * @param $type 'core'|null
     * @return string
     */
    public function getPath()
    {
        if (!$this->path) {


                if (defined('PATH_APPLICATION')) {
                    $this->path = PATH_APPLICATION;
                } else {
                    $reflection = new \ReflectionClass($this);
                    $this->path = dirname($reflection->getFileName());
                }

        }
        return $this->path;
    }

    public function getCorePath()
    {
        if (!$this->core_path) {
            if (defined('PATH_CORE')) {
                $this->core_path = PATH_CORE;
            } else {
                //TODO внести коррективы
                fb("TODO внести коррективы. Сработал вызов ReflectionClass;  " . __METHOD__);
                $reflection = new \ReflectionClass($this);
                $this->core_path = dirname($reflection->getFileName());
            }
        }
        return $this->core_path;
    }

    /**
     * getRegistry
     *
     * @return Registry
     */
    public function getRegistry()
    {
        if (!$this->registry) {
            $this->registry = new Registry();
            if ($conf = $this->getConfigData('registry')) {
                $this->registry->setData($conf);
            }
        }
        return $this->registry;
    }

    /**
     * getRequest
     *
     * @return Request\HttpRequest
     */
    public function getRequest()
    {
        if (!$this->request) {
            $this->request = new Request\HttpRequest();
            $this->request->setOptions($this->getConfigData('request'));

            if ($this->request->isXmlHttpRequest()) {
                $this->useLayout(false);
            }
        }
        return $this->request;
    }

    /**
     * setRequest
     *
     * @param Request\AbstractRequest $request
     * @return Application
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * getRouter
     *
     * @return Router
     */
    public function getRouter()
    {
        if (!$this->router) {
            $this->router = new Router();
        }
        return $this->router;
    }

    /**
     * getSession
     *
     * @return Session
     */
    public function getSession()
    {
        if (!$this->session) {
            $this->session = new Session();
            $this->session->setOptions($this->getConfigData('session'));

            $this->getMessages();
        }
        return $this->session;
    }

    /**
     * getTranslator
     *
     * @return Translator
     */
    public function getTranslator()
    {//TODO вставить в конфиг файл секцию translator
        if (!$this->translator) {
            $this->translator = new Translator();
            $this->translator->setOptions($this->getConfigData('translator'));
        }
        return $this->translator;
    }

    /**
     * return new instance of view
     *
     * @return View
     */
    public function getView()
    {
        // [!] Кажется, сюда вообще не заходит ни разу

        $view = new View();

        // setup default partial path
        $view->addPartialPath($this->getCorePath() . '/View/layouts/partial');

        return $view;
    }

    /**
     * isDebug
     *
     * @return boolean
     */
    public function isDebug()
    {
        return $this->debugFlag;
    }

    /**
     * hasLayout
     *
     * @return boolean|string
     */
    public function hasLayout()
    {
        return $this->layoutFlag;
    }

    /**
     * useLayout
     *
     * @param boolean|string $flag
     * @return Application
     */
    public function useLayout($flag = true)
    {
        if (is_string($flag)) {
            $this->getLayout()->setTemplate($flag);
            $this->layoutFlag = true;
        } else {
            $this->layoutFlag = $flag;
        }
        return $this;
    }

    /**
     * useJson
     *
     * @param boolean $flag
     * @return Application
     */
    public function useJson($flag = true)
    {
        if ($flag) {
            // disable view and layout for JSON output
            $this->useLayout(false);
        }
        $this->jsonFlag = $flag;
        return $this;
    }

    /**
     * process
     *
     * @return mixed
     */
    public function process()
    {
        $this->log('app:process');

        $this->getRequest();

        $this->getRouter()
            ->process();

        // check header "accept" for catch AJAX JSON requests, and switch to JSON response
        // for HTTP only
        // Если заголовок Accept содержит 	application/json, переключить на прием json'a
        // Ajax-запросы с Заголовком Accept = text/html сюда не попадают
        if (!$this->getRequest()->isCli()) {
            $accept = $this->getRequest()->getHeader('accept');
            $accept = explode(',', $accept);
            if ($this->getRequest()->isXmlHttpRequest()
                && in_array("application/json", $accept)
            ) {
                $this->useJson(true);
            }

        }


        //fb($this->request->getModule());
        //fb($this->request->getController());
        //fb($this->request->getAllParams());

        // try to dispatch controller
        try {
            $this->dispatchResult = $this->dispatch(
                $this->request->getModule(),
                $this->request->getController(),
                $this->request->getAllParams()
            );
        } catch (RedirectException $e) {
            $this->dispatchResult = $e;
        } catch (ReloadException $e) {
            $this->dispatchResult = $e;
        } catch (\Exception $e) {
            $this->dispatchResult = $this->dispatch(
                Router::ERROR_MODULE,
                Router::ERROR_CONTROLLER,
                array(
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                )
            );
        }

        return $this->dispatchResult;
    }

    /**
     * dispatch
     *
     * Call dispatch from any \Bluz\Package
     * <code>
     * $this->getApplication()->dispatch($module, $controller, array $params);
     * </code>
     *
     * Attach callback function to event "dispatch"
     * <code>
     * $this->getApplication()->getEventManager()->attach('dispatch', function($event) {
     *     $eventParams = $event->getParams();
     *     $app = $event->getTarget();
     *     \Bluz\Profiler::log('bootstrap:dispatch: '.$eventParams['module'].'/'.$eventParams['controller']);
     * });
     * </code>
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @throws \Exception
     * @return View
     */
    public function dispatch($module, $controller, $params = array())
    {
        $this->log("app:dispatch: " . $module . '/' . $controller);
        $controllerFile = $this->getControllerFile($module, $controller);
        $reflectionData = $this->reflection($controllerFile);

        if(app()->getInstance()->getRequest()->getHeader('X-Requested-With') == 'XMLHttpRequest'){
        // Этот запрос пришел через ajax
        // Такой заголовок есть у ВСЕХ ajax-запросов
            $this->useLayout(false);
        }


        // system trigger "dispatch"
        $this->getEventManager()->trigger(
            'dispatch',
            $this,
            array(
                'module' => $module,
                'controller' => $controller,
                'params' => $params,
                'reflection' => $reflectionData
            )
        );

        // check acl
        ///fb($module);
        ///fb($reflectionData);
        if (!$this->isAllowed($module, $reflectionData)) {
            $this->denied();
        }

        // check method(s)
        if (isset($reflectionData['method'])
            && !in_array($this->getRequest()->getMethod(), $reflectionData['method'])
        ) {
            throw new ApplicationException(join(',', $reflectionData['method']), 405);
        }

        // cache initialization
        if (isset($reflectionData['cache'])) {
            $cacheKey = $module . '/' . $controller . '/' . http_build_query($params);
            //$cacheKey = md5($cacheKey);

            if ($cachedView = $this->getCache()->get($cacheKey)) {
               return $cachedView;
            }
        }

        // process params
        $params = $this->params($reflectionData, $params);


        // $view for use in closure
        $view = $this->getView();
        // [!] Так работало, но ради эксперимента переключил на верхний вариант
        // $view = \Core\Helper\Registry::getInstance()->view;

        ///fb($view);

        // setup default path
        $view->setPath($this->getPath() . '/modules/' . ucfirst($module) . '/view');

        // setup default template ('Base')
        $view->setTemplate($controller . '.phtml');

        $bootstrapPath = $this->getPath() . '/modules/' . ucfirst($module) . '/bootstrap.php';

        /**
         * optional $bootstrap for use in closure
         * @var \closure $bootstrap
         */
        if (file_exists($bootstrapPath)) {
            $bootstrap = require $bootstrapPath;
        } else {
            $bootstrap = null;
        }
        unset($bootstrapPath);

        /**
         * @var \closure $controllerClosure
         */
        $controllerClosure = include $controllerFile;

        if (!is_callable($controllerClosure)) {
            throw new ApplicationException("Controller is not callable '$module/$controller'");
        }

        $result = call_user_func_array($controllerClosure, $params);

        // return false is equal to disable view and layout
        if ($result === false) {
            $this->useLayout(false);
            return $result;
        }

        // return closure is replace logic of controller
        // or return any class
        // [!] заходит сюда, если из главного контроллера модуля произведен вызов вспомогательного контроллера
        // Например, из catalog/Base идет вызов products.php через dispatch('catalog','products')
        if (is_callable($result) or
            is_object($result)
        ) {
            return $result;
        }

        // ЗДесь загружаем шаблон модуля
        // return string is equal to change view template
        if (is_string($result)) {
            $view->setTemplate($result);
        }

        // return array is equal to setup view
        if (is_array($result)) {
            $view->setData($result);
        }

        if (isset($reflectionData['cache'])) {
            $this->getCache()->set($cacheKey, $view, intval($reflectionData['cache']) * 60);
            $this->getCache()->addTag($cacheKey, 'view');
            $this->getCache()->addTag($cacheKey, 'view:' . $module);
            $this->getCache()->addTag($cacheKey, 'view:' . $module . ':' . $controller);
        }

        return $view; // Bluz\View\View
    }

    /**
     * reflection for anonymous function
     *
     * @param string $file
     * @throws \Exception
     * @return array
     */
    public function reflection($file)
    {
        // cache for reflection data
        // $this->getCache()->delete('reflection:' . $file);

        if (!$data = $this->getCache()->get('reflection:' . $file)) {

            // TODO: workaround for get reflection of closure function
            $bootstrap = $view = $module = $controller = null;
            $closure = include $file;

            if (!is_callable($closure)) {
                throw new \Exception("There is no closure in file $file");
            }

            // init data
            $data = array(
                'params' => array(),
                'values' => array(),
            );

            switch (get_class($closure)) {
                case 'Closure':
                    $reflection = new \ReflectionFunction($closure);
                    break;
                default:
                    $reflection = new \ReflectionObject($closure);
                    break;
            }

            // check and normalize params by doc comment
            $docComment = $reflection->getDocComment();

            // get all options by one regular expression
            if (preg_match_all('/\s*\*\s*\@([a-z0-9-_]+)\s+(.*).*/i', $docComment, $matches)) {
                foreach ($matches[1] as $i => $key) {
                    $data[$key][] = trim($matches[2][$i]);
                }
            }


            if (method_exists($reflection, 'getParameters')) {
                // get params and convert it to simple array
                $reflectionParams = $reflection->getParameters();

                // prepare params data
                // setup param types
                $types = array();
                if (isset($data['param'])) {
                    foreach ($data['param'] as $param) {
                        if (strpos($param, '$') === false) {
                            continue;
                        }
                        list($type, $key) = preg_split('/\$/', $param);
                        $type = trim($type);
                        if (!empty($type)) {
                            $types[$key] = $type;
                        }
                    }
                }

                // setup params and optional params
                $params = array();
                $values = array();
                foreach ($reflectionParams as $param) {
                    $name = $param->getName();
                    $params[$name] = isset($types[$name]) ? $types[$name] : null;
                    if ($param->isOptional()) {
                        $values[$name] = $param->getDefaultValue();
                    }
                }
                $data['params'] = $params;
                $data['values'] = $values;
            }

            // prepare cache ttl settings
            if (isset($data['cache'])) {
                $cache = current($data['cache']);
                $num = (int)$cache;
                $time = substr($cache, strpos($cache, ' '));
                switch ($time) {
                    case 'day':
                    case 'days':
                        $data['cache'] = (int)$num * 60 * 24;
                        break;
                    case 'hour':
                    case 'hours':
                        $data['cache'] = (int)$num * 60;
                        break;
                    case 'min':
                    default:
                        $data['cache'] = (int)$num;
                }
            }

            // prepare acl settings
            // only one privilege
            if (isset($data['privilege'])) {
                $data['privilege'] = current($data['privilege']);
            }

            // clean unused data
            unset($data['return'], $data['param']);

            $this->getCache()->set('reflection:' . $file, $data);
            $this->getCache()->addTag('reflection:' . $file, 'reflection');
        }
        return $data;
    }

    /**
     * render
     *
     * @return void
     */
    public function render()
    {
        $this->log('app:render');

        $result = $this->dispatchResult;

        /**
         * - Why you don't use "X-" prefix?
         * - Because it deprecated
         * @link http://tools.ietf.org/html/rfc6648
         */
        if ($result instanceof RedirectException) {
            if ($this->jsonFlag) {
                header('Bluz-Redirect: ' . $result->getMessage(), true, 204);
            } else {
                header('Location: ' . $result->getMessage(), true, $result->getCode());
            }
            return;
        } elseif ($result instanceof ReloadException) {
            if ($this->jsonFlag) {
                header('Bluz-Reload: true', true, 204);
            } else {
                header('Refresh: 15; url=' . $this->getRequest()->getRequestUri());
            }
            return;
        }

        if ($this->jsonFlag) {
            // Setup headers
            // HTTP does not define any limit
            // However most web servers do limit size of headers they accept.
            // For example in Apache default limit is 8KB, in IIS it's 16K.
            // Server will return 413 Entity Too Large error if headers size exceeds that limit

            // setup messages
            if ($this->hasMessages()) {
                header('Bluz-Notify: '.json_encode($this->getMessages()->popAll()));
            }

            // response without content
            if (false === $result) {
                return;
            }

            if (is_callable($result)) {

                $result = $result();
            }

            // prepare to JSON output
            $json = json_encode($result);


            // override response code so javascript can process it
            header('Content-Type: application/json');

            //fb($result);

            // send content length
            header('Content-Length: '.strlen($json));

            //ob_clean();
            flush();
            echo $json;


        } elseif ($this->layoutFlag) {
            $this->getLayout()->setContent($result);
            echo $this->getLayout();
        } else {
            /**
             * Can be Closure or any object with magic method '__invoke'
             */
            if (is_callable($result)) {
                $result = $result();
            }
            echo $result;
        }
    }

    /**
     * render for CLI
     *
     * @return void
     */
    public function output()
    {
        $this->log('app:output');

        $result = $this->dispatchResult;

        // get data from layout
        $data = $this->getLayout()->getData();

        // merge it with view data
        if ($result instanceof View) {
            $data = array_merge($data, $result->getData());
        }

        // inject messages if exists
        if ($this->hasMessages()) {
            while ($msg = $this->getMessages()->pop(Messages::TYPE_ERROR)) {
                echo "\033[41m\033[1;37mError    \033[m\033m: ";
                echo $msg->text . "\n";
            }
            while ($msg = $this->getMessages()->pop(Messages::TYPE_NOTICE)) {
                echo "\033[44m\033[1;37mInfo     \033[m\033m: ";
                echo $msg->text . "\n";
            }
            while ($msg = $this->getMessages()->pop(Messages::TYPE_SUCCESS)) {
                echo "\033[42m\033[1;37mSuccess  \033[m\033m: ";
                echo $msg->text . "\n";
            }
            echo "\n";
        }
        foreach ($data as $key => $value) {
            echo "\033[1;33m$key\033[m:\n";
            print_r($value);
            echo "\n";
        }
    }

    /**
     * widget
     *
     * Call widget from any \Bluz\Package
     * <code>
     * $this->getApplication()->widget($module, $widget, array $params);
     * </code>
     *
     * Attach callback function to event "widget"
     * <code>
     * $this->getApplication()->getEventManager()->attach('widget', function($event) {
     *     $eventParams = $event->getParams();
     *     $app = $event->getTarget();
     *     \Bluz\Profiler::log('bootstrap:dispatch: '.$eventParams['module'].'/'.$eventParams['widget']);
     * });
     * </code>
     *
     * @param string $module
     * @param string $widget
     * @param array $params
     * @throws Exception
     * @return \Closure
     */
    public function widget($module, $widget, $params = array())
    {
        $this->log(__METHOD__ . ": " . $module . '/' . $widget);
        $widgetFile = $this->getWidgetFile($module, $widget);
        $reflectionData = $this->reflection($widgetFile);


        $this->getEventManager()->trigger(
            'widget',
            $this,
            array(
                'module' => $module,
                'widget' => $widget,
                'params' => $params,
                'reflection' => $reflectionData
            )
        );

        // check acl
        if (!$this->isAllowed($module, $reflectionData)) {
            throw new AclException("Not enough permissions for call widget '$module/$widget'");
        }

        /**
         * Cachable widget
         * @var \Closure $widgetClosure
         */
        if (isset($this->widgets[$module])
            && isset($this->widgets[$module][$widget])
        ) {
            $widgetClosure = $this->widgets[$module][$widget];
        } else {
            $widgetClosure = include $widgetFile;

            if (!isset($this->widgets[$module])) {
                $this->widgets[$module] = array();
            }
            $this->widgets[$module][$widget] = $widgetClosure;
        }

        if (!is_callable($widgetClosure)) {
            throw new ApplicationException("Widget is not callable '$module/$widget'");
        }

        return $widgetClosure;
    }

    /**
     * api
     *
     * Call API from any \Bluz\Package
     * <code>
     * $this->getApplication()->api($module, $widget, array $params);
     * </code>
     *
     * Attach callback function to event "api"
     * <code>
     * $this->getApplication()->getEventManager()->attach('api', function($event) {
     *     $eventParams = $event->getParams();
     *     $app = $event->getTarget();
     *     \Bluz\Profiler::log('bootstrap:dispatch: '.$eventParams['module'].'/'.$eventParams['widget']);
     * });
     * </code>
     *
     * @param string $module
     * @param string $method
     * @throws Exception
     * @return \Closure
     */
    public function api($module, $method)
    {
        $this->log(__METHOD__ . ": " . $module . '/' . $method);

        $this->getEventManager()->trigger(
            'api',
            $this,
            array(
                'module' => $module,
                'method' => $method
            )
        );

        /**
         * Cachable APIs
         * @var \Closure $widgetClosure
         */
        if (isset($this->api[$module])
            && isset($this->api[$module][$method])
        ) {
            $apiClosure = $this->api[$module][$method];
        } else {
            $apiClosure = require $this->getApiFile($module, $method);

            if (!isset($this->api[$module])) {
                $this->api[$module] = array();
            }
            $this->api[$module][$method] = $apiClosure;
        }

        if (!is_callable($apiClosure)) {
            throw new ApplicationException("API is not callable '$module/$method'");
        }

        return $apiClosure;
    }

    /**
     * reflection for anonymous function
     *
     * @param string $file
     * @throws Exception
     * @return array
     *
     * Не совместима с php 5.3
     */

    /**
     * process params:
     *  - type conversion
     *  - default values
     *
     * @param array $reflectionData
     * @param array $rawData
     * @return array
     */
    private function params($reflectionData, $rawData)
    {
        // need use new array for order params as described in controller
        $params = array();

        foreach ($reflectionData['params'] as $param => $type) {

            if (isset($rawData[$param])) {
                switch ($type) {
                    case 'bool':
                    case 'boolean':
                        $params[] = (bool)$rawData[$param];
                        break;
                    case 'int':
                    case 'integer':
                        $params[] = (int)$rawData[$param];
                        break;
                    case 'float':
                        $params[] = (float)$rawData[$param];
                        break;
                    case 'string':
                        $params[] = (string)$rawData[$param];
                        break;
                    case 'array':
                        $params[] = (array)$rawData[$param];
                        break;
                    default:
                        $params[] = $rawData[$param];
                        break;
                }
            } elseif (isset($reflectionData['values'][$param])) {
                $params[] = $reflectionData['values'][$param];
            } else {
                $params[] = null;
            }
        }
        return $params;
    }

    /**
     * Is allowed controller/widget/etc
     *
     * @param string $module
     * @param array $reflection
     * @return boolean
     */
    public function isAllowed($module, $reflection)
    {
        if (isset($reflection['privilege'])) {
            return $this->getAcl()->isAllowed($module, $reflection['privilege']);
        }
        return true;
    }

    /**
     * Get controller file
     *
     * @param  string $module
     * @param  string $controller
     * @return string
     * @throws Exception
     *
     * Если контроллер - ajax, то ожидается, что есть третий параметр (имя файла в папке ajax данного контроллера)
     */
    public function getControllerFile($module, $controller)
    {
        if( preg_match('/(?:\?)/i', $controller) ){
            $controller = explode('?',$controller);
            if(isset($controller[0]))
                $controller = $controller[0];
        }

        if($controller == 'ajax'){
            $file = 'ajax';

            $uri = app()->getInstance()->getRequest()->requestUri ; // /module/ajax/file

            $uri_arr = explode('/',$uri);
            for($i = 0; $i< count($uri_arr); $i++){
                if($uri_arr[$i] == $controller ) {
                    $file =   $uri_arr[$i+1];
                    break;
                }
            }

            /*
             * способ 2
            $uri = str_replace("/",'',$uri);
            $uri = str_replace($module,'',$uri);
            $controller = str_replace($controller,'',$uri);
            */

            $controllerPath = $this->getPath() . '/modules/' . ucfirst($module)
                . '/ajax/' . $file . ".php" ;

        } elseif($controller == 'widget') {
            $uri = app()->getInstance()->getRequest()->requestUri ; // users/widget/cabinet

            $controllerPath = $this->getPath() . '/modules/' . ucfirst($module)
                . '/widget/' . app()->getRequest()->get(3) . ".php" ;
        } else {

            $controllerPath = $this->getPath() . '/modules/' . ucfirst( $module )
                . '/controller/' . $controller . '.php';


            if( !file_exists( $controllerPath ) ) {
                if( getenv( 'ZOQA_ENV' ) == 'debug' ) fb( "Debug info: " . __METHOD__ . " " . $controllerPath );
                throw new ApplicationException( "Controller not found '$module/$controller' [$controllerPath]", 404 );
            }
        }

        return $controllerPath;
    }

    /**
     * Get widget file
     *
     * @param  string $module
     * @param  string $widget
     * @return string
     * @throws \Exception
     */
    protected function getWidgetFile($module, $widget)
    {//fb("исправить this->getPath( getWidgetFile)"); // работает
        $widgetPath = $this->getPath() . '/modules/' . ucfirst($module)
            . '/widget/' . $widget . '.php';

        if (!file_exists($widgetPath)) {
            throw new ApplicationException("Widget not found '$module/$widget'");
        }

        return $widgetPath;
    }

    /**
     * Get API file
     *
     * @param  string $module
     * @param  string $method
     * @return \Closure
     * @throws \Exception
     */
    protected function getApiFile($module, $method)
    {//fb("исправить this->getPath( getApiFile)"); // работает
        $apiPath = $this->getPath() . '/modules/' .     ucfirst($module)
            . '/api/' . $method . '.php';



        if (!file_exists($apiPath)) {




            throw new ApplicationException("API not found '$module/$method'");
        }

        return $apiPath;
    }

    /**
     * Finally method
     *
     * @return Application
     */
    public function finish()
    {
        $this->log(__METHOD__);
        return $this;
    }

    /**
     * @param $old_name
     * @param $new_name
     * @param int $width
     * @param int $height
     * @param bool $white_back
     * @return bool|int (-100 означает, что Разрешение больше допустимого)
     * @author Махорин Александр
     *
     * Новый всеядный метод для создания thumbnailов строго фиксированной ширины и высоты из фоток.
     *  Если ширина или высота не задана (равна нулю), то фотка не обрезается,
     *  а просто недостающий параметр высчитывается пропорционально.
     * Расширение файла изображения сохраняется оригинальное.
     */
    static function imgCropAndResize($old_name, $new_name, $width = 0, $height = 0, $white_back = false)
    {
        $limit_resolution = 4000;

        //Если заданы неверные параметры
        if($width <= 0 && $height <= 0) return false;

        if(!is_file($old_name)) return -1;

        /*
          $path_info = pathinfo($_FILES["img"]['name']);
                $ext = $path_info['extension'];


        $ext_is_allowed = false;
        if($ext == 'jpg')  $ext_is_allowed = true;
        if($ext == 'jpeg')  $ext_is_allowed = true;
        if($ext == 'png')  $ext_is_allowed = true;
        if($ext == 'gif')  $ext_is_allowed = true;
        if(!$ext_is_allowed)
          return -101;

        $filesize =  $_FILES["userfile_$file_index"]['size'];
        if($filesize > 2048000)
          return -102;

          // --
           // проверить картинку
            $path_info = pathinfo($_FILES["img"]['name']);
            $ext = $path_info['extension'];


            if($ext == 'jpg' OR $ext == 'jpeg' OR $ext == 'png' OR $ext == 'gif'){}
            else
              return   $this->Msg('Допустимые расширени графических файлов: jpg, jpeg, png, gif', '/events/', 1000);

            $filesize =  $_FILES["img"]['size'];
            if($filesize > 2048000)
              return $this->Msg('Допустимый размер файла: 2МБ', '/events/', 1000);

            $size = GetImageSize($_FILES['img']['tmp_name']);
            if($size[0] > 3000 OR $size[1] > 3000)
               return $this->Msg('Допустимое разрешение 3000 х 3000 пикселей', '/events/', 1000);

          */
        // пример
        /*
           fb($size[0]); 3264
           fb($size[1]); 2448
           fb($old_name); // /tmp/phpDzbhS6
        */


        //Определяем размер фотографии
        $size = getimagesize ($old_name);




        if($size[0] > $limit_resolution OR $size[1] > $limit_resolution) return -100;

        //Создаём новое изображение из «старого»
        //определяем тип файла по содержимому
        if ($size['mime'] == "image/gif")  $src = imagecreatefromgif($old_name);
        if ($size['mime'] == "image/jpeg") $src = imagecreatefromjpeg($old_name);
        if ($size['mime'] == "image/png")  $src = imagecreatefrompng($old_name);
        if (!$src) return false;

        //Вычисляем ширину/высоту
        $iw=$size[0];
        $ih=$size[1];

        //Если всё больше нуля то создаем новую фотку с обрезкой
        if($width > 0 && $height > 0)
            $dst=ImageCreateTrueColor ($width, $height);

        //Если один из параметров == 0, то без обрезки
        //Недостающий параметр высчитывается пропорцией
        if($width == 0){
            $width = $iw*$height/$ih;
            $dst=ImageCreateTrueColor ($width, $height);
        }
        if($height == 0){
            $height = $ih*$width/$iw;
            $dst=ImageCreateTrueColor ($width, $height);
        }


        if ($size['mime'] == "image/png" AND $white_back === true)
        // Если это png, заливаем фон белым
        imagefilledrectangle($dst, 0, 0, $width, $height, imagecolorallocate($dst, 255, 255, 255));




        //Если высота исходной фотки > ширины
        if($ih > $iw){
            //вычисляем коэфициент
            //соотношения ширины оригинала с будущей превьюшкой.
            $koe=$iw/$width;
            //Делим высоту изображения на коэфициент, полученный в предыдущей строке,
            //и округляем число до целого в большую сторону — в результате получаем
            //высоту нового изображения.
            $new_h=ceil ($ih/$koe);

            //Вычисляем, какую высоту должна была бы иметь исходная фотка чтоб
            //попадать в наши пропорции (120х160 например для событий)
            $tmp = ceil($iw*$height/$width);

            //Если получается что эта "идеальная" высота больше реальной
            //обрезаем фотку по бокам, выравниванием по центру по горизонтали.

            if($tmp > $ih){
                $koe=$ih/$height;
                $new_w=ceil ($iw/$koe);

                //Теперь уже вычисляем "идеальную" ширину
                $tmp = ceil($ih*$width/$height);
                $shift_x = ceil($iw/2 - $tmp/2);

                ImageCopyResampled ($dst, $src, 0, 0, $shift_x, 0, $new_w, $height, $iw, $ih);
            }
            //Иначе обрезаем фотку сверху и снизу, выравниванием по центру по вертикали.
            else{
                $shift_y = ceil($ih/2 - $tmp/2);

                ImageCopyResampled ($dst, $src, 0, 0, 0, $shift_y, $width, $new_h, $iw, $ih);
            }
        }

        //Если высота исходной фотки < ширины
        else{
            $koe=$ih/$height;
            $new_w=ceil ($iw/$koe);

            $tmp = ceil($ih*$width/$height);

            if($tmp > $iw){


                $koe=$iw/$width;
                $new_h=ceil ($ih/$koe);

                //Теперь уже вычисляем "идеальную" ширину
                $tmp = ceil($iw*$height/$width);
                $shift_y = ceil($ih/2 - $tmp/2);

                /*$result = */ ImageCopyResampled ($dst, $src, 0, 0, 0, $shift_y, $width, $new_h, $iw, $ih);
            }
            else{

                $shift_x = ceil($iw/2 - $tmp/2);

                ImageCopyResampled ($dst, $src, 0, 0, $shift_x, 0, $new_w, $height, $iw, $ih);
            }
        }
        //Сохраняем полученное изображение в формате JPG
        ImageJPEG ($dst, $new_name, 100);
    }

    /**
     * Создает объект Yandex для общения с сервисами yandex
     * @return Yandex
     */
    function getYandex()
    {
        // Берем имя секции, которая хранит идентификационные данные для работы с сервисами Яндекса
       $default_yandex_account = $this->getConfigData($section = "auth", $subsection = "default_yandex_account");

        // Берем сами данные
       $config = $this->getConfigData($section = "auth", $subsection = "yandex_$default_yandex_account");

       if( AppRegistry::getInstance()->has("yandex") === false ){
           AppRegistry::getInstance()->yandex = new \Yandex($config,$default_yandex_account);
       }

        return AppRegistry::getInstance()->yandex;
    }


}
