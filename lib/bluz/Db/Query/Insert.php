<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Db\Query;

use Bluz\Db\Db;

/**
 * Builder of SELECT queries
 */
class Insert extends AbstractBuilder
{
    //use Traits\Set;

    /**
     * Sets a new value for a column in a insert/update query
     *
     * <code>
     *     $ub = new UpdateBuilder();
     *     $ub
     *         ->update('users')
     *         ->set('password', md5('password'))
     *         ->where('id = ?');
     * </code>
     *
     * @param string $key The column to set
     * @param string $value The value, expression, placeholder, etc
     * @return $this
     */
    public function set($key, $value)
    {
        $this->setParameter(null, $value);
        $key = $this->getAdapter()->quoteIdentifier($key);
        return $this->addQueryPart('set', $key .' = ?', true);
    }

    /**
     * setArray
     *
     * <code>
     *     $ub = new UpdateBuilder();
     *     $ub
     *         ->update('users')
     *         ->setArray([
     *              'password' => md5('password')
     *              'updated' => date('Y-m-d H:i:s')
     *          ])
     *         ->where('u.id = ?');
     * </code>
     *
     * @param array $data
     * @return $this
     */
    public function setArray(array $data)
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($sequence = null)
    {

        $result = $this->getAdapter()->query($this->getSQL(), $this->params, $this->paramTypes);
        if ($result) {
            return $this->getAdapter()->handler()->lastInsertId($sequence);
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getSql()
    {
        $query = "INSERT INTO " . $this->sqlParts['from']['table']
            . " SET " . join(", ", $this->sqlParts['set']);

        return $query;
    }

    /**
     * Turns the query being built into an insert query that inserts into
     * a certain table
     *
     * <code>
     *
     *     $ib = new InsertBuilder();
     *     $ib
     *         ->insert('users')
     *         ->set('name', 'username')
     *         ->set('password', md5('password'));
     * </code>
     *
     * @param string $table The table into which the rows should be inserted
     * @return self instance
     */
    public function insert($table)
    {
        $table = $this->getAdapter()->quoteIdentifier($table);
        return $this->addQueryPart('from', array('table' => $table));
    }
}
