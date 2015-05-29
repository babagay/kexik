<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Products;

use Bluz\Crud\Table;
use Application\FiltersToProducts;

/**
 * Crud based on Db\Table
 *
 * @category Application
 * @package  Products
 *
 * @author   Anton Shevchuk
 * @created  03.09.12 13:11
 */
class Crud extends Table
{
    /**
     * Example
     * {@inheritdoc}
     */
    public function readSet($offset = 0, $limit = 10, $params = array())
    {
        $select = app()->getDb()
            ->select('*')
            ->from('products', 't');

        if ($limit) {
            $selectPart = $select->getQueryPart('select');
            $selectPart[0] = 'SQL_CALC_FOUND_ROWS ' . current($selectPart);
            $select->select($selectPart);

            $select->setLimit($limit);
            $select->setOffset($offset);
        }

        $result = $select->execute('\\Application\\Products\\Row');

        if ($limit) {
            $total = app()->getDb()->fetchOne('SELECT FOUND_ROWS()');
        } else {
            $total = sizeof($result);
        }

        if (sizeof($result) < $total) {
            http_response_code(206);
            header('Content-Range: items '.$offset.'-'.($offset+sizeof($result)).'/'. $total);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function validateCreate($data)
    {
        $products_id = isset($data['products_id'])?$data['products_id']:null;
        $this->checkArticool($products_id);

        $this->checkArticoolUnique( $products_id );

        $products_barcode = isset($data['products_barcode'])?$data['products_barcode']:null;
        $this->checkBarcode($products_barcode);

        $products_name = isset($data['products_name'])?$data['products_name']:null;
        $this->checkName($products_name);

    }

    /**
     * {@inheritdoc}
     */
    public function validateUpdate($id, $data)
    {
        $products_id = isset($data['products_id'])?$data['products_id']:null;
        $this->checkArticool($products_id);
    }

    /**
     * checkName - Example
     *
     * @param $name
     * @return void
     */
    protected function checkName($name)
    {
        if (empty($name)) {
            $this->addError('Name can\'t be empty', 'products_name');
        } elseif (!preg_match('/^[a-zа-я .-]+/i', $name)) {
            $this->addError('Не допустимый формат', 'products_name');
        }
    }

    /**
     * checkEmail - Example
     *
     * @param $email
     * @return void
     */
    protected function checkEmail($email)
    {
        if (empty($email)) {
            $this->addError('Email can\'t be empty', 'email');
        } elseif (!$email = filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('Email has invalid format', 'email');
        }
    }

    protected function checkArticool($products_id)
    {
        if (empty($products_id)) {
            $this->addError('products_id can\'t be empty', 'products_id');
        } elseif (!preg_match('/^[\d]+$/i', $products_id)) {
            $this->addError('Articool has invalid format', 'products_id');
        }
    }

    protected function checkArticoolUnique($products_id)
    {
        // проверить на уникальность
        $row = $this->getTable()->findRow(['products_id' => $products_id]);

        if( is_null($row) OR $row === false ) {
        } else {
            $this->addError('Значение не уникально', 'products_id');
        }
    }

    protected function checkBarcode($barcode)
    {
        if (empty($barcode)) {
            $this->addError('Поле не должно быть пустым', 'products_barcode');
        } elseif (!preg_match('/^[\d]+$/i', $barcode)) {
            $this->addError('Не верный формат', 'products_barcode');
        }

        // проверить на уникальность
        $row = $this->getTable()->fetch("SELECT products_barcode FROM products WHERE products_barcode = $barcode " );  //['products_barcode' => $barcode]);

        if( is_null($row) OR $row === false ) {
        } elseif( sizeof($row) ) {
            $this->addError('Значение не уникально', 'products_barcode');
        }
    }

    function readOne($primary_key)
    {
        if(is_array($primary_key)){
            return parent::readOne($primary_key);
        }

        //TODO добавить вытягивание категории
    }

    function createOne($data)
    {
        $data['products_last_modified'] = gmdate('Y-m-d H:i:s');

        $id = parent::createOne($data); //  array('products_id'=>'0')

        $id = $data['products_id'] ;

        //  привязка к категории
        if((int)$id > 0)
        if(isset($data['categories_id'])){
            $insertBuilder        = app()->getDb()
                ->insert( 'products_to_categories' )
                ->set( 'categories_id', $data['categories_id'] )
                ->set( 'products_id', $id )
            ;
            $insertBuilder->execute();
        }
    }

    function updateOne($primary, $data)
    {
        $category = app()->getDb()->fetchRow("SELECT * FROM products_to_categories WHERE products_id = '{$data['products_id']}'");
        if(isset($category['categories_id'])){

            if(isset($data['categories_id'])){

                /*
                if((int)$data['categories_id'] === (int)$category['categories_id']){
                    // Do nothing
                } else {
                    $insertBuilder        = app()->getDb()
                        ->insert( 'products_to_categories' )
                        ->set( 'categories_id', $data['categories_id'] )
                        ->set( 'products_id', $data['products_id'] )
                    ;
                    $insertBuilder->execute();
                }
                */

                // FIXME сейчас оно удаляет все записи из products_to_categories
                // TODO поддерживать несколько записей
                app()->getDb()->delete('products_to_categories')->where("products_id = ?",$data['products_id'])->execute();

                $insertBuilder        = app()->getDb()
                    ->insert( 'products_to_categories' )
                    ->set( 'categories_id', $data['categories_id'] )
                    ->set( 'products_id', $data['products_id'] )
                ;
                $insertBuilder->execute();
            }
        }

        FiltersToProducts\Table::getInstance()->dropFilters(['products_id' => $data['products_id']]);
        if (isset($data['filters_id'])) {
            if (sizeof($data['filters_id'])) {
                FiltersToProducts\Table::getInstance()->insertFilters(['filters_id' => $data['filters_id'], 'products_id' => [$data['products_id']]]);
            }
        }

        parent::updateOne($primary,$data);
    }
}
