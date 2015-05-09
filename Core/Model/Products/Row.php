<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Products;

/**
 * Products Row
 *
 * @property integer $products_id
 * @property string $products_barcode
 * @property string $products_name
 * @property string $products_seo_page_name
 * @property string $products_unit
 * @property string $products_departament
 * @property float $products_shoppingcart_price
 * @property float $products_price
 * @property float $products_quantity
 * @property string $image_small
 * @property string $image_large
 * @property string $products_last_modified
 * @property integer $products_visibility enum('0','1')
 * @property integer $manufacturers_id
 * @property integer $sort_order
 *
 * @category Application
 * @package  Products
 */
class Row extends \Bluz\Db\Row
{

    protected function beforeSave()
    {
        // fb($this->data);
    }
}