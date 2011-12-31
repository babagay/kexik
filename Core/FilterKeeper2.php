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
 * Ключи сущностей, к которым относятся фильтры: category|vendor|phrase
 *
 * TODO
 * Подключить в качестве резервного стораджа обертку для работы с базой|файлом|сессией|куки
 *
 * Реализовать логику хранения наборов фильтрв в базе.
 *      Filtersets
 *      hash    categories_id   manufacturers_id    phrase
 *
 *      Filtersets_to_filters
 *      hash    filters_id
 *
 *      Filtersets_to_products
 *      hash    products_id
 *
 * Фильтры на основе категорий 3-го уровня тоже получают свой хэш и могут храниться в тех же таблицах.
 * Для этого их надо расширить:
 *      Filtersets - так и остается. categories_id - id родительской по отношению к набору категории
 *      hash    categories_id   manufacturers_id    phrase
 *
 *      Filtersets_to_filters (categories_id - id категории 3 уровня)
 *      hash    filters_id    categories_id
 *
 *      Filtersets_to_products - полностью без изменений
 *      hash    products_id
 *
 * Реализовать метод() очистки кэша от всех наборов фильтров. Для этого надо последовательно вызвать метод delete(),
 * а для этого нужно все хэши хранить в базе.
 *
 * [usage]
 * $filterKeeper->deleteFilterSet("5d80c5e75cfb8ccb652463750d2109e4"); - не используется
 *
 * Обновление списка производителей
 *      Допустим, мы изменили продукт.
 *      Мы обнаружили, что он входит в категорию 5 и имеет производителя 400.
 *      Чтобы удалить кэш списка производителей для категории 5, нужно выполнить:
 *      app()->getFilterKeeper()->deleteManufacturerSetByVendor(400,5);
 *
 * Закэшировать производителей для категории
 *      $vendors = Manufacturers\Table::getInstance()->getVendorsByCategory($категория);
 *      $filterKeeper->setFiltersVendor($vendors);
 *
 * @todo сделать, чтобы подобным образом обновлялся кэш фильтров (original, subcategory) при изменении фильтра/продукта
 *
 * Удаление фильтра
 *       app()->getFilterKeeper()->getStorage()->deleteByTag('by_category:'.'filters_id:'.'518');
 *
 * app()->getFilterKeeper()->clearContext()->selectContext("category_to_vendors", $category['categories_id'])->selectFilterType("manufacturer")->setFiltersVendor($vendors);
 */
final class FilterKeeper2 {

    private static $instance;

    private $categories_id = null;
    private $manufacturers_id = null;
    private $productset_hash = null;

    private $entity_types = array("category", "vendor", "phrase", "category_to_vendors");
    private $filter_types = array("by_category", "origin", "manufacturer");

    /**
     * @var null|category|vendor|phrase
     * Хранит сущность, с которой работаем в данный момент
     */
    private $trigger = null;

    /**
     * Текущий тип фильтра
     * @var null|by_category|origin|manufacturer
     */
    private $type = null;

    /**
     * Хэш-таблица наборов фильтров: array(hash => filter_set)
     * @var null
     */
    private $filters = null;

    /**
     * Хэш набора фильтров
     * @var null
     */
    private $filterset_hash = null;

    /**
     * @var null
     */
    private $phrase = null;

    /**
     * @var array of ('categories_id' => array of categories_id|filters_id)
     */
    private $filters_category = array(); // FIXME удалить

    /**
     * @var array of ('manufacturer_id' => arrays of categories_id|filters_id)
     */
    private $filters_vendor = array();  // FIXME удалить

    /**
     * @var array of ('product_set_id' => array of categories_id|filters_id)
     */
    private $filters_product_set = array();  // FIXME удалить



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

    function setCategory($categories_id)
    {
        $this->trigger = 'category';
        $this->categories_id = $categories_id;

        return $this;
    }

    function setVendor($manufacturers_id, $cat_id = null)
    {
        $this->clearContext();

        $this->trigger = 'vendor';
        $this->manufacturers_id = $manufacturers_id;
        $this->categories_id = $cat_id;

        return $this;
    }

    function setPhrase($phrase)
    {
        $this->trigger = 'phrase';
        $this->phrase = $phrase;

        return $this;
    }

    /** DEPRECATED
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
     * Метод вычисления хэша
     * @return callable
     */
    function hashCalc()
    {
        return function($key) {
            $hash = md5( serialize($key) );
            return $hash;
        };
    }

    function calculateHash($key,$trigger,$type)
    {
        $hc = $this->hashCalc();
        return  $hc($trigger.$type.$key);
    }

    /**
     * @param array $filters
     * @param string $type (origin|by_category)
     * @return $this
     * @throws \Exception
     * @todo в конце метода добавить (или это лучше вынести в метод add() ?) сохранение в filtersets_to_products записи (hash => array of product_id)
     */
    private function setFilters($filters = [], $type = null)
    {
        if($this->trigger === null){
            throw new \Exception(__CLASS__ . ": Не возможно установить фильтры, тк нет привязки к объекту");
        }

        if($type === null) $type = $this->type;
        if($type === null) throw new \Exception(__CLASS__ . ": Не возможно установить фильтры, тк нет привязки к типу");

        $id = null;

        switch($this->trigger){
            case 'category':
            case 'category_to_vendors':
            if(is_null($this->categories_id)) throw new \Exception(__CLASS__ . ": необходимо уточнить categories_id");
                $id = $this->categories_id;
                break;
            case 'vendor':
                if(is_null($this->manufacturers_id)) throw new \Exception(__CLASS__ . ": необходимо уточнить categories_id и manufacturers_id");
                if(is_null($this->categories_id)) throw new \Exception(__CLASS__ . ": необходимо уточнить categories_id и categories_id");
                $id = $this->categories_id . $this->manufacturers_id;
                break;
            case 'phrase':
                if(is_null($this->phrase)) throw new \Exception(__CLASS__ . ": необходимо уточнить ключевую фразу");
                $id = $this->phrase;
                break;
        }

        $hash = $this->filterset_hash = $this->calculateHash($id,$this->trigger,$type);

        $this->getStorage()->set($hash,$filters);

        // Тэги
        if (is_array($filters)) {
            if ($this->type == 'origin') $key = 'filters_id';
            elseif ($this->type == 'by_category') $key = 'categories_id';
            elseif ($this->type == 'manufacturer') $key = 'manufacturers_id';

            foreach ($filters as $filter) {
                $this->getStorage()->addTag($hash, $this->type . ':' . 'filters_id:' . $filter[$key]);
            }
        }

        return $this;
    }

    function setFiltersOrigin($filters = [])
    {
        return $this->setFilters($filters,'origin');
    }

    function setFiltersSubcategory($filters = [])
    {
        return $this->setFilters($filters, 'by_category');
    }

    function setFiltersVendor($filters = [])
    {
        return $this->setFilters($filters, 'manufacturer');
    }

    function selectContext($trigger,$key = null)
    {
        $this->selectTrigger($trigger);

        // FIXME $key не может быть NULL, значит, надо проверить все вызовы этого метода, чтобы в них было передано 2 параметра
        // и потом убрать из сигнатуры $key = null
        if($key !== null)
            switch($trigger){
                case 'category_to_vendors':
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
        $this->type = null;

        $this->categories_id = null;
        $this->manufacturers_id = null;
        $this->phrase = null;

        $this->filterset_hash = null;

        return $this;
    }

    function selectTrigger($trigger)
    {
        if( !in_array($trigger,$this->entity_types) )

            throw new \Exception(__CLASS__ . ": Допустимый контекст: category, vendor, phrase. Пытаетесь ввести $trigger");

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


    function getFilters($hash = null)
    {
        if(is_null($hash)) {
            // Попробовать взять хэш из контекста
            if($this->trigger === null){
                throw new \Exception(__CLASS__ . ": необходимо уточнить контекст");
            }

            $id = null;

            switch($this->trigger){
                case 'category':
                case 'category_to_vendors':
                if(is_null($this->categories_id)) throw new \Exception(__CLASS__ . ": необходимо уточнить categories_id");
                    $id = $this->categories_id;
                    break;
                case 'vendor':
                    if(is_null($this->manufacturers_id)) throw new \Exception(__CLASS__ . ": необходимо уточнить manufacturers_id ");
                    if(is_null($this->categories_id)) throw new \Exception(__CLASS__ . ": необходимо уточнить categories_id ");
                    $id = $this->categories_id . $this->manufacturers_id;
                    break;
                case 'phrase':
                    if(is_null($this->phrase)) throw new \Exception(__CLASS__ . ": необходимо уточнить ключевую фразу");
                    $id = $this->phrase;
                    break;
            }

            /*
            if(!is_null($this->phrase)) $id = $this->phrase;
            elseif(!is_null($this->manufacturers_id) AND !is_null($this->categories_id)) $id = $this->manufacturers_id . $this->manufacturers_id;
            elseif(!is_null($this->categories_id)) $id = $this->categories_id;
            */

            if(is_null($this->type))  throw new \Exception(__CLASS__ . ": необходимо уточнить тип набора фильтров (by_category,origin)");

            $hash = $this->productset_hash = $this->calculateHash($id,$this->trigger,$this->type);
        }

        return $this->getItemFromStorage($hash);
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

    /**
     * Взвращает кэш либо альтернативное хранилище данных
     * @return \Bluz\Cache\Cache|\Bluz\Common\Nil|\Bluz\Cache\Storage
     */
    function getStorage()
    {
        $adapter = app()->getCache();

        if ($adapter instanceof \Bluz\Common\Nil)
            $adapter = app()->getStorage();

        return $adapter;
    }

    function getItemFromStorage ($hash)
    {
        return $this->getStorage()->get($hash);
    }

    /**
     * [!] Не используется
     * @param $hash
     */
    function deleteFilterSet($hash)
    {
        $this->getStorage()->delete($hash);
        $this->clearContext();
    }

    /**
     * @param $manufacturers_id
     * @param $categories_id
     */
    function deleteManufacturerSetByVendor($manufacturers_id,$categories_id)
    {
        // FIXME
        // При внесении нового продукта в категорию метод getFilters() возвращает false
        // При повторном редактировании того же продукта он работает нормально

        $vendors = $this->clearContext()->selectContext("category_to_vendors", $categories_id)->selectFilterType("manufacturer")->getFilters();

        if(is_array($vendors)){
            foreach($vendors as $vendor){
                $this->getStorage()->deleteByTag("manufacturer:filters_id:{$vendor['manufacturers_id']}");
            }
        }
    }
}