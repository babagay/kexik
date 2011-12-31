<?php

namespace Application\Manufacturers;

use Application\Exception;

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
            if ($format == "all-cols") {
                $data = app()->getDb()->fetchAll(
                    "SELECT *
                    FROM {$this->table}
                    ORDER BY manufacturers_name"
                );
            } elseif ($format == "id-name") {
                $data = app()->getDb()->fetchAll(
                    "SELECT manufacturers_id, manufacturers_name
                    FROM {$this->table}
                    ORDER BY manufacturers_name"
                );
                $tmp = array();
                if (is_array($data)) {
                    foreach ($data as $value) {
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

    function getVendorsByCategory($cat_id)
    {
        $query = "
                    SELECT m.manufacturers_id, m.manufacturers_name
                    FROM products p
                    JOIN manufacturers m ON m.manufacturers_id = p.manufacturers_id
                    WHERE products_id IN
                    (
                      SELECT products_id
                      FROM products_to_categories p2c
                      WHERE categories_id = '$cat_id'
                    )
                    GROUP BY p.manufacturers_id
                    ORDER BY m.manufacturers_name
        ";

        return app()->getDb()->fetchAll($query);
    }

}
