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


}
