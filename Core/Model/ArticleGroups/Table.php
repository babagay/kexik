<?php
/**
 * @namespace
 */
namespace Core\Model\ArticleGroups;

class Table extends \Bluz\Db\Table
{
   /*
    const STATUS_PENDING = 'pending'; // Pending email verification

    const STATUS_ACTIVE = 'active'; //  Active user

    const STATUS_DISABLED = 'disabled'; // Disabled by administrator

    const STATUS_DELETED = 'deleted'; // Removed account

    const SYSTEM_USER = 0; // system user with ID=0
  */

    /**
     * Table without prefixe
     *
     * @var string
     */
    protected $table = 'article_groups';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('groups_id');


}
