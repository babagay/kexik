<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 08.04.14
 * Time: 13:55
 *
 * http://twig.sensiolabs.org/doc/advanced_legacy.html#creating-extensions
 *
 * Перед внесением нового метода, его нужно зарегистрировать в getFunctions()
 */

namespace Core\View;


use Application\Exception;
use Core\Helper\Registry;

class ZoqaTwigExtension extends \Twig_Extension{

    private $data = array();
    private $helpers = array();

    private $registry = null;

    private $globals = array();

    function __construct()
    {
        //$this->registry = \Core\Helper\Registry::getInstance();
        \Core\Helper\Registry::getInstance()->twig_view = $this;
    }

    function getName()
    {
        return 'zoqa_twig';
    }

    function getFilters(){
        return array(
            'rot13' => new \Twig_Filter_Function('str_rot13'),
        );
    }

    public function rot13Filter($string)
    {
        return str_rot13($string);
    }

    /**
     *
     * Глобальные переменные. Вызов: {{ text }}
     *
     * @return array
     */
    public function getGlobals()
    {
        /*
        return array(
            'text' =>   'text_test'
        ); */
        return $this->globals;
    }

    /**
     * Экспериментальный метод установки глобальной переменной
     * Как его вызвать из контроллера остается вопросом
     * @param $name
     * @param $value
     */
    function setGlobalParam($name,$value)
    {
        $this->globals[$name] = $value;
    }

    /**
     * Вызов: {{ method_name('param_value') }}
     *
     * @return array
     */
    function getFunctions()
    {
        return array(
            'api_closure' => new \Twig_Function_Method($this, 'api_closure'),
            'test' => new \Twig_Function_Method($this, 'test'),
            'title' => new \Twig_Function_Method($this, 'title'),
            'baseUrl' => new \Twig_Function_Method($this, 'baseUrl'),
            'url' => new \Twig_Function_Method($this, 'url'),
            'headScript' => new \Twig_Function_Method($this, 'headScript'),
            'headStyle' => new \Twig_Function_Method($this, 'headStyle'),
            'link' => new \Twig_Function_Method($this, 'link'),
            'meta' => new \Twig_Function_Method($this, 'meta'),
            'getContent' => new \Twig_Function_Method($this, 'getContent'),
            'trigger' => new \Twig_Function_Method($this, 'trigger'),
            'module' => new \Twig_Function_Method($this, 'module'),
            'controller' => new \Twig_Function_Method($this, 'controller'),
            'hasMessages' => new \Twig_Function_Method($this, 'hasMessages'),
            'getMessages' => new \Twig_Function_Method($this, 'getMessages'),
            'json_encode' => new \Twig_Function_Method($this, 'json_encode'),
            '__' => new \Twig_Function_Method($this, '__'),
            '_n' => new \Twig_Function_Method($this, '_n'),
            'ahref' => new \Twig_Function_Method($this, 'ahref'),
            'style' => new \Twig_Function_Method($this, 'style'),
            'array' => new \Twig_Function_Method($this, 'arrayEmulate'),
            'user' => new \Twig_Function_Method($this, 'user'),
            'attributes' => new \Twig_Function_Method($this, 'attributes'),
            'dispatch' => new \Twig_Function_Method($this, 'dispatch'),
            'widget' => new \Twig_Function_Method($this, 'widget'),
            'api' => new \Twig_Function_Method($this, 'api'),
            'partial' => new \Twig_Function_Method($this, 'partial'),
            'uniqid' => new \Twig_Function_Method($this, 'uniq_id'),
            'is_null' => new \Twig_Function_Method($this, 'is_null'),
            'breadCrumbs' => new \Twig_Function_Method($this, 'breadCrumbs'),
            'dump' => new \Twig_Function_Method($this, 'dump'),
            'publicUrl' => new \Twig_Function_Method($this, 'publicUrl'),
            'getRequest' => new \Twig_Function_Method($this, 'getRequest'),
            'setGlobalParam' => new \Twig_Function_Method($this, 'setGlobalParam'),
            'cropByWords' => new \Twig_Function_Method($this, 'cropByWords'),
            // new \Twig_SimpleFunction('lipsum', 'priceFilter'),
            //'getTest' => new \Twig_Function_Function('test')
        );
    }

    /**
     * @return \Bluz\View\View|string
     */
    function getContent()
    {
        return app()->getLayout()->getContent();
    }

    /**
     *
     */
    function trigger()
    {

    }

     function dump($var)
    {
        ob_start();
        var_dump($var);
        $d = ob_get_clean();
        file_put_contents( $_SERVER['DOCUMENT_ROOT'] . '/public/temp/dump.txt', $d);
    }

    function user($param = 'acl', $module = null, $privilege = null )
    {
        $result = false;

        switch($param){
            case 'auth':
                if( app()->getAuth()->getIdentity() ){
                    // user is authorized
                    $result = true;
                }
                break;
            case 'acl':
            // Взять определенный стандартный acl

                $app_object = app()->getInstance();

                $result = $app_object->getAcl()->isAllowed($module,$privilege);



                //fb($result);
                break;
            case 'info':
                $result = app()->getAuth()->getIdentity();
                break;
            default:
                break;
        }

        return $result;
    }

    function uniq_id($prefix = "", $more_entropy = false)
    {
        return uniqid($prefix, $more_entropy);
    }

    /**
     * @return string
     */
    function _module()
    {
        return app()->getInstance()->getRequest()->getModule();
    }

    /**
     * @return string
     */
    function controller()
    {
        return app()->getInstance()->getRequest()->getController();
    }

    function hasMessages()
    {
        //app()->hasMessages()

        $result = false;

        return $result;
    }

    function getMessages()
    {
        //json_encode(app()->getMessages()->popAll());
    }

    function json_encode()
    {

    }

    function arrayEmulate($param_1 = null, $param_2 = null, $param_3 = null, $param_4 = null, $param_5 = null,
                          $param_6 = null, $param_7 = null, $param_8 = null, $param_9 = null, $param_10 = null )
    {
        $result = array();

        if(!is_null($param_1)) $result[] = $param_1;
        if(!is_null($param_2)) $result[] = $param_2;
        if(!is_null($param_3)) $result[] = $param_3;
        if(!is_null($param_4)) $result[] = $param_4;
        if(!is_null($param_5)) $result[] = $param_5;
        if(!is_null($param_6)) $result[] = $param_6;
        if(!is_null($param_7)) $result[] = $param_7;
        if(!is_null($param_8)) $result[] = $param_8;
        if(!is_null($param_9)) $result[] = $param_9;
        if(!is_null($param_10)) $result[] = $param_10;

        return $result;
    }

    /**
     * translate
     *
     * <code>
     * // simple
     * // equal to gettext('Message')
     * __('Message');
     *
     * // simple replace of one or more argument(s)
     * // equal to sprintf(gettext('Message to %s'), 'Username')
     * __('Message to %s', 'Username');
     * </code>
     *
     * @param $message
     * @return mixed
     */
    function __($message)
    {
        return __($message);
        // return call_user_func_array(['\Bluz\Translator\Translator', 'translate'], func_get_args());
    }

    function is_null($value){
        return is_null($value);
    }

    /**
     * translate plural form
     *
     * <code>
     *
     * // plural form + sprintf
     * // equal to sprintf(ngettext('%d comment', '%d comments', 4), 4)
     * _n('%d comment', '%d comments', 4, 4)
     *
     * // plural form + sprintf
     * // equal to sprintf(ngettext('%d comment', '%d comments', 4), 4, 'Topic')
     * _n('%d comment to %s', '%d comments to %s', 4, 'Topic')
     * </code>
     *
     * @param $singular
     * @param $plural
     * @param $number
     * @return mixed
     */
    function _n($singular, $plural, $number)
    {
        return _n($singular, $plural, $number);
        //return call_user_func_array(['\Bluz\Translator\Translator', 'translatePlural'], func_get_args());
    }

    function publicUrl(){
        return Registry::getInstance()->view->getBaseUrl();
    }

    function getRequest(){
        return app()->getInstance()->getRequest();
    }


    /**
     * Include view helper
     *
     * @param $method
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $params)
    {
        // test
        // var_dump($this->registry);die;
        // $helper = \Core\Helper\Registry::getInstance()->view->getHelper();
        // fb($helper->helpers);
        // fb($this->helpers);
        // fb($method);


        if(!array_key_exists($method,$this->helpers)){
            @$helperInclude = include PATH_VIEW . "/helper/" . $method . ".php";

            if(is_callable($helperInclude)){
                $this->helpers[$method] = $helperInclude;
                return $this->$method($params);
            } else {
                throw new \Exception("Helper '$method' not found in " . PATH_ROOT . "/core/view/helper/" . $method);
            }
        } else {
            if (isset($this->helpers[$method]) && is_callable($this->helpers[$method])) {
                //FIXME. PHP берет параметры и запихивает их в ДОПОЛНИТЕЛЬНЫЙ массив
                /**
                 * это происходит из-за того, что сначала твиг пытается вызвать метод из кэш-файла,
                 * не находит, вызывает через __call() - это одна сборка в массив.
                 * Потом уже непосредственно идет вызов из ZoqaTwigExtension, метод не обнаружен, снова вызов __call()
                 * и вторая упаковка в массив
                 * Возможное решение - отказаться от использования __call в твиге.
                 */
                if($method == 'ahref'){
                   // fb('---- яArams: -------');
                   // fb($params);
                   // fb('-----------');
                   // dsb();
                }

                return call_user_func_array($this->helpers[$method], $params);
            } else {
                throw new \Exception("Helper '$method' is not exists in " . __METHOD__);
            }
        }


    }

    /**
     * @param null $param_1
     * @return callable
     *
     * $param_1 формируется в test()
     * $arg_1, $arg_2 формируются в шаблоне
     */
    function test_closure($param_1 = null){
        return function ($arg_1 = null, $arg_2 = null) use($param_1) {

            // Application, View
            $view = null;
            $app = null;
            /*
            $app = app()->getInstance();
            $view = $app->getView();
            // или
            $view = \Core\Helper\Registry::getInstance()->view;
            */

            return "$arg_1 $arg_2 [$param_1]";
        };
    }

    /**
     * stub
     */
    function _closure(){
        return function(){
            return 'This is closure stub';
        };
    }

    /**
     *
     */
    function getClosure($method_name){
        $method = '';

        $method_arr = explode('::',$method_name) ;

        if( isset($method_arr[1]) ){
            $method = $method_arr[1];
        }

        $method .= "_closure";

        // Список необязательных аргументов
        $args = array();

        $result = null;

        if(!method_exists($this,$method)){
            throw new Exception("Method $method does not exists");
        }

        $глазурь =  $this->$method($args);

        if(!is_callable($глазурь)){
            throw new Exception("Closure $method does not exists");
        }

        return $глазурь;
    }

    /**
     * @param null $param_1 - приходит из шаблона
     * @param null $param_2 -  приходит из шаблона
     * @return null
     * @throws \Application\Exception
     *
     * Метод вызывает замыкание __CLASS__::test_closure()
     */
    function test($param_1 = null, $param_2 = null)
    {
        $глазурь = $this->getClosure(__METHOD__);

        return  $глазурь($param_1, $param_2);
    }

    function _ahref($param_1 = null, $param_2 = null, $param_3 = null)
    {   
        $глазурь = $this->getClosure(__METHOD__);

        return  $глазурь($param_1, $param_2, $param_3);
    }

    /**
     * @param array $extra_params
     * @return callable
     * $text, $href, $attributes gets from template
     * $extra_params gets from getClosure()
     */
    function ahref_closure($extra_params = array()){
        return function ($text, $href, $attributes = array()) use($extra_params) {
            /*
            $app = app()->getInstance();
            $view = $app->getView();
            // или
            $view = \Core\Helper\Registry::getInstance()->view;
            */

            $result = '';

            // Application, View
            $view = null;
            $app = null;

            $view = \Core\Helper\Registry::getInstance()->view;
            
           /// fb( \Core\Helper\Registry::getInstance() -> helper );
            
            // if href is settings for url helper
            if (is_array($href)) {
                $href = call_user_func_array(array($view, 'url'), $href);
            }
 
            // href can be null, if access is denied
            if (null === $href) {
                return '';
            }

            if ($href == app()->getRequest()->getRequestUri()) {
                if (isset($attributes['class'])) {
                    $attributes['class'] .= ' on';
                } else {
                    $attributes['class'] = 'on';
                }
            }

            $attributes = $view->attributes($attributes);

            $result = '<a href="' . $href . '" ' . $attributes . '>' . __($text) . '</a>';

            return $result;
        };
    }

    function api($param_1 = null, $param_2 = null, $param_3 = null)
    {

        $глазурь = $this->getClosure(__METHOD__);

        return  $глазурь($param_1, $param_2, $param_3);
    }

    function api_closure($module, $method, $extra_params = array())
    {

        $application = app();
        try {
            $apiClosure = $application->api($module, $method);
            return call_user_func_array($apiClosure, $extra_params);
        } catch (\Exception $e) {
            if (app()->isDebug()) {
                // exception message for developers
                echo
                    '<div class="alert alert-error">' .
                    '<strong>API "' . $module . '/' . $method . '"</strong>: ' .
                    $e->getMessage() .
                    '</div>';
            }
        }

        // ------------------------
        return function ($module = null, $method = null, $params = array()) use($extra_params) {


            /*
            $app = app()->getInstance();
            $view = $app->getView();
            // или
            $view = \Core\Helper\Registry::getInstance()->view;
            */

            $result = '';

            // Application, View
            $view = null;
            $app = null;

            $view = \Core\Helper\Registry::getInstance()->view;

            // --
            $application = app();
            try {
                $apiClosure = $application->api($module, $method);
                return call_user_func_array($apiClosure, $params);
            } catch (\Exception $e) {
                if (app()->isDebug()) {
                    // exception message for developers
                    echo
                        '<div class="alert alert-error">' .
                        '<strong>API "' . $module . '/' . $method . '"</strong>: ' .
                        $e->getMessage() .
                        '</div>';
                }
            }

            return $result;
        };
    }

    function cropByWords($text, $words_num)
    {
        if ($words_num < 0 OR $words_num == 0) return $text;

        $arr = explode(" ", $text);

        $str = '';

        if (sizeof($arr))
            for ($t = 0; $t < sizeof($arr); $t++) {
                $str .= $arr[$t] . " ";
                if ($t > ($words_num - 2))
                    break;
            }


        return $str;
    }
} 