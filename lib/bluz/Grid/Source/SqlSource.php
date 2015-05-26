<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Grid\Source;

use Bluz\Application\Application;
use Bluz\Db;
use Bluz\Grid;
use Bluz\Translator\Translator;

/**
 * SQL Source Adapter for Grid package
 *
 * @category Bluz
 * @package  Grid
 *
 * @author   Anton Shevchuk
 * @created  27.08.12 10:10
 */
class SqlSource extends AbstractSource
{
    /**
     * setSource
     *
     * @param $source
     * @throws \Bluz\Grid\GridException
     * @return self
     */
    public function setSource($source)
    {
        if (!is_string($source)) {
            throw new Grid\GridException("Source of SqlSource should be string with SQL query");
        }
        $this->source = $source;

        return $this;
    }

    /**
     * process
     *
     * @param array $settings
     * @return \Bluz\Grid\Data
     */
    public function process(array $settings = array())
    {//fb($settings['filters']);
        // process filters
        $where = array();
        if (!empty($settings['filters'])) {
            foreach ($settings['filters'] as $column => $filters) {
                foreach ($filters as $filter => $value) {

                    if(preg_match('/fulltext-/i',$value,$m)){
                        $value = str_replace ('fulltext-','',$value);
                        $value = Translator::translitBackToCyr($value);
                        // TODO fulltext search here
                    } else {

                        if( $filter == Grid\Grid::FILTER_LIKE ) {
                            $value = Translator::translitBackToCyr($value);
                            $value = '%' . $value . '%';
                        }
                        $where[] = $column . ' ' .
                            $this->filters[$filter] . ' ' .
                            Db\Db::getDefaultAdapter()->quote( $value );
                    }
                }
            }
        }

        // process orders
        $orders = array();
        if (!empty($settings['orders'])) {
            // Obtain a list of columns
            foreach ($settings['orders'] as $column => $order) {
                $column = Db\Db::getDefaultAdapter()->quoteIdentifier($column);
                $orders[] = $column . ' ' . $order;
            }
        }

        // process pages
        $limit = ' LIMIT ' . ($settings['page'] - 1) * $settings['limit'] . ', ' . $settings['limit'];

        // prepare query
        $connect = Application::getInstance()->getConfigData('db', 'connect');
        if (strtolower($connect['type']) == 'mysql') { // MySQL
            $dataSql = preg_replace('/SELECT\s(.*?)\sFROM/is', 'SELECT SQL_CALC_FOUND_ROWS $1 FROM', $this->source, 1);
            $countSql = 'SELECT FOUND_ROWS()';
        } else { // other
            $dataSql = $this->source;
            $countSql = preg_replace('/SELECT\s(.*?)\sFROM/is', 'SELECT COUNT(*) FROM', $this->source, 1);
            if (sizeof($where)) {
                $countSql .= ' WHERE ' . (join(' AND ', $where));
            }
        }

        if (sizeof($where)) {
            $dataSql .= ' WHERE ' . (join(' AND ', $where));
        }
        if (sizeof($orders)) {
            $dataSql .= ' ORDER BY ' . (join(', ', $orders));
        }
        $dataSql .= $limit;

        // [FIXME]: ошибка при  $this->source = NULL
        if($dataSql == $limit) $dataSql = '';

        // run queries
        $data = Db\Db::getDefaultAdapter()->fetchAll($dataSql);
        $total = Db\Db::getDefaultAdapter()->fetchOne($countSql);
        $gridData = new Grid\Data($data);
        $gridData->setTotal($total);
        return $gridData;

    }
}
