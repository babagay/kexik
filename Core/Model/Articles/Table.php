<?php
/**
 * @namespace
 */
namespace Core\Model\Articles;

class Table extends \Bluz\Db\Table
{


    /**
     * Table without prefixe
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = array('articles_id');
}
