<?php

namespace Application;

use Core\Helper\getDate;

return

    /**
     * Обязательно передавать type, orders_id
     * Если передавать пост-запросом (используя data-ajax-method="post"), параметры попадают в аргументы функции
     * Если обычным гет-запросом, то нужно ихх дописывать в урл
     */
    function ($type = null, $orders_id = null) use ($view) {

        //$app_object = Application\Bootstrap::getInstance();
        $app_object = app()->getInstance();

        /**
         * Тип документа
         */
        if (is_null($type))
            $type = app()->getRequest()->getParam('type', null);

        /**
         * ID заказа
         */
        if (is_null($orders_id))
            $orders_id = app()->getRequest()->getParam('orders_id', null);

        # Проверка авторизации
        $user = app()->getAuth()->getIdentity();

        if (!is_object($user))
            throw new \Bluz\Application\Exception\ApplicationException("Такой страницы нет", 404);

        $access_is_open = $user->hasPrivilege($module = 'admin', $privilege = 'Management');
        if ($access_is_open !== true)
            throw new \Bluz\Application\Exception\ApplicationException("Такой страницы нет", 404);

        $file_tpl = $fieldValues = $localTemplateName = $remoteTemplateName = $products = $fileName = null;
        $total_header = "Сумма заказа";

        if (is_null($orders_id))
            throw new \Bluz\Application\Exception\ApplicationException("Такой страницы нет", 404);

        # TODO выгребаем заказ
        $crudController = new \Bluz\Controller\Crud();

        $crudController->setCrud(Orders\Crud::getInstance());

        $order = $crudController();

        $orders_data = $order['row']->getData();

        $total_cache = $total_recalculated_discounted = $total_recalculated = 0;
        $index       = 1;

        #
        $livedocx_options_arr = app()->getConfig()->getData('auth', 'livedocx');
        $wsdl                 = $livedocx_options_arr['base_url'];

        // [!] При отсутсвии соединения с инетом здесь вываливает Error 503
        $soap = new \Awakenweb\Livedocx\Soap\Client(new \SoapClient($wsdl));
        $soap->connect($livedocx_options_arr['username'], $livedocx_options_arr['password']);
        $livedocx = new \Awakenweb\Livedocx\Livedocx($soap, new \Awakenweb\Livedocx\Container());

        $template = $livedocx->createLocalTemplate();

        # Выбор шаблона
        switch ($type) {
            case 'type_1': // TODO имя типа

                $localTemplateName = $remoteTemplateName = "form_1.docx";

                $fileName = "form_1.pdf";

                // Путь к файлу шаблона
                $file_tpl = PATH_DATA . '/files/' . $localTemplateName;

                /*
                // Попытка срендерить массив продуктов отдельно (Работает)
                $products_table = app()->dispatch(
                    'admin',
                    'docx-template',
                    array(
                        'template' => 'products', // совпадает с именем темплейта в view/docx
                        'vars' => array('products' => $orders_data['products'], 'total' => $orders_data['total'] )
                    )
                )->render();
                */

                if ((string)$orders_data['delivery_date'] == '') $delivery_date = date("F j, Y") . " 13-00";
                else $delivery_date = app()->getDate()->transformDate($orders_data['delivery_date']);

                // Задаём значения переменным
                $fieldValues = array(
                    'date'             => app()->getDate()->today(),
                    'customer'         => $orders_data['user']['name'],
                    'delivery_address' => $orders_data['address'],
                    'delivery_date'    => $delivery_date,
                    'phone'            => $orders_data['user']['phone'],
                    'notes'            => $orders_data['notes'],
                    'price_type'       => $orders_data['price_type'],
                    'delivery_time'    => $delivery_date,
                    'payment_type'     => $orders_data['payment_type']['type_name'],
                );

                $discount = (int)$orders_data['user_discount'];

                $products = array();
                foreach ($orders_data['products'] as $product) {
                    if ($product['products_num'] < 0) continue;
                    $tmp                              = array();
                    $tmp['products_name']             = $product['products_name'];
                    $tmp['products_num']              = round($product['products_num'],2);
                    $tmp['products_unit']             = $product['products_unit'];
                    $tmp['products_shoppingcart_price'] = $product['price']; // Берем зафиксированную цену
                    $tmp['products_price_discounted'] = $product['price'] - ($product['price'] * $discount / 100); // Цена со скидкой
                    $tmp['products_total']            = $tmp['products_shoppingcart_price'] * $tmp['products_num'];
                    $tmp['products_total_discounted'] = $tmp['products_price_discounted'] * $tmp['products_num'];

                    $total_recalculated_discounted += $tmp['products_total_discounted'];
                    $total_recalculated += $tmp['products_total'];

                    $tmp['products_shoppingcart_price'] = \PHPExcel_Calculation_MathTrig::ROUNDUP($tmp['products_shoppingcart_price'], 2);
                    $tmp['products_total']              = \PHPExcel_Calculation_MathTrig::ROUNDUP($tmp['products_total'], 2); //round($tmp['products_total'] * 100, 2) / 100;

                    $products[] = $tmp;
                }

                if ($discount > 0) $total_header = "Сумма заказа со скидкой $discount%";

                //$total_recalculated = round($total_recalculated * 100, 2) / 100;
                $total_recalculated = \PHPExcel_Calculation_MathTrig::ROUNDUP($total_recalculated, 2);
                //$total_recalculated_discounted = round($total_recalculated_discounted * 100, 2) / 100;
                $total_recalculated_discounted = \PHPExcel_Calculation_MathTrig::ROUNDUP($total_recalculated_discounted, 2);

                if ($orders_data['payment_type']['key'] == 'cache') $total_cache = $total_recalculated_discounted;

                break;

            case 'type_2':

                $localTemplateName = $remoteTemplateName = "form_2.docx";

                $fileName = "form_2.pdf";

                // Путь к файлу шаблона
                $file_tpl = PATH_DATA . '/files/' . $localTemplateName;

                if ((string)$orders_data['delivery_date'] == '') $delivery_date = date("F j, Y") . " 13-00";
                else $delivery_date = app()->getDate()->transformDate($orders_data['delivery_date']);

                // Задаём значения переменным
                $fieldValues = array(
                    'delivery_date'    => $delivery_date,
                    'date'             => app()->getDate()->today(),
                    'orders_date'      => app()->getDate()->transformDate($orders_data['date_added']),
                    'customer'         => $orders_data['user']['name'],
                    'employee'         => "Артём",
                    'delivery_address' => $orders_data['address'],
                    'phone'            => $orders_data['user']['phone'],
                    'notes'            => $orders_data['notes'],
                    'total'            => $orders_data['total'],
                    'id'               => $orders_data['orders_id'],
                );

                $discount = (int)$orders_data['user_discount'];
                $products = array();
                foreach ($orders_data['products'] as $product) {
                    if ($product['products_num'] < 0) continue;
                    $tmp                  = array();
                    $tmp['products_name'] = $product['products_name'];
                    $tmp['products_num']  = round($product['products_num'],2);
                    $tmp['products_unit'] = $product['products_unit'];

                    $tmp['products_price_discounted'] = $product['price'] - ($product['price'] * $discount / 100); // Цена со скидкой
                    $tmp['products_total']            = $product['price'] * $product['products_num'];
                    $tmp['products_total_discounted'] = $tmp['products_price_discounted'] * $tmp['products_num'];
                    $total_recalculated_discounted += $tmp['products_total_discounted'];

                    $products[] = $tmp;
                }

                $total_recalculated_discounted = \PHPExcel_Calculation_MathTrig::ROUNDUP($total_recalculated_discounted, 2);

                break;

            case 'type_3':
                $localTemplateName = $remoteTemplateName = "form_3.docx";

                $fileName = "form_3.pdf";

                // Путь к файлу шаблона
                $file_tpl = PATH_DATA . '/files/' . $localTemplateName;

                if ((string)$orders_data['delivery_date'] == '') $delivery_date = date("F j, Y") . " 13-00";
                else $delivery_date = app()->getDate()->transformDate($orders_data['delivery_date']);

                $fieldValues = array(
                    'customer'         => $orders_data['user']['name'],
                    'phone'            => $orders_data['user']['phone'],
                    'delivery_address' => $orders_data['address'],
                    'delivery_date'    => $delivery_date,
                    'date'             => app()->getDate()->today(),
                    'orders_date'      => $orders_data['date_added'],
                    'notes'            => $orders_data['notes'],
                    'payment_type'     => $orders_data['payment_type']['type_name'],
                    'id'               => $orders_data['orders_id'],
                );

                $discount = (int)$orders_data['user_discount'];
                $products = array();

                foreach ($orders_data['products'] as $product) {
                    if ($product['products_num'] < 0) continue;
                    $tmp                   = array();
                    $tmp['index']          = $index++;
                    $tmp['products_name']  = $product['products_name'];
                    $tmp['price']          = \PHPExcel_Calculation_MathTrig::ROUNDUP($product['price'], 2);
                    $tmp['products_price'] = \PHPExcel_Calculation_MathTrig::ROUNDUP($product['products_price'], 2);
                    $tmp['barcode']        = $product['products_barcode'];
                    $tmp['products_id']    = $product['products_id'];
                    $tmp['number']         = round($product['products_num'],2);
                    $tmp['unit']           = $product['products_unit'];


                    $tmp['products_price_discounted'] = $product['price'] - ($product['price'] * $discount / 100); // Цена со скидкой
                    $tmp['products_total']            = $product['price'] * $product['products_num'];
                    $tmp['products_total_discounted'] = $tmp['products_price_discounted'] * $product['products_num'];
                    $total_recalculated_discounted += $tmp['products_total_discounted'];

                    $products[] = $tmp;
                }

                $total_recalculated_discounted = \PHPExcel_Calculation_MathTrig::ROUNDUP($total_recalculated_discounted, 2);

                break;
        }

        #
        if (is_null($file_tpl) OR is_null($fieldValues))
            throw new \Bluz\Application\Exception\ApplicationException("Такой страницы нет", 404);

        # Генерация документа
        $template->setName($localTemplateName, PATH_DATA . '/files/');
        $template->upload();

        // Блок
        $block = $livedocx->createBlock();
        $block->setName('block1');
        $block->bind($products);
        $livedocx->assign($block);

        // Параметры, объединенные в массив
        $livedocx->assign($fieldValues);

        // Отдельный параметр
        $livedocx->assign('orders_id', $orders_id);
        $livedocx->assign('total_header', $total_header);
        $livedocx->assign('total', $total_recalculated_discounted /*$orders_data['total']*/);
        $livedocx->assign('total_cache', $total_cache);

        $remoteTemplate = $livedocx->createRemoteTemplate();

        $remoteTemplate->setName($remoteTemplateName)
            ->setAsActive();

        $document = $livedocx->prepare();
        $document->create();

        //file_put_contents(PATH_ROOT . '/public/uploads/myPdfFile.pdf', $document->retrieve('pdf'));

        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fileName");
        //header("Content-Type: application/msword");
        header("Content-Type: application/pdf");
        header("Content-Transfer-Encoding: binary");
        header('Accept-Ranges: bytes');

        echo $document->retrieve('pdf');

        die;

        /*
         * Даннное Подключение к сервису livedocx работает, но не известно, как генерировать таблицу
         *
          $livedocx_options_arr = app()->getConfig()->getData('auth','livedocx') ;

        // Выключаем WSDL кэширование
        ini_set ('soap.wsdl_cache_enabled', 0);

        // Выставляем временную зону
        date_default_timezone_set('Europe/Moscow');


        // Создаём экземпляр объекта Soap и передаём ему свои учетные данные
        $soap = new \SoapClient( $livedocx_options_arr['base_url'] );



        $soap->LogIn(
            array(
                'username' =>  $livedocx_options_arr['username'],
                'password' =>  $livedocx_options_arr['password']
            )
        );




        $data = file_get_contents($file_tpl);

        // Установка расширения файла .docx и параметров кодирования
        $soap->SetLocalTemplate(
            array(
                'template' => base64_encode($data),
                'format'   => 'docx'
            )
        );



        // Эта хитрая функция преобразует массив c переменными в то что понимает SOAP
        function assocArrayToArrayOfArrayOfString ($assoc)
        {
            $arrayKeys   = array_keys($assoc);
            $arrayValues = array_values($assoc);
            return array ($arrayKeys, $arrayValues);
        }

        // Передаём переменные в наш LiveDocx объект
        $soap->SetFieldValues(
            array (
                'fieldValues' => assocArrayToArrayOfArrayOfString($fieldValues)
            )
        );

        // Формируем документ
        $soap->CreateDocument();
        $result = $soap->RetrieveDocument(
            array(
                'format' => 'docx'
            )
        );
        $doc = base64_decode($result->RetrieveDocumentResult);

        // Разрываем сессию с SOAP
        $soap->LogOut();

        // Отдаём вордовский файл
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        $fileName = "Документ.doc";
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Type: application/msword");
        header("Content-Transfer-Encoding: binary");

        unset($soap);

        echo $doc;
        die;
        */


        //$app_object->useLayout(false);

        return false;

    };