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
    protected $orders_id = null;

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
        } elseif ( isset($this->options['orders_id']) ){
            // получение продуктов одного заказа

            $this->orders_id = $this->options['orders_id'];

            $builder = app()->getDb()
                ->select('op.*' )
                ->from('order_products', 'op')
                ->where('op.orders_id = ?', $this->options['orders_id']);
            $products = $builder->execute();

            $products_str = '';
            $tmp = null;
            if(is_array($products))
                if(sizeof($products)){
                    foreach($products as $product){
                        $tmp[] = $product['products_id'];
                    }
                }
            if(sizeof($tmp)) $products_str = implode(',',$tmp);

            if($products_str != ''){
                $source = "SELECT  p.*, op.price, op.products_num,
                    (op.price * op.products_num) products_total
                    FROM " . $this->table_name . " p
                    LEFT JOIN order_products op ON op.products_id = p.products_id AND orders_id = '{$this->orders_id}'
                    WHERE p.products_id IN ( $products_str   )
                    ";
                $adapter->setSource( $source );
            } else {
                // заглушка. Нужна, т.к. $source нужно устанавливать при любом раскладе
                $source = "SELECT  p.*, op.price, op.products_num
                    FROM " . $this->table_name . " p
                    LEFT JOIN order_products op ON op.products_id = p.products_id AND orders_id = '{$this->orders_id}'
                    WHERE p.products_id = 0
                    ";
                $adapter->setSource( $source );
            }

            // Если задан только search, можно искать во всех столбцах
            if( isset( $this->options['search']) ){
                $key = $this->options['search'];

                if( isset( $this->options['search-column']) ){
                    if( $this->options['search-column'] == 'products_name' ){
                        $adapter->setSource(
                            'SELECT *, (op.price * op.products_num) products_total FROM ' .
                            '(SELECT * FROM ' . $this->table_name . ' WHERE MATCH (products_name) AGAINST (\'' . $key . '\') ) p' .
                            " LEFT JOIN order_products op ON op.products_id = p.products_id AND orders_id = '{$this->orders_id}'" .
                            " WHERE p.products_id IN($products_str)"

                        );
                        $this->fulltext_search = true;
                    } elseif( $this->options['search-column'] == 'products_id' ){
                        $adapter->setSource("SELECT p.*, op.*, (op.price * op.products_num) products_total
                                              FROM (
                                                SELECT *
                                                FROM {$this->table_name}
                                                WHERE products_id IN($products_str)
                                                ) p
                                              LEFT JOIN order_products op ON op.products_id = p.products_id AND orders_id = '{$this->orders_id}'
                                              WHERE p.products_id LIKE '%$key%'
                                              ");

                    } elseif ($this->options['search-column'] == 'products_barcode') {
                        $adapter->setSource("SELECT p.*, op.*, (op.price * op.products_num) products_total
                                              FROM (
                                                SELECT *
                                                FROM {$this->table_name}
                                                WHERE products_id IN($products_str)
                                                ) p
                                              LEFT JOIN order_products op ON op.products_id = p.products_id AND orders_id = '{$this->orders_id}'
                                              WHERE p.products_barcode LIKE '%$key%'
                                              ");
                    }
                }

            }

        } else {

            // Параметры поиска переданы вручную - провести поиск
            if( isset( $this->options['search']) ){
                $key = $this->options['search'];
                if( isset( $this->options['search-column']) ){
                    $search_column = $this->options['search-column'];
                    $adapter->setSource("SELECT *
                                                FROM {$this->table_name}
                                              WHERE $search_column LIKE '%$key%'
                                              ");
                }
            } else
            // По умолчанию - общий запрос
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
