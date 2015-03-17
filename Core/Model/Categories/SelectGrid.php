<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
//namespace Core\Model\Categories;
namespace Application\Categories;


/**
 * categories Grid based on SQL
 *
 * @category Application
 * @package  categories
 */
class SelectGrid extends \Bluz\Grid\Grid
{
    protected $uid = 'sql';

    /**
     * init
     * 
     * @return self
     */
    public function init()
    {
         // Array
         $adapter = new \Bluz\Grid\Source\SelectSource();

         $select = new \Bluz\Db\Query\Select();
         $select->select('*')->from('categories', 'c'); /// ->where('c.parent_id = 0')

        $parent_id = 0;

        // {$this->uid}-filter-parent_id/eq-1

        // Или по умолчанию можно добавлять фильтр
        /*

        if(true){
            $parent_id = 1;
        }

        */

         $adapter->setSource($select);

         $this->setAdapter($adapter);
         $this->setDefaultLimit(25);
        //$this->setDefaultOrder()
          
         $this->setAllowOrders(array('categories_id', 'categories_name', 'sort_order', 'date_added', 'last_modified', 'categories_level', 'categories_seo_page_name'));
         $this->setAllowFilters(array('categories_id', 'parent_id', 'categories_name', 'categories_seo_page_name'));

         $this->addFilter('parent_id','eq',$parent_id);

         return $this;
    }
}
