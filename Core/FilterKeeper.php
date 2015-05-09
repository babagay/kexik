<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.04.15
 * Time: 9:26
 */

namespace Core;

/**
 * Class FilterKeeper
 * @package Core
 *
 * Ключи в массиве фильтров: by_category и origin
 * Ключи сущностей, к которым относятся фильтры: category|vendor|products
 *
 * [usage]
 * $фильтры_категории = $filterKeeper->selectContext("category",$categoryies_id)->selectFilterType("origin")->getFilters()
 */
final class FilterKeeper {

    private static $instance;

    private $categories_id = null;
    private $manufacturers_id = null;
    private $productset_hash = null;

    /**
     * @var null|category|vendor|products
     * Хранит сущность, с которой работаем в данный момент
     */
    private $trigger = null;

    /**
     * @var array of ('categories_id' => array of categories_id|filters_id)
     */
    private $filters_category = array();

    /**
     * @var array of ('manufacturer_id' => arrays of categories_id|filters_id)
     */
    private $filters_vendor = array();

    /**
     * @var array of ('product_set_id' => array of categories_id|filters_id)
     */
    private $filters_product_set = array();

    private $entity_types = array("category","vendor","products");
    private $filter_types = array("by_category","origin");

    /**
     * Текущий тип фильтра
     * @var null|by_category|origin
     */
    private $type = null;

    private function __construct()
    {
        self::$instance = $this;
    }

    /**
     * @return Registry|null
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Enforce singleton; disallow cloning
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Enforce singleton; disallow wakeup
     *
     * @return void
     */
    private function __wakeup()
    {
    }

    /**
     * Инициализация (обнуление) фильтров для заданной категории
     * @param $categories_id
     * @return $this
     */
    function setCategory($categories_id)
    {
        $this->trigger = 'category';
        $this->filters_category[$categories_id] = array();
        $this->categories_id = $categories_id;

        return $this;
    }

    function setVendor($manufacturers_id)
    {
        $this->trigger = 'vendor';
        $this->filters_vendor[$manufacturers_id] = array();
        $this->manufacturers_id = $manufacturers_id;

        return $this;
    }

    /**
     * Рекомендация: передавать в виде массива в формате array('id1','id2',...'idN')
     * либо в виде строки id1,id2,idN
     * @param mixed $products
     * @return $this
     */
    function setProducts($products)
    {
        $this->trigger = 'products';

        $hc = $this->hashCalc();
        $hash = $hc($products);

        $this->filters_product_set[$hash] = array();
        $this->productset_hash = $hash;

        return $this;
    }

    /**
     * Метод вычисления хэша для набора продуктов
     * @return callable
     */
    function hashCalc()
    {
        return function($products) {
            if(is_string($products)) $products = explode(",",$products);
            $hash = md5( serialize($products) );
            return $hash;
        };
    }

    /**
     * @param array $filters
     * @param string $type (origin|by_category)
     * @return $this
     * @throws \Exception
     */
    private function setFilters($filters = [], $type = 'origin')
    {
        if($this->trigger === null){
            throw new \Exception(__CLASS__ . ": Не возможно установить оригинальные фильтры, тк нет привязки к объекту");
        }

        switch($this->trigger){
            case 'category':
                if(is_null($this->categories_id))
                    throw new \Exception(__CLASS__ . ": Не задана категория");

                $this->filters_category[$this->categories_id][$type] = $filters;
                break;

            case 'vendor':
                if(is_null($this->manufacturers_id))
                    throw new \Exception(__CLASS__ . ": Не задан производитель");

                $this->filters_vendor[$this->manufacturers_id][$type] = $filters;
                break;

            case 'products':
                if(is_null($this->productset_hash))
                    throw new \Exception(__CLASS__ . ": Не задан набор продуктов");

                $this->filters_product_set[$this->productset_hash][$type] = $filters;
                break;
        }

        return $this;
    }

    function setFiltersOrigin($filters = [])
    {
        return $this->setFilters($filters);
    }

    function setFiltersSubcategory($filters = [])
    {
        return $this->setFilters($filters, 'by_category');
    }

    function selectContext($trigger,$key = null)
    {
        $this->selectTrigger($trigger);

        if($key !== null)
        switch($trigger){
            case 'category': $this->selectCategoriesId($key);
                break;
            case 'vendor': $this->selectVendorId($key);
                break;
            case 'products': $this->selectProductSetId($key);
                break;
        }

        return $this;
    }

    function clearContext()
    {
        $this->trigger = null;
        $this->categories_id = null;
        $this->manufacturers_id = null;
        $this->productset_hash = null;

        return $this;
    }

    function selectTrigger($trigger)
    {
        if( !in_array($trigger,$this->entity_types) )
        //if($trigger != "category" OR $trigger != "vendor" OR $trigger != "products")
            throw new \Exception(__CLASS__ . ": Допустимый контекст: category, vendor, products. Пытаетесь ввести $trigger");

        $this->trigger = $trigger;

        return $this;
    }

    function selectFilterType($type)
    {
        if(!is_null($type)){
            if( !in_array($type,$this->filter_types) )
                throw new \Exception(__CLASS__ . ": указан неверный тип фильтра ($type)");
        }

        $this->type = $type;

        return $this;
    }


    function selectCategoriesId($categories_id)
    {
        $this->categories_id = $categories_id;
        return $this;
    }

    function selectVendorId($manufacturers_id)
    {
        $this->manufacturers_id = $manufacturers_id;
        return $this;
    }

    function selectProductSetId($id)
    {
        $this->productset_hash = $id;
        return $this;
    }

    function getCategoriesId()
    {
        return $this->categories_id;
    }

    function getVendorId()
    {
        return $this->manufacturers_id;
    }

    function getHash()
    {
        return $this->productset_hash;
    }

    /**
     *  Возвращает фильтры для текущего коонтекста и текущего идентификатора сущности
     * @param null $key
     * @return $this|null
     * @throws \Exception
     */
    function getFilters($key = null)
    {
        if($this->trigger === null){
            throw new \Exception(__CLASS__ . ": необходимо уточнить контекст");
        }

        $type = null;
        if(!is_null($this->type)) $type = $this->type;

        if($key !== null){
            switch($this->trigger){
                case 'category':
                    if($type === null) return $this->getFiltersCategory($key);
                    $filters = $this->getFiltersCategory($key);
                    return isset($filters[$type]) ? $filters[$type] : null;
                    break;
                case 'vendor':
                    if($type === null) return $this->filters_vendor[$key];
                    $filters = $this->getFiltersVendor($key);
                    return isset($filters[$type]) ? $filters[$type] : null;
                    break;
                case 'products':
                    if($type === null) return $this->filters_product_set[$key];
                    $filters = $this->getFiltersProducts($key);
                    return isset($filters[$type]) ? $filters[$type] : null;
                    break;
                default:
                    throw new \Exception(__CLASS__ . ": Не верный контекст " . $this->trigger);
                    break;
            }
        }

        switch($this->trigger){
            case 'category':
                $key = $this->categories_id;
                if(is_null($key))
                    throw new \Exception(__CLASS__ . ": Контекст определен ({$this->trigger}), но не задан ключ (key = $key)");
                if($type === null) return $this->filters_category[$key];
                $filters = $this->getFiltersCategory($key);
                return isset($filters[$type]) ? $filters[$type] : null;
                break;
            case 'vendor':
                $key = $this->manufacturers_id;
                if(is_null($key))
                    throw new \Exception(__CLASS__ . ": Контекст определен ({$this->trigger}), но не задан ключ (key = $key)");
                if($type === null) return $this->filters_vendor[$key];
                $filters = $this->getFiltersVendor($key);
                return isset($filters[$type]) ? $filters[$type] : null;
                break;
            case 'products':
                $key = $this->productset_hash;
                if(is_null($key))
                    throw new \Exception(__CLASS__ . ": Контекст определен ({$this->trigger}), но не задан ключ (key = $key)");
                if($type === null) return $this->filters_product_set[$key];
                $filters = $this->getFiltersProducts($key);
                return isset($filters[$type]) ? $filters[$type] : null;
                break;
            default:
                throw new \Exception(__CLASS__ . ": Не верный контекст " . $this->trigger);
                break;
        }

        return $this;
    }

    function getFiltersCategory($categories_id = null)
    {
        if(!is_null($categories_id))
            return isset($this->filters_category[$categories_id]) ? $this->filters_category[$categories_id] : null;

        if($this->trigger == 'category' AND !is_null($this->categories_id))
            return isset($this->filters_category[$this->categories_id]) ? $this->filters_category[$this->categories_id] : null;

        throw new \Exception(__CLASS__ . ": Не возможно вернуть фильтры категории [$categories_id]");
    }

    function getFiltersVendor($manufacturers_id = null)
    {
        if(!is_null($manufacturers_id))
            return isset($this->filters_vendor[$manufacturers_id]) ? $this->filters_vendor[$manufacturers_id] : null;

        if($this->trigger == 'vendor' AND !is_null($this->manufacturers_id))
            return isset($this->filters_vendor[$this->manufacturers_id]) ? $this->filters_vendor[$this->manufacturers_id] : null;

        throw new \Exception(__CLASS__ . ": Не возможно вернуть фильтры вендора [$manufacturers_id]");
    }

    function getFiltersProducts($hash = null)
    {
        if(!is_null($hash))
            return isset($this->filters_product_set[$hash]) ? $this->filters_product_set[$hash] : null;

        if($this->trigger == 'products' AND !is_null($this->productset_hash))
            return isset($this->filters_product_set[$this->productset_hash]) ? $this->filters_product_set[$this->productset_hash] : null;

        throw new \Exception(__CLASS__ . ": Не возможно вернуть фильтры набора продуктов [$hash, {$this->productset_hash}]");
    }
} 