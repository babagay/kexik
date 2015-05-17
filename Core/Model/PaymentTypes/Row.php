<?php

namespace Application\PaymentTypes;

/**
 * Class PaymentTypes Row
 * @package Application\PaymentTypes
 *
 * @property integer $payment_types_id
 * @property string $key
 * @property string $type_name
 * @property string $pay_by
 *
 * @category Application
 * @package  PaymentTypes
 */
class Row extends \Bluz\Db\Row
{
    /**
     * isBasic - Example
     *
     * @return boolean
     */
    public function isBasic()
    {
        //return in_array(strtolower($this->name), Table::getInstance()->getBasicRoles());
    }
}
