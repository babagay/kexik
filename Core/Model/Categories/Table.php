<?php
/**
 * @namespace
 */
//namespace Core\Model\Categories;
namespace Application\Categories;

class Table extends \Bluz\Db\Table
{

    /**
     * Table without prefixe
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('categories_id');

    /**
     * @param string $format (id-name|all-cols|id-name-by-level)
     * @return array|mixed
     */
    public function getCategories($format = "all-cols", $cut_category_name = false)
    {
        //TODO обрезать название категории

        $cacheKey = 'categories:all:' . $format;

        if (!$data = app()->getCache()->get($cacheKey)) {
            if($format == "all-cols"){
                $data = app()->getDb()->fetchAll(
                    "SELECT *
                    FROM {$this->table}
                    ORDER BY categories_name"
                );
            } elseif($format == "id-name"){
                $data = app()->getDb()->fetchAll(
                    "SELECT categories_id, categories_name
                    FROM {$this->table}
                    ORDER BY categories_name"
                );
                $tmp = array();
                if(is_array($data)){
                    foreach($data as $value){
                        if($cut_category_name)
                            $tmp[$value['categories_id']] = substr($value['categories_name'],0,50);
                        else
                            $tmp[$value['categories_id']] = $value['categories_name'];
                    }
                    $data = $tmp;
                }
            } elseif($format == 'id-name-by-level'){
                $data = app()->getDb()->fetchAll(
                    "SELECT categories_id, categories_name
                    FROM {$this->table}
                    WHERE parent_id = 0
                    ORDER BY sort_order"
                );

                $tmp = array();
                if(is_array($data)){
                    foreach($data as $value){
                        $cat_group = array();
                        $sub_data = app()->getDb()->fetchAll(
                            "SELECT categories_id, categories_name
                                FROM {$this->table}
                                WHERE parent_id = {$value['categories_id']}
                                ORDER BY categories_name"
                                    );

                        if(is_array($sub_data)){
                            foreach($sub_data as $sub_value){
                                //preg_match('/\b([\w.-_])+\b([\w.-,_])*\b([\w-_.,])*/i',$sub_value['categories_name'], $m);

                                $cat_group[$sub_value['categories_id']] = $sub_value['categories_name'];
                            }
                        }

                        $tmp[$value['categories_name']] = $cat_group;
                    }

                    $data = $tmp;
                }
            }

            app()->getCache()->set($cacheKey, $data, \Bluz\Cache\Cache::TTL_NO_EXPIRY);
            app()->getCache()->addTag($cacheKey, 'categories');
        }

        return $data;
    }

    /**
     * Подкатегории
     * Возвращает только те подкатегории, которые связаны с данными продуктами, если $exact = true
     * @param array $products
     * @param int $level
     * @param boolean $exact
     * @return array
     */
    function getCategoriesByProducts(array $products, $level = 2, $exact = true)
    {
        $products_str = implode(",", $products);

        if ($exact === true) {
            $query = "
            SELECT cc.*
            FROM categories cc
            WHERE cc.categories_id IN(
                SELECT p2cc.categories_id
                FROM products_to_categories p2cc
                WHERE p2cc.categories_id IN(
                    SELECT p2c.categories_id
                    FROM products_to_categories p2c
                    WHERE categories_id IN(
                           SELECT c.categories_id
                           FROM categories c
                           WHERE categories_level = " . app()->getDb()->quote($level) . "
                           AND parent_id IN(
                               SELECT categories_id
                               FROM products_to_categories
                               WHERE products_id IN ($products_str)
                               GROUP BY categories_id
                               )
                           )
                           GROUP BY categories_id
                    )
                    AND products_id IN($products_str)
                )
                        ";
        } else {
            $query = " SELECT c.*
                       FROM categories c
                       WHERE categories_level = " . app()->getDb()->quote($level) . "
                       AND parent_id IN(
                           SELECT categories_id
                           FROM products_to_categories
                           WHERE products_id IN ($products_str)
                           GROUP BY categories_id
                           )
                        ";
        }

        return app()->getDb()->fetchAll($query);
    }

    /**
     * Подкатегории
     * @param $categories_id
     * @param $level
     * @return array
     */
    function getChildren($categories_id, $level = 2)
    {
        $query = "  SELECT  c.*
                        FROM categories c
                        WHERE parent_id = " . app()->getDb()->quote($categories_id) . "
                        AND categories_level = " . app()->getDb()->quote($level);

        return app()->getDb()->fetchAll($query);
    }

    function filterProductsByCategories(array $products, array $filter_subcategory)
    {
        // Взять все продукты , входящие в субкатегории
        $categories_str  = implode(",", $filter_subcategory);
        $query           = "
            SELECT *
            FROM products_to_categories
            WHERE categories_id IN($categories_str)
            GROUP BY products_id
        ";
        $subcat_products = app()->getDb()->fetchAll($query);

        // Вычислить пересечение массивов
        $tmp = [];
        if (sizeof($subcat_products) AND sizeof($products)) {
            foreach ($subcat_products as $subcat_product) {
                $subcat_product['products_id'];
                foreach ($products as $product) {
                    if ($product['products_id'] == $subcat_product['products_id']) {
                        $tmp[] = $product;
                    }
                }
            }
            $products = $tmp;
        }

        return $products;
    }

}
