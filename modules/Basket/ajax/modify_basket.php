<?php
/**
 * AJAX closure
 *
 * @author   Anton Shevchuk
 * @created  26.09.11 17:41
 * @return closure

TODO
 * Проверять, если запрос пришел НЕ через аджакс, блокировать или редиректить
 * Такую защиту имеет смысл ставить на все замыкания, обрабатываемые ссылками (ссылка содержит href)
 * TODO перенести логику в модель (сейчас это "толстый контроллер")
 */
namespace Application;

use Bluz;
use \Bluz\Application\Exception\ApplicationException as AppEx;

/**
 * @var \Application\Bootstrap $_this
 */
$_this = $this;

return

    /**
     * mode: add|remove|clear|update
     *
     * @var Bluz\View\View $view
     *
     * Если здесь эхнуть var_dump($articles);
     * И потом вернуть шаблон  return 'getheaders.phtml';
     * То ошибки не будет
     *
     * if (app()->getInstance()->getRequest()->getHeader('X-Requested-With') != 'XMLHttpRequest') {}
     */
    function ($products_id = null,$products_num = null,$mode = 'add',$products = []) use ($view, $_this) {

        $response = 'ok';
        $error = false;

        //  $db = app()->getDb();

        $basket = $_this->getSession()->basket;
        $redir = $total = null;

        switch($mode){
            case 'add':

                if( is_null($basket) ){
                    $basket = array();
                }

                if(isset($basket['products'][$products_id])){
                    if($basket['products'][$products_id] == $products_num){
                        $response = "Товар уже в корзине";
                        $error = true;
                    }
                }

                if(!$error){
                    $basket['products'][$products_id] = $products_num;

                    $_this->getSession()->basket = $basket;
                }

                //TODO протестить
//                try {
//                    app()->getBasket()->putProduct($products_id,$products_num);
//                } catch (AppEx $e) {
//
//                }

                break;

            case 'remove':
                if(is_null($products_id))
                    throw new Bluz\Application\Exception\ApplicationException("product is not set");

                if(isset($basket['products'][$products_id]))
                    unset($basket['products'][$products_id]);

                $_this->getSession()->basket = $basket;

                //TODO протестить
                //app()->getBasket()->removeProduct($products_id);

                if(count($basket['products']) == 0)
                    $redir = "clear";

                break;

            case 'update':
                if(is_null($products_id))
                    throw new Bluz\Application\Exception\ApplicationException("product is not set");

                if($products_num == 0)
                    app()->dispatch("Basket","ajax/modify_basket",['products_id'=>$products_id,'mode'=>'remove']);

                //TODO протестить
                //app()->getBasket()->updateProduct($products_id,$products_num);

                $basket['products'][$products_id] = $products_num;
                $_this->getSession()->basket = $basket;

                break;

            case 'clear':
                // Очистить корзину

                ////app()->useLayout(false);

                app()->getBasket()->flush();

                break;

            case 'set':
                // Загрузить набор продуктов

                app()->getBasket()->set($products);

                break;
        }

        return function () use($redir,$response) {

            $result = array('response' => $response, 'redir' => $redir );

            return $result;
        };


        /*

        // sleep(2); // задержка 2 сек


        // $frame = 7;

        $offset = ($articles_frame_counter  - 1) * $frame; // 1
        $limit = $frame;

        // TODO брать из параметра
        $order_by = "art.dateline";
        $order = "DESC";

        //
        $Articles = new \Core\Articles( array('группа' => $группа, 'offset' => $offset, 'limit' => $limit, 'order_by' => $order_by, 'order' => $order) );

        $headers = $Articles->getHeaders();





        if($headers === false)
            throw new \Bluz\Application\Exception\ApplicationException("Ресурс не найден",404);

        if(is_array($headers) AND count($headers) == 0)
            throw new \Bluz\Application\Exception\ApplicationException("Нет статей",404);

        if(!is_null($группа)){
            $имя_группы_для_крошек = $группа;
        }


        $view->articles = $headers;
*/

        // return 'products.phtml';


    };