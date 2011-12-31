<?php

    namespace Application\Orders;

    class Table extends \Bluz\Db\Table
    {
        /**
         * Статусы заказа
         */
        const STATUS_OPEN = 1; // новый заказ
        const STATUS_CLOSED = 2; // успешно отработан и закрыт
        const STATUS_CANCELED = 0; // отменен

        /**
         * Типы заказов
         */
        const ORDERTYPE_FRONTEND        = 1;
        const ORDERTYPE_BACKEND         = 2;
        const ORDERTYPE_FRONTEND_CLONED = 3;

        public $order_status_arr = [self::STATUS_OPEN     => 'новый заказ',
                                    self::STATUS_CLOSED   => 'успешно отработан и закрыт',
                                    self::STATUS_CANCELED => 'отменен'];

        public $order_type_arr = [self::ORDERTYPE_FRONTEND        => 'Создан в магазине',
                                  self::ORDERTYPE_BACKEND         => 'Создан в админке',
                                  self::ORDERTYPE_FRONTEND_CLONED => 'Клонирован пользователем',
        ];

        /**
         * Table
         *
         * @var string
         */
        protected $table = 'orders';

        /**
         * Primary key(s)
         * @var array
         */
        protected $primary = array('orders_id');

        // FIXME Если  $this->order_status_arr сделать protected, его не возможно полуычить через
        // Orders\Table::getInstance()->getStatusArr(), поэтому сейчас он public
        function getStatusArr ()
        {
            return $this->order_status_arr;
        }
    }
