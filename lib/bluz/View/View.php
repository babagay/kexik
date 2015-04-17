<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\View;

use Application\Exception;
use Bluz\Application\Application;
use Bluz\Common\Helper;
use Bluz\Common\Options;
use Core\View\Zoqa_Twig_Extension;

/**
 * View
 *
 * @category Bluz
 * @package  View
 *
 * @method string ahref(\string $text, \string $href, array $attributes = [])
 * @method string api(\string $module, \string $method, $params = array())
 * @method string attributes(array $attributes = [])
 * @method string baseUrl(\string $file = null)
 * @method array|null breadCrumbs(array $data = [])
 * @method string checkbox($name, $value = null, $checked = false, array $attributes = [])
 * @method string|boolean controller(\string $controller = null)
 * @method string|View dispatch($module, $controller, $params = array())
 * @method string|null headScript(\string $script = null)
 * @method string|null headStyle(\string $style = null, $media = 'all')
 * @method string|View meta(\string $name = null, string $content = null)
 * @method string|boolean module(\string $module = null)
 * @method string|View link(string $src = null, string $rel = 'stylesheet')
 * @method string partial($__template, $__params = array())
 * @method string partialLoop($template, $data = [], $params = [])
 * @method string radio($name, $value = null, $checked = false, array $attributes = [])
 * @method string script(\string $script)
 * @method string select($name, array $options = [], $selected = null, array $attributes = [])
 * @method string style(\string $style, $media = 'all')
 * @method string|View title(\string $title = null, $position = 'replace', $separator = ' :: ')
 * @method string|View url(\string $module, \string $controller, array $params = [], boolean $checkAccess = false)
 * @method \Bluz\Auth\AbstractRowEntity|null user()
 * @method void widget($module, $widget, $params = [])
 *
 * @author   Anton Shevchuk, ErgallM
 * @created  08.07.11 11:49
 */
class View extends Options implements ViewInterface , \JsonSerializable
{
    //use Options;
    //use Helper;
    //FIXME Helper надо создавать здесь в виде $this->helper = new Helper();

    /**
     * Constants for define positions
     */
    const POS_PREPEND = 'prepend';
    const POS_REPLACE = 'replace';
    const POS_APPEND = 'append';

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var array
     */
    protected static $helpersPath = array();

    /**
     * View variables
     *
     * @var array
     */
    protected $data = array();

    /**
     * System variables, should be uses for helpers
     *
     * @var array
     */
    protected $system = array();

    /**
     * @var string path to template
     */
    protected $path;

    /**
     * @var array paths to partial
     */
    protected $partialPath = array();

    /**
     * @var string
     */
    protected $template;

    /*
     *
     */
    protected static $instance;

    //FIXME добавлена переменная
    protected $helper;
    protected $helpers = array();

    /**
     * @var null|object
     */
    protected $twig = null;

    /**
     * @var null|obj
     */
    protected $twig_loader = null;

    /**
     * @var null|array
     */
    protected $twig_data = array();

    /**
     * Test var
     *
     * @var null
     */
   // protected $asd = null;


    /**
     * __construct
     *
     * @return self
     */
    public function __construct()
    {
        ///debug_print_backtrace();

        if (is_null($this->helper))
            $this->helper = new Helper();

        // initial default helper path. Пути для helper'a добавлять здесь.
        // Последовательность имеет зачение
        $this->helper->addHelperPath(PATH_CORE . '/View/helper/'); // /Core/View/helper/
        $this->helper->addHelperPath(dirname(__FILE__) . '/Helper/'); // /lib/bluz/View/Helper/

        $this->addHelperPath(PATH_CORE . '/View/helper/');
        $this->addHelperPath(dirname(__FILE__) . '/Helper/');

        // Twig
        $this->setTwig();

        // Save to Registry
        \Core\Helper\Registry::getInstance()->view = $this;

        // Layout
        // FIXME бесконечная рекурсия
        //\Core\Helper\Registry::getInstance() -> layout = new Layout();
        //var_dump( \Core\Helper\Registry::getInstance() -> layout  );

        $this->baseUrl = PUBLIC_URL;

    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function getHelper(){
        return $this->helper;
    }

    /**
     * Twig
     *
     * Example: $template = $this->twig->loadTemplate('index.html');
     *          echo $template->render(array('asd' => 'zxc'));
     */
    private function setTwig()
    {
        require_once PATH_LIB . '/twig/twig/lib/Twig/Autoloader.php';

        \Twig_Autoloader::register();

        try {
            $this->twig_loader = new \Twig_Loader_Filesystem(PATH_CORE . '/View/templates');
        } catch (Exception $e) {
            // } catch(\Exception $e) {
            // Так можно перехватить исключение. Если этого не делать, оно уйдет в стандартный вывод
            if (app()->isDebug()) {
                var_dump($e->getMessage() . "\n<br/>" . $e->getTraceAsString());
            }
        }

        $this->twig = new \Twig_Environment($this->twig_loader, array(
            'cache' => PATH_CACHE,
            'debug' => true,
        ));

        $this->twig->addExtension(new \Core\View\ZoqaTwigExtension());

    }

    /**
     * Add helper path
     *
     * @param string $path
     * @return self
     */
    public function addHelperPath($path)
    {

        $this->helper->addHelperPath($path);
        //$this->helper->addHelperPath(dirname(__FILE__) . '/Helper/');

        // FIXME: конструкция не работает, т.к. пытается найти хелпер getHelperPath
        //$this->helpers = $this->helper->getHelperPath();

        //FIXME вставил сюда реализацию метода, т.к Helper был трейтом
        $path = rtrim(realpath($path), '/');
        if (false !== $path && !in_array($path, self::$helpersPath)) {
            self::$helpersPath[] = $path;
        }

        return $this;

    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }

        return self::$instance;
    }

    /**
     * __sleep
     *
     * @return array
     */
    public function __sleep()
    {
        return array('baseUrl', 'data', 'system', 'path', 'template');
    }

    /**
     * Get a variable
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return null;
        }
    }


    /**
     * Is set a variable
     *
     * @param string $key
     * @return mixed
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Assign a variable
     *
     * A $value of null will unset the $key if it exists
     *
     * @param string $key
     * @param mixed $value
     * @throws ViewException
     * @return View
     */
    public function __set($key, $value)
    {
        if (!is_string($key)) {
            throw new ViewException("You can't use `" . gettype($key) . "` as identity of Views key");
        }

        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Unset variable
     *
     * @param $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->data[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data = array())
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * merge data from array
     *
     * @param array $data
     * @return View
     */
    public function mergeData($data = array())
    {
        $this->data = array_replace_recursive($this->data, $data);
        return $this;
    }

    /**
     * is callable
     *
     * @return string
     */
    public function __invoke()
    {
        return $this->render();
    }

    /**
     * render like string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Implement JsonSerializable
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function setTemplate($file)
    {
        $this->template = $file;
        return $this;
    }

    /**
     * Add partial path for use inside partial and partialLoop helpers
     *
     * @param $path
     * @return View
     */
    public function addPartialPath($path)
    {
        $this->partialPath[] = $path;
        return $this;
    }

    function getPartialPath()
    {
        return $this->partialPath;
    }

    /**
     * manipulation under system stack
     *
     * @param string $key
     * @param mixed|null $value
     * @return mixed|View
     */
    function system($key, $value = null)
    {

        if (self::$instance === null)
            self::$instance = self::getInstance();

        if (null === $value) {
            if (isset(self::$instance->system[$key])) {
                return self::$instance->system[$key];
            } else {
                return null;
            }
        } else {
            self::$instance->system[$key] = $value;
        }
        return self::$instance;
    }

    /**
     * addTwigParam - добавляет переменную в глобальный шаблон
     * @param $name
     * @param $value
     */
    function addTwigParam($name,$value)
    {
        $this->twig_data[$name] = $value;
        \Core\Helper\Registry::getInstance()->twig_data = $this->twig_data;
    }

    /**
     * Twig render
     *
     * @return string
     */
    public function TwigRender($template = null, $path = null, $data = null)
    {
        // пересылка в twig тестовых переменных
        // [!] twig_data надо пробрасывать через реестр, т.к. TwigRender() вызывается 2 раза (это видно в  \FB::trace('asd');) и на втором проходе поля класса View сбрасываются
        //$this->twig_data = array(
            //  'request' => new \Bluz\Request\HttpRequest(),
            //'zoqa_title' => app()->getInstance()->getRequest()->getController(),
            //'zoqa_title' => $this->title(),

            // извращение - передача в твиг самого себя
            //   'view' => &$this
            //    'view' => "asd"
        //);

        $twig_data = array();
        if( \Core\Helper\Registry::getInstance()->has('twig_data') )
            $twig_data = \Core\Helper\Registry::getInstance()->twig_data;
        $this->twig_data = array_merge($this->twig_data,$twig_data );

        if(!is_null($data)){
            $this->twig_data = array_merge($this->twig_data, $data);
        }
        if(!is_null($path)){
            $this->path = $path;
        }
        if(!is_null($template)){
            $this->template =  $template;
        }

        // пересылка в twig переменных из контроллера
        $this->twig_data = array_merge($this->twig_data, $this->data);

        $this->twig_loader->addPath($this->path);

        try {
            // При использовании twig вставил htmlspecialchars_decode()

            return htmlspecialchars_decode($this->twig->render($this->template, $this->twig_data));

        } catch (\Exception $e) {
            if (app()->isDebug()) {
                //throw new ViewException("");
                return "<pre>" . $e->getMessage() . "</pre>";
            }
        }
    }

    /**
     * Render
     *
     * @throws ViewException
     * @return string
     */
    public function render()
    {
        // Use twig rendering as alternative
        return $this->TwigRender();

        ob_start();
        try {
            if (!file_exists($this->path . '/' . $this->template)) {
                throw new ViewException("Template " . $this->path . " <b>'{$this->template}'</b> not found");
            }
            extract($this->data);

            require $this->path . '/' . $this->template;


        } catch (\Exception $e) {
            // clean output
            ob_end_clean();
            if (app()->isDebug()) {
                return $e->getMessage() . "\n<br/>" . $e->getTraceAsString();
            }
            // nothing for production
            return '';
        }
        return ob_get_clean();
    }

    /**
     * Call
     *
     * @param string $method
     * @param array $args
     * @throws \Exception
     * @return mixed
     */
    public function __call($method, $args)
    {
        //FIXME метод title() из контроллера не влияет на тайтл
        //FIXME метод attributes() из замыкания контроллера не вызывается


        // [!] Такой вызов заворачивает параметры $args в дополнительный массив
        //return $this->helper->$method($args);


        //FIXME реализация взята из Helper'а. Метод вызывает вью-хелпер в виде замыкания

        // Call helper function (or class)
        // Попробовал заменить вызов замыкания с целью избежать заворачивания в массив, 
        // но после этого в хелперах (например, breadCrumbs) произошло дополнительное обворачивание, которого ранее не было, поэтому, вернул назад
        //$m = $this->helpers[$method];            
        //return $m($args);
        if (isset($this->helpers[$method]) && is_callable($this->helpers[$method])) { 
            
            return call_user_func_array($this->helpers[$method], $args);
        }

        // Try to find helper file
        foreach (self::$helpersPath as $helperPath) {
            $helperPath = realpath($helperPath . '/' . lcfirst($method) . '.php');
            if ($helperPath) {
                $helperInclude = include $helperPath;
                if (is_callable($helperInclude)) {
                    $this->helpers[$method] = $helperInclude;
                } else {
                    throw new \Exception("Helper '$method' not found in file '$helperPath'");
                }

                return $this->__call($method, $args);
            } else {
              $helperPath = realpath($helperPath . '/' . ucfirst($method) . '.php');
              if ($helperPath) {
                $helperInclude = include $helperPath;
                if (is_callable($helperInclude)) {
                    $this->helpers[$method] = $helperInclude;
                } else {
                    throw new \Exception("Helper '$method' not found in file '$helperPath'");
                }

                return $this->__call($method, $args);
              }
            }
        }
        throw new \Exception("Helper '$method' not found for '" . __CLASS__ . "'");
    }
}
