<?php

namespace Bluz\Basket;

interface BasketInterface {

    /**
     * Очистить корзину
     */
    public function flush();

    /**
     * Вернуть массив элементов корзины
     * @return mixed
     */
    public function getItems();

    /**
     * Загрузить набор продуктов в корзину
     * @param array $products[id:num]
     * @return mixed
     */
    public function set(array $products);

    /**
     * Обновить информацию о продукте в корзине
     * @param int $products_id
     * @param float $products_num
     * @return mixed
     */
    public function updateProduct($products_id, $products_num);

    /**
     * Удалить продукт из корзины
     * @param $products_id
     * @return mixed
     */
    public function removeProduct($products_id);

    /**
     * Добавить новый товар в корзину
     * @param $products_id
     * @param $products_num
     * @return mixed
     */
    public function putProduct($products_id, $products_num);

}