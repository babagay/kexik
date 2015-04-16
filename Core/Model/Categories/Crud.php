<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Categories;
//namespace Core\Model\Categories;

use Bluz\Crud\Table;

/**
 * Crud based on Db\Table
 * 
 */
class Crud extends Table
{

    protected  $table_name = "categories";

    /**
     * {@inheritdoc}
     */
    public function readSet($offset = 0, $limit = 10, $params = array())
    {
        $select = app()->getDb()
            ->select('*')
            ->from('categories', 'c');

        if ($limit) {
            $selectPart = $select->getQueryPart('select');
            $selectPart[0] = 'SQL_CALC_FOUND_ROWS ' . current($selectPart);
            $select->select($selectPart);

            $select->setLimit($limit);
            $select->setOffset($offset);
        }

        $result = $select->execute('\\Application\\Categories\\Row');

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
        // name validator
        $name = isset($data['categories_name'])?$data['categories_name']:null;
        $this->checkName($name);

        // categories_seo_page_name validator
        $categories_seo_page_name = isset($data['categories_seo_page_name'])?$data['categories_seo_page_name']:null;
        $this->checkSeo($categories_seo_page_name);
    }

    /**
     * {@inheritdoc}
     */
    public function validateUpdate($id, $data)
    {
        // name validator
        if (isset($data['categories_name'])) {
            $this->checkName($data['categories_name']);
        }

        // categories_seo_page_name validator
        if (isset($data['categories_seo_page_name'])) {
            $this->checkSeo($data['categories_seo_page_name']);
        }
    }

    /**
     * checkName
     *
     * @param $name
     * @return void
     */
    protected function checkName($name)
    {
        if (empty($name)) {
            $this->addError('Name can\'t be empty', 'name');
        } elseif (!preg_match('/^[a-zA-Zа-яА-Я .-]+$/i', $name)) {
            // TODO
            // $this->addError('Имя содержит толкьо буквы иили точку', 'name');
        }
    }

    /**
     * checkSeo
     *
     * @param $categories_seo_page_name
     * @return void
     */
    protected function checkSeo($categories_seo_page_name)
    {
        // TODO
        if (!preg_match('/^[a-zA-Zа-яА-Я_.-]+$/i', $categories_seo_page_name)) {
            //$this->addError('Недопустимый символ', 'categories_seo_page_name');
        }

        /*
        if (empty($email)) {
            $this->addError('Email can\'t be empty', 'email');
        } elseif (!$email = filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('Email has invalid format', 'email');
        }
        */
    }

    /**
     * @param mixed $primary
     * @param array $data
     * @return int|void
     * @throws NotFoundException
     *
     * Перегрузил родительский метод, чтобы добавить фильтрацию столбцов (filterColumns)
     */
    function updateOne($primary,$data)
    {
        $data =  $this->getTable()->filterColumns($data) ;

        $row = $this->getTable()->findRow($primary);

        if (!$row) {
            throw new NotFoundException("Record not found");
        }

        $this->validate($primary, $data);
        $this->validateUpdate($primary, $data);

        $err_arr = $this->getErrors();
        if( is_array($err_arr) AND count($err_arr) > 0 )
            $this->checkErrors();

        $updateBuilder = app()->getDb()
            ->update('categories')
            ->setArray($data)
            ->where('categories_id = ?', $primary['categories_id']);
        $updateBuilder->execute();
    }
}
