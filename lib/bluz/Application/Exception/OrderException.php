<?php

namespace Bluz\Application\Exception;


class OrderException extends ApplicationException
{
        function setCode($code)
        {
            $this->code = $code;

            switch($code){
                case 1:
                    $message = "Заказ не содержит продуктов";
                    break;
                case 2:
                    $message = "Заказ не создан";
                    break;
                case 3:
                    $message = "Не достаточно средств на кредитном счету";
                    break;
                case 4:
                    $message = "Не установлен пользователь";
                    break;
                default:
                    $message = "Ошибка заказа";
                    break;
            }

            $this->setMessage($message);
        }

    function setMessage($mess)
    {
        $this->message = $mess;
    }
}
