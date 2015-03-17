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
class Update extends AbstractBuilder
{
   // use Traits\Set; Вставил метод set(), setArray
   // use Traits\Where;
   // use Traits\Limit;

    /**
     * @var integer The maximum number of results to retrieve/update/delete
     */
    protected $limit = null;

    /**
     * @var integer The index of the first result to retrieve.
     */
    protected $offset = 0;

    /**
     * {@inheritdoc}
     */
    public function getSql()
    {
        $query = "UPDATE " . $this->sqlParts['from']['table']
            . " SET " . join(", ", $this->sqlParts['set'])
            . ($this->sqlParts['where'] !== null ? " WHERE " . ((string) $this->sqlParts['where']) : "")
            . ($this->limit ? " LIMIT ". $this->limit : "")
        ;

        return $query;
    }

    /**
     * Turns the query being built into a bulk update query that ranges over
     * a certain table
     *
     * <code>
     *     $ub = new UpdateBuilder();
     *     $ub
     *         ->update('users')
     *         ->set('password', md5('password'))
     *         ->where('id = ?');
     * </code>
     *
     * @param string $table The table whose rows are subject to the update
     * @return self instance.
     */
    public function update($table)
    {
        $table = $this->getAdapter()->quoteIdentifier($table);
        return $this->addQueryPart('from', array('table' => $table));
    }

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
     * Specifies one or more restrictions to the query result
     * Replaces any previously specified restrictions, if any
     *
     * <code>
     *     $sb = new SelectBuilder();
     *     $sb
     *         ->select('u.name')
     *         ->from('users', 'u')
     *         ->where('u.id = ?', $id)
     *      ;
     * </code>
     *
     * @param mixed $condition The query restriction predicates
     * @return $this
     */
    public function where($condition)
    {
        $condition = $this->prepareCondition(func_get_args());

        return $this->addQueryPart('where', $condition);
    }

    /**
     * Adds one or more restrictions to the query results, forming a logical
     * conjunction with any previously specified restrictions.
     *
     * <code>
     *     $sb = new SelectBuilder();
     *     $sb
     *         ->select('u')
     *         ->from('users', 'u')
     *         ->where('u.username LIKE ?', '%Smith%')
     *         ->andWhere('u.is_active = ?', 1);
     * </code>
     *
     * @param mixed $condition The query restriction predicates
     * @return $this
     */
    public function andWhere($condition)
    {
        $condition = $this->prepareCondition(func_get_args());

        $where = $this->getQueryPart('where');

        if ($where instanceof CompositeBuilder && $where->getType() == 'AND') {
            $where->add($condition);
        } else {
            $where = new CompositeBuilder(array($where, $condition));
        }
        return $this->addQueryPart('where', $where);
    }

    /**
     * Adds one or more restrictions to the query results, forming a logical
     * disjunction with any previously specified restrictions.
     *
     * <code>
     *     $sb = new SelectBuilder();
     *     $sb
     *         ->select('u.name')
     *         ->from('users', 'u')
     *         ->where('u.id = 1')
     *         ->orWhere('u.id = ?', 2);
     * </code>
     *
     * @param mixed $condition The query restriction predicates
     * @return $this
     */
    public function orWhere($condition)
    {
        $condition = $this->prepareCondition(func_get_args());

        $where = $this->getQueryPart('where');

        if ($where instanceof CompositeBuilder && $where->getType() == 'OR') {
            $where->add($condition);
        } else {
            $where = new CompositeBuilder(array($where, $condition), 'OR');
        }
        return $this->addQueryPart('where', $where);
    }

    /**
     * Sets the maximum number of results to retrieve/update/delete
     *
     * @param integer $limit The maximum number of results to retrieve
     * @param integer $offset
     * @return $this
     */
    public function limit($limit, $offset = 0)
    {
        $this->setLimit($limit);
        $this->setOffset($offset);
        return $this;
    }

    /**
     * Setup limit for the query
     *
     * @param integer $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = (int) $limit;
        return $this;
    }

    /**
     * Setup offset for the query
     *
     * @param integer $offset
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = (int) $offset;
        return $this;
    }
}
