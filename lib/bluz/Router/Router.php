<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Router;

use Bluz\Common\Options;

/**
 * Router
 *
 * @category Bluz
 * @package  Router
 *
 * @author   Anton Shevchuk
 * @created  06.07.11 18:16
 */
class Router extends Options
{
    //use Options;

    /**
     * Or should be as properties?
     */
    const DEFAULT_MODULE = 'index';
    const DEFAULT_CONTROLLER = 'index';
    const ERROR_MODULE = 'error';
    const ERROR_CONTROLLER = 'index';

    /**
     * @var array
     */
    protected $routers = array();

    /**
     * @var array
     */
    protected $reverse = array();

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @return self
     */
    public function __construct()
    {
        $routers = app()->getCache()->get('router:routers');
        $reverse = app()->getCache()->get('router:reverse');

        if (!$routers or !$reverse) {
            $routers = array();
            $reverse = array();


            //foreach (new \GlobIterator(app()->getPath() . '/modules/*/controllers/*.php') as $file) {
            foreach (glob(app()->getPath() . '/modules/*/controller/*.php') as $file) {
                /* @var \SplFileInfo $file */
                //$module = pathinfo(dirname(dirname($file->getPathname())), PATHINFO_FILENAME);
                $module = pathinfo(dirname(dirname(realpath($file))), PATHINFO_FILENAME);


                $controller = pathinfo(realpath($file), PATHINFO_FILENAME);
                $data = app()->reflection(realpath($file));


                if (isset($data['route'])) {
                    foreach ((array)$data['route'] as $route) {
                        $route = trim($route);

                        if (!isset($reverse[$module])) {
                            $reverse[$module] = array();
                        }

                        $reverse[$module][$controller] = array('route' => $route, 'params' => $data['params']);

                        $pattern = str_replace('/', '\/', $route);

                        foreach ($data['params'] as $param => $type) {
                            switch ($type) {
                                case 'int':
                                case 'integer':
                                    $pattern = str_replace("{\$" . $param . "}", "(?P<$param>[0-9]+)", $pattern);
                                    break;
                                case 'float':
                                    $pattern = str_replace("{\$" . $param . "}", "(?P<$param>[0-9.,]+)", $pattern);
                                    break;
                                case 'string':
                                case 'module':
                                case 'controller':
                                    $pattern = str_replace(
                                        "{\$" . $param . "}",
                                        "(?P<$param>[a-zA-Z0-9-_.]+)",
                                        $pattern
                                    );
                                    break;
                            }
                        }
                        $pattern = '/^' . $pattern . '/i';

                        $rule = array(
                            $route => array(
                                'pattern' => $pattern,
                                'module' => $module,
                                'controller' => $controller,
                                'params' => $data['params']
                            )
                        );

                        // static routers should be first
                        if (strpos($route, '$')) {
                            $routers = array_merge($routers, $rule);
                        } else {
                            $routers = array_merge($rule, $routers);
                        }
                    }
                }
            }
            app()->getCache()->set('router:routers', $routers);
            app()->getCache()->set('router:reverse', $reverse);
        }

        $this->routers = $routers;
        $this->reverse = $reverse;
    }

    /**
     * getBaseUrl
     * always return string with slash at end
     * @return string
     */
    public function getBaseUrl()
    {
        if (!$this->baseUrl) {
            $this->baseUrl = app()
                ->getRequest()
                ->getBaseUrl();
        }
        return $this->baseUrl;
    }

    /**
     * getFullUrl
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @return string
     */
    public function getFullUrl(
        $module = self::DEFAULT_MODULE,
        $controller = self::DEFAULT_CONTROLLER,
        $params = array()
    ) {
        $scheme = app()->getRequest()->getScheme() . '://';
        $host = app()->getRequest()->getHttpHost();
        $url = $this->url($module, $controller, $params);

        // Если $url уже содержит протокол и доменное имя, вернуть только $url
        if( preg_match('/(http)/i',$url) ){
            if( preg_match("/($host)/i",$url) ) {
                return $url;
            }
        }

        return $scheme . $host . $url;
    }

    /**
     * build URL
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @return string
     */
    public function url($module = self::DEFAULT_MODULE, $controller = self::DEFAULT_CONTROLLER, $params = array())
    {
        if (null === $module) {
            $module = app()->getRequest()->getModule();
        }

        if (null === $controller) {
            $controller = app()->getRequest()->getController();
        }

        if (empty($this->routers)) {
            return $this->urlRoute($module, $controller, $params);
        } else {
            if (isset($this->reverse[$module], $this->reverse[$module][$controller])) {
                return $this->urlCustom($module, $controller, $params);
            }
            return $this->urlRoute($module, $controller, $params);
        }
    }


    /**
     * build URL by default route
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @return string
     */
    public function urlCustom($module, $controller, $params)
    {
        $url = $this->reverse[$module][$controller]['route'];

        $getParams = array();
        foreach ($params as $key => $value) {
            // sub-array as GET params
            if (is_array($value)) {
                $getParams[$key] = $value;
                continue;
            }
            $url = str_replace('{$' . $key . '}', $value, $url, $replaced);
            // if not replaced, setup param as GET
            if (!$replaced) {
                $getParams[$key] = $value;
            }
        }
        // clean optional params
        $url = preg_replace('/\{\$[a-z0-9-_]+\}/i', '', $url);
        // replace "//" with "/"
        $url = str_replace('//', '/', $url);

        if (!empty($getParams)) {
            $url .= '?' . http_build_query($getParams);
        }
        return $this->getBaseUrl() . ltrim($url, '/');
    }

    /**
     * build URL by default route
     *
     * @param string $module
     * @param string $controller
     * @param array $params
     * @return string
     */
    public function urlRoute($module, $controller, $params)
    {
        $url = $this->getBaseUrl();

        if (empty($params)) {
            if ($controller == self::DEFAULT_CONTROLLER) {
                if ($module == self::DEFAULT_MODULE) {
                    return $url;
                } else {
                    return $url . $module;
                }
            }
        }

        $url .= $module . '/' . $controller;
        $getParams = array();
        foreach ($params as $key => $value) {
            // sub-array as GET params
            if (is_array($value)) {
                $getParams[$key] = $value;
                continue;
            }

            $url .= '/' . urlencode($key) . '/' . urlencode($value);
        }
        if (!empty($getParams)) {
            $url .= '?' . http_build_query($getParams);
        }
        return $url;
    }

    /**
     * process
     *
     * @return \Bluz\Router\Router
     */
    public function process()
    {
        switch (true) {
            // try process default router
            case $this->processDefault():
                break;
            // try process custom routers
            case $this->processCustom():
                break;
            // try process router
            case $this->processRoute():
                break;
        }

        return $this;
    }

    /**
     * process default router
     *
     * @return boolean
     */
    protected function processDefault()
    {
        $uri = app()->getRequest()->getCleanUri();
        return empty($uri);
    }


    /**
     * process custom router
     *
     * @return boolean
     */
    protected function processCustom()
    {
        $request = app()->getRequest();
        $uri = '/' . $request->getCleanUri();
        foreach ($this->routers as $router) {
            if (preg_match($router['pattern'], $uri, $matches)) {
                $request->setModule($router['module']);
                $request->setController($router['controller']);

                foreach ($router['params'] as $param => $type) {
                    if (isset($matches[$param])) {
                        $request->{$param} = $matches[$param];
                    }
                }
                return true;
            }
        }
        return false;
    }

    /**
     * process default router
     * Метод выставляет имя Контроллера на основе парсинга параметров
     * Логика: контроллер/имя файла контроллера/параметры
     * Пример: my/Base/question/2+2
     *
     * <code>
     *  /
     *  /:module/
     *  /:module/:controller/
     *  /:module/:controller/:key1/:value1/:key2/:value2...
     * </code>
     *
     * @return boolean
     */
    protected function processRoute()
    {
        $request = app()->getRequest();


        /*
        $uri = $request->getCleanUri();
        $uri = trim($uri, '/');
        $params = explode('/', $uri);
        */

        $my_uri = $request->selfURL();
        $my_uri = str_replace(PUBLIC_URL,'', $my_uri);
        $my_uri = trim($my_uri, '/');
        $params = explode('/', $my_uri);

        // virtual_modules
        $virtual_modules =  \Core\Helper\Registry::getInstance() -> config -> virtual_modules;

        $arg_first = \Bluz\Translator\Translator::translit($request -> getURIarg(1));

        if(is_array($virtual_modules)){
            if(isset($virtual_modules[$arg_first])){
            // Its a virtual module
                if(is_array($virtual_modules[$arg_first])){
                    // real_module_name
                    $params[0] = $virtual_modules[$arg_first][0];
                    // real file name
                    $params[1] = $virtual_modules[$arg_first][1];
                }
            }
        }

        /*
        $virtual_module_name = 'virtual_module';
        $real_module_name = 'Error';
        $controller_name  = 'index';

        if($request -> getURIarg(1) == $virtual_module_name){
            $params[0] = $real_module_name;
            $params[1] = $controller_name;
        }
        */
        // --

        if( count($params) == 1 AND (bool)$params[0] === false){
            $params[0] = 'index';
        }

        if (sizeof($params)) {
            $request->setModule(array_shift($params));
        }
        if (sizeof($params)) {
            $request->setController(array_shift($params));
        }
        if ($size = sizeof($params)) {
            // save raw params
            $request->setRawParams($params);

            // remove tail
            if ($size % 2 == 1) {
                array_pop($params);
                $size = sizeof($params);
            }
            // or use array_chunk and run another loop?
            for ($i = 0; $i < $size; $i = $i + 2) {
                $request->{$params[$i]} = $params[$i + 1];
            }
        }

        return true;
    }


}
