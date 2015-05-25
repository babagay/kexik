<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Grid;

use Bluz\Application\Application;
use Bluz\Common\Helper;
use Bluz\Common\Options;
use Core\Helper\Registry;

/**
 * Grid
 *
 * @category Bluz
 * @package  Grid
 *
 * @method string filter($column, $filter, $value, $reset = true)
 * @method string first()
 * @method string last()
 * @method string limit($limit = 25)
 * @method string next()
 * @method string order($column, $order = null, $defaultOrder = Grid::ORDER_ASC, $reset = true)
 * @method string page($page = 1)
 * @method string pages()
 * @method string prev()
 * @method string reset()
 * @method string total()
 *
 * @author   Anton Shevchuk
 * @created  15.08.12 11:52
 */
abstract class Grid extends Options
{
    //use Options;
    //use Helper;

    const ORDER_ASC = 'asc';
    const ORDER_DESC = 'desc';

    const FILTER_LIKE = 'like'; // like
    const FILTER_ENUM = 'enum'; // one from .., .., ..
    const FILTER_NUM = 'num'; // ==, !=, >, >=, <, <=

    const FILTER_EQ = 'eq'; // equal to ..
    const FILTER_NE = 'ne'; // not equal to ..
    const FILTER_GT = 'gt'; // greater than ..
    const FILTER_GE = 'ge'; // greater than .. or equal
    const FILTER_LT = 'lt'; // less than ..
    const FILTER_LE = 'le'; // less than .. or equal

    /**
     * @var Source\AbstractSource
     */
    protected $adapter;

    /**
     * @var Data
     */
    protected $data;

    /**
     * Unique identification of grid
     *
     * @var string
     */
    protected $uid;

    /**
     * Unique prefix of grid
     *
     * @var string
     */
    protected $prefix;

    /**
     * Location of Grid
     * @var string $module
     */
    protected $module;

    /**
     * Location of Grid
     * @var string $controller
     */
    protected $controller;

    /**
     * Custom array params
     * @var array
     */
    protected $params = array();

    /**
     * Start from 1!
     *
     * @var int
     */
    protected $page = 1;

    /**
     * @var int
     */
    protected $limit = 25;

    /**
     * @var int
     */
    protected $defaultLimit = 25;

    /**
     * @var string
     */
    protected $defaultOrder;

    /**
     * <pre>
     * <code>
     * [
     *     'first' => 'ASC',
     *     'last' => 'ASC'
     * ]
     * </code>
     * </pre>
     * @var array
     */
    protected $orders = array();

    /**
     * <pre>
     * <code>
     * ['first', 'last', 'email']
     * </code>
     * </pre>
     * @var array
     */
    protected $allowOrders = array();

    /**
     * @var array
     */
    protected $filters = array();

    /**
     * <pre>
     * <code>
     * ['id', 'status' => ['active', 'disable']]
     * </code>
     * </pre>
     *
     * @var array
     */
    protected $allowFilters = array();

    protected $fulltext_search = false;


    protected static $helpersPath = array();

    protected $helpers = array();

    /*
    public function asd(){
        return 'Asd';
    }
    */

    /**
     * __construct
     *
     * @param array $options
     * @return Grid
     */
    public function __construct($options = null)
    {
        if ($options) {
            $this->setOptions($options);
        }

        if ($this->uid) {
            $this->prefix = $this->getUid() . '-';
        } else {
            $this->prefix = '';
        }

        $this->init();

        $this->processRequest();
        // initial default helper path
        \Core\Helper\Registry::getInstance()->view->getHelper()->
        /*$this->*/addHelperPath(dirname(__FILE__) . '/Helper/');
    }

    /**
     * init
     *
     * @return Grid
     */
    abstract public function init();

    /**
     * setAdapter
     *
     * @param Source\AbstractSource $adapter
     * @return Grid
     */
    public function setAdapter(Source\AbstractSource $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * getAdapter
     *
     * @throws GridException
     * @return Source\AbstractSource
     */
    public function getAdapter()
    {
        if (null == $this->adapter) {
            throw new GridException('Grid adapter is not initialized');
        }
        return $this->adapter;
    }

    /**
     * getUid
     *
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * getPrefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * setModule
     *
     * @param $module
     * @return self
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * getModule
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * setController
     *
     * @param $controller
     * @return self
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * getController
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * process request
     *
     * <code>
     * // example of request url
     * // http://domain.com/pages/grid/
     * // http://domain.com/pages/grid/page/2/
     * // http://domain.com/pages/grid/page/2/order-alias/desc/
     * // http://domain.com/pages/grid/page/2/order-created/desc/order-alias/asc/
     *
     * // with prefix for support more than one grid on page
     * // http://domain.com/users/grid/users-page/2/users-order-created/desc/
     * // http://domain.com/users/grid/users-page/2/users-filter-status/active/
     *
     * // hash support
     * // http://domain.com/pages/grid/#/page/2/order-created/desc/order-alias/asc/
     *
     * </code>
     *
     * @return Grid
     */
    public function processRequest()
    {
        $request = app()->getRequest();

        $this->module = $request->getModule();
        $this->controller = $request->getController();

        $page = $request->getParam($this->prefix . 'page', 1);
        $this->setPage($page);

        $limit = $request->getParam($this->prefix . 'limit', $this->limit);
        $this->setLimit($limit);

        foreach ($this->allowOrders as $column) {
            $order = $request->getParam($this->prefix . 'order-' . $column);
            if ($order) {
                $this->addOrder($column, $order);
            }
        }

        foreach ($this->allowFilters as $column) {
            $filter = $request->getParam($this->prefix . 'filter-' . $column);
            if ($filter) {
                if (strpos($filter, '-')) {
                    $filter = trim($filter, ' -');

                    while ($pos = strpos($filter, '-')) {

                        $filterType = substr($filter, 0, $pos);
                        $filter = substr($filter, $pos + 1);

                        if ($pos = strpos($filter, '-')) {
                            $filterValue = substr($filter, 0, strpos($filter, '-'));
                            $filter = substr($filter, strpos($filter, '-') + 1);
                        } else {
                            $filterValue = $filter;
                        }

                        $this->addFilter($column, $filterType, $filterValue);
                    }

                } else {
                    $this->addFilter($column, self::FILTER_EQ, $filter);
                }
            }
        }
        return $this;
    }

    /**
     * processSource
     *
     * @throws GridException
     * @return self
     */
    public function processSource()
    {
        if (null === $this->adapter) {
            throw new GridException("Grid Adapter is not initiated, please update method init() and try again");
        }

        try {
            $this->data = $this->getAdapter()->process($this->getSettings());
        } catch (\Exception $e) {
            throw new GridException("Grid Adapter can't process request: ". $e->getMessage());
        }

        return $this;
    }

    /**
     * getData
     *
     * @return Data
     */
    public function getData()
    {
        if (!$this->data) {
            $this->processSource();
        }
        return $this->data;
    }

    /**
     * getSettings
     *
     * @return array
     */
    public function getSettings()
    {
        $settings = array();
        $settings['page'] = $this->getPage();
        $settings['limit'] = $this->getLimit();
        $settings['orders'] = $this->getOrders();
        $settings['filters'] = $this->getFilters();
        return $settings;
    }

    /**
     * setup params
     *
     * @param $params
     * @return Grid
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * return params prepared for url builder
     *
     * @param array $rewrite
     * @return array
     */
    public function getParams(array $rewrite = array())
    {
        $params = $this->params;

        // orders_id - параметр введен мной
        if (isset($rewrite['orders_id']) && (int)$rewrite['orders_id'] > 0) {
            $params['orders_id'] = $rewrite['orders_id'];
        }

        // change page
        if (isset($rewrite['page']) && (int)$rewrite['page'] > 1) {
            $params[$this->prefix . 'page'] = $rewrite['page'];
        }

        // change limit
        if (isset($rewrite['limit'])) {
            if ($rewrite['limit'] != $this->defaultLimit) {
                $params[$this->prefix . 'limit'] = ($rewrite['limit'] != $this->limit)
                    ? $rewrite['limit'] : $this->limit;
            }
        } else {
            if ($this->limit != $this->defaultLimit) {
                $params[$this->prefix . 'limit'] = $this->limit;
            }
        }

        // change orders
        if (isset($rewrite['orders'])) {
            $orders = $rewrite['orders'];
        } else {
            $orders = $this->getOrders();
        }

        foreach ($orders as $column => $order) {
            $params[$this->prefix . 'order-' . $column] = $order;
        }

        // change filters
        if (isset($rewrite['filters'])) {
            $filters = $rewrite['filters'];
        } else {
            $filters = $this->getFilters();
        }
        foreach ($filters as $column => $columnFilters) {


            $columnFilter = array();
            foreach ($columnFilters as $filterName => $filterValue) {
                $filterValue = \Bluz\Translator\Translator::translit(str_replace("fulltext-","",$filterValue));
                if ($filterName == self::FILTER_EQ) {
                    $columnFilter[] = $filterValue;
                } else {
                    $columnFilter[] = $filterName . '-' . $filterValue;
                }
            }
            $params[$this->prefix . 'filter-' . $column] = join('-', $columnFilter);
        }

        return $params;
    }

    /**
     * getUrl
     *
     * @param array $params
     * @return string
     */
    public function getUrl($params)
    {
        // prepare params
        $params = $this->getParams($params);

        // retrieve URL
        $url = app()->getRouter()->url(
            $this->getModule(),
            $this->getController(),
            $params
        );

        return str_replace(PUBLIC_URL . "/", '', $url);

        /*
        return app()->getRouter()->url(
            $this->getModule(),
            $this->getController(),
            $params
        );
        */
    }

    function url($module, $controller, $params)
    {
        $params = $this->getParams($params);

        $url = app()->getRouter()->url(
            $module,
            $controller,
            $params
        );

        return str_replace(PUBLIC_URL . "/", '', $url);
    }

    /**
     * setAllowOrders
     *
     * @param array $orders
     * @return Grid
     */
    public function setAllowOrders(array $orders = array())
    {
        $this->allowOrders = $orders;
        return $this;
    }

    /**
     * getAllowOrders
     *
     * @return array
     */
    public function getAllowOrders()
    {
        return $this->allowOrders;
    }

    /**
     * @param        $column
     * @param string $order
     * @throws GridException
     * @return Grid
     */
    public function addOrder($column, $order = Grid::ORDER_ASC)
    {
        if (!in_array($column, $this->allowOrders)) {
            throw new GridException('Wrong column order');
        }

        if (strtolower($order) != Grid::ORDER_ASC
            && strtolower($order) != Grid::ORDER_DESC
        ) {
            throw new GridException('Order for column "' . $column . '" is incorrect');
        }

        $this->orders[$column] = $order;

        return $this;
    }

    /**
     * @param array $orders
     * @return Grid
     */
    public function addOrders(array $orders)
    {
        foreach ($orders as $column => $order) {
            $this->addOrder($column, $order);
        }
        return $this;
    }

    /**
     * @param        $column
     * @param string $order
     * @return Grid
     */
    public function setOrder($column, $order = Grid::ORDER_ASC)
    {
        $this->orders = array();
        $this->addOrder($column, $order);
        return $this;
    }

    /**
     * @param array $orders
     * @return Grid
     */
    public function setOrders(array $orders)
    {
        $this->orders = array();
        foreach ($orders as $column => $order) {
            $this->addOrder($column, $order);
        }
        return $this;
    }

    /**
     * getOrders
     *
     * @return array
     */
    public function getOrders()
    {
        $default = $this->getDefaultOrder();

        // remove default order when another one is set
        if (is_array($default)
            && count($this->orders) > 1
            && isset($this->orders[key($default)])
            && $this->orders[key($default)] == reset($default)
        ) {
            unset($this->orders[key($default)]);
        }

        return $this->orders;
    }

    /**
     * setAllowedFilters
     *
     * @param array $filters
     * @return self
     */
    public function setAllowFilters(array $filters = array())
    {
        $this->allowFilters = $filters;
        return $this;
    }

    /**
     * getAllowedFilters
     *
     * @return array
     */
    public function getAllowFilters()
    {
        return $this->allowFilters;
    }

    /**
     * checkFilter
     *
     * @param $filter
     * @return boolean
     */
    public function checkFilter($filter)
    {
        if ($filter == self::FILTER_EQ or
            $filter == self::FILTER_NE or
            $filter == self::FILTER_GT or
            $filter == self::FILTER_GE or
            $filter == self::FILTER_LT or
            $filter == self::FILTER_LE or
            $filter == self::FILTER_ENUM or
            $filter == self::FILTER_NUM or
            $filter == self::FILTER_LIKE
        ) {
            return true;
        }
        return false;
    }

    /**
     * addFilter
     *
     * @param string $column
     * @param string $filter
     * @param string $value
     * @throws GridException
     * @return self
     */
    public function addFilter($column, $filter, $value)
    {

        if (!in_array($column, $this->allowFilters) &&
            !array_key_exists($column, $this->allowFilters)
        ) {
            throw new GridException('Wrong column name for filter');
        }

        $filter = strtolower($filter);

        if (!$this->checkFilter($filter)) {
            throw new GridException('Wrong filter name');
        }

        if (!isset($this->filters[$column])) {
            $this->filters[$column] = array();
        }


        if($this->fulltext_search) $value = 'fulltext-' . $value;

        $this->filters[$column][$filter] = $value;


        return $this;
    }


    /**
     * getFilters
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * setPage
     *
     * @param int $page
     * @throws GridException
     * @return Grid
     */
    public function setPage($page = 1)
    {
        if ($page < 1) {
            throw new GridException('Wrong page number, should be greater than zero');
        }
        $this->page = (int)$page;
        return $this;
    }

    /**
     * getPage
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * setLimit
     *
     * @param int $limit
     * @throws GridException
     * @return Grid
     */
    public function setLimit($limit)
    {
        if ($limit < 1) {
            throw new GridException('Wrong limit value, should be greater than zero');
        }
        $this->limit = (int)$limit;
        return $this;
    }

    /**
     * getLimit
     *
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * setDefaultLimit
     *
     * @param int $limit
     * @throws GridException
     * @return Grid
     */
    public function setDefaultLimit($limit)
    {
        if ($limit < 1) {
            throw new GridException('Wrong default limit value, should be greater than zero');
        }
        $this->setLimit($limit);

        $this->defaultLimit = (int)$limit;
        return $this;
    }

    /**
     * getDefaultLimit
     *
     * @return integer
     */
    public function getDefaultLimit()
    {
        return $this->defaultLimit;
    }

    /**
     * setDefaultOrder
     *
     * @param string $column
     * @param string $order
     * @throws GridException
     * @return Grid
     */
    public function setDefaultOrder($column, $order = Grid::ORDER_ASC)
    {
        if (empty($column)) {
            throw new GridException('Wrong default order value, should be not empty');
        }
        $this->setOrder($column, $order);

        $this->defaultOrder = array($column => $order);
        return $this;
    }

    /**
     * getDefaultOrder
     *
     * @return integer
     */
    public function getDefaultOrder()
    {
        return $this->defaultOrder;
    }

    public function addHelperPath($path)
    {
        $path = rtrim(realpath($path), '/');
        if (false !== $path && !in_array($path, self::$helpersPath)) {
            self::$helpersPath[] = $path;
        }

        return $this;
    }

    function zxc($param){
        return $param + 1;
    }

    /**
     *
     * Call (скопировано из Common/Helper)
     *
     * @param string $method
     * @param array $args
     * @throws \Exception
     * @return mixed

    // fb($this); // Core\Model\Test\SqlGrid

     */
    public function __call($method, $args)
    {

        //if($method == 'filter')            fb($args);
        $path = PATH_LIB . '/bluz/Grid/Helper';

        self::addHelperPath($path);

        // app()->getInstance()->getView()->getHelper()->addHelperPath($path);

        // здесь происходит потеря объекта гриды, поэтому , нужно его пробросить в хелпер
        // $args[] = $this;
        // return app()->getInstance()->getView()->getHelper()->$method($args);


        // Call helper function (or class)
        if (isset($this->helpers[$method]) && is_callable($this->helpers[$method])) {
            return call_user_func_array($this->helpers[$method], $args);
        }

        /*
         * Попытка сделать через Helper
        $Helper = Registry::getInstance()->helper;
        $Helper->addHelperPath($path);
        return $Helper->$method($args);
        */


        /// $helperPath = $path . "/$method.php";

        // Try to find helper file
        foreach (self::$helpersPath as $helperPath) {
            //$helperPath = realpath($helperPath . '/' . $method . '.php');
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
        throw new \Exception("Helper '$method' not found for '" . __CLASS__ . "'");

    }
}
