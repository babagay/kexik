<?php


namespace Application\OrderProducts;

use Application\Auth;
use Application\Exception;

use Application\UsersActions;
use Bluz\Application\Application;
use Bluz\Crud\ValidationException;


class Crud extends \Bluz\Crud\Table
{



    function createSet($data)
    {
         throw new \Exception("Not Implemented " . __METHOD__ . " in " . __FILE__);
    }



    public function validateCreate($data)
    {
        // TODO
        if(false){
            $this->addError('error','fieldname');
        }
    }

    public function validateUpdate($id,$data)
    {
        // TODO
        if(false){
            $this->addError('error','fieldname');
        }
    }

    /**
     * Добавлена фильтрация массива $params от лишних полей
     * @param mixed $primary
     * @param array $params
     * @return int
     */
    function _updateOne($primary, $params)
    {
        $self = Table::getInstance();

        $params = $self->filterColumns($params);

        return parent::updateOne($primary, $params);
    }







}
