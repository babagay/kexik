<?php 
//namespace Core\Model\Categories;
namespace Application\Categories;


/**
 * categories Grid based on SQL
 *
 * @category Application
 * @package  Categories
 */
class SqlGrid extends \Bluz\Grid\Grid
{
    protected $uid = 'sql';
    protected $table_name = 'categories';
    protected $fulltext_search = false;
 
    public function init()
    {
        $adapter = new \Bluz\Grid\Source\SqlSource();

        $key = null;

    
            $adapter->setSource( 'SELECT * FROM ' . $this->table_name );
       

         $this->setAdapter($adapter);
         $this->setDefaultLimit(25);
         $this->setAllowOrders(array('categories_id', 'categories_name', 'sort_order', 'date_added', 'last_modified', 'categories_level', 'categories_seo_page_name'));
         $this->setAllowFilters(array('categories_id', 'categories_name', 'categories_seo_page_name'));
        // categories_description
  	 	 	  	 	
 
         return $this;
    }


}
