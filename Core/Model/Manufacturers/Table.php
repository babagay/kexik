<?php

namespace Application\Manufacturers;
 
class Table extends \Bluz\Db\Table
{

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'manufacturers';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('manufacturers_id');

    /**
     * @param string $format (id-name|all-cols)
     * @return array|mixed
     */
    public function getManufacturers($format = "all-cols")
    {
        $cacheKey = 'manufacturers:all:' . $format;

        if (!$data = app()->getCache()->get($cacheKey)) {
            if($format == "all-cols"){
                $data = app()->getDb()->fetchAll(
                    "SELECT *
                    FROM {$this->table}
                    ORDER BY manufacturers_name"
                );
            } elseif($format == "id-name"){
                $data = app()->getDb()->fetchAll(
                    "SELECT manufacturers_id, manufacturers_name
                    FROM {$this->table}
                    ORDER BY manufacturers_name"
                );
                $tmp = array();
                if(is_array($data)){
                    foreach($data as $value){
                        $tmp[$value['manufacturers_id']] = $value['manufacturers_name'];
                    }
                    $data = $tmp;
                }
            }

            app()->getCache()->set($cacheKey, $data, \Bluz\Cache\Cache::TTL_NO_EXPIRY);
            app()->getCache()->addTag($cacheKey, 'manufacturers');
        }

        return $data;
    }
     
}
