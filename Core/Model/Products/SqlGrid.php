<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Core\Model\Products;


/**
 * Products Grid based on SQL
 *
 * @category Application
 * @package  Products
 */
class SqlGrid extends \Bluz\Grid\Grid
{
    protected $uid = 'sql';
    protected $table_name = 'products';
    protected $fulltext_search = false;

    /**
     * init
     * 
     * @return self
     */
    public function init()
    {
        $adapter = new \Bluz\Grid\Source\SqlSource();

        $key = null;

        if( isset($this->options['search-like']) ){
            // TODO если вызван серч, то нужен альтернативный init()
            // TODO секюрность
            $key = trim($this->options['search-like']);
            $adapter->setSource('SELECT * FROM '.$this->table_name . " WHERE MATCH (products_name) AGAINST ('$key')");
            $this->fulltext_search = true;
        } else {
            $adapter->setSource( 'SELECT * FROM ' . $this->table_name );
        }

         $this->setAdapter($adapter);
         $this->setDefaultLimit(500);
         $this->setAllowOrders(array('products_id', 'products_barcode', 'products_name', 'products_shoppingcart_price', 'products_price', 'products_quantity'));
         $this->setAllowFilters(array('products_id', 'products_barcode', 'products_name'));



        //if($key !== null)
          //  $this->addFilter("products_name","like",$key, true);

         return $this;
    }


}
