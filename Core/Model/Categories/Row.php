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

use Application\Exception;
use Application\Privileges;
use Application\Roles;
use Bluz\Auth\AbstractRowEntity;
use Bluz\Auth\AuthException;

/**
 * Categories Row
 *
 * @property integer $categories_id
 * @property string $categories_name
 * @property string $categories_description
 * @property string $categories_image
 * @property string $categories_icon
 * @property string $categories_background
 * @property integer $parent_id
 * @property integer $sort_order
 * @property integer $price_index
 * @property date $date_added
 * @property date $last_modified
 * @property integer $categories_status
 * @property integer $categories_level
 * @property string $categories_seo_page_name
 *
 * @category Application
 * @package  Categories
 */

class Row extends AbstractRowEntity
{

    function __construct($params = null)
    {
        if(!is_null($params)){
           //$r = \Core\Model\Categories\Table::findWhere($params);
           $r = Table::findWhere($params);
           if($r)
               $this->selection = $r;
        }
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getPrivileges()
    {
        // TODO: Implement getPrivileges() method.
    }

    /**
     * Can entity login
     *
     * @throws AuthException
     * @return boolean
     */
    public function tryLogin()
    {
        // TODO: Implement tryLogin() method.
    }


}
