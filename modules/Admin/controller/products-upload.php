<?php

    namespace Application;

    use Bluz;

    $_this = $this;

    return

        function (  $asd = null ) use ($_this) {
            /**
             * @var \Application\Bootstrap $this
             * @var \Bluz\View\View $view
             * fb($_this); // Application\Bootstrap
             */



            $_this->resetLayout();
           // $_this->useJson(true);


            if( !isset($_FILES['products_list'] )) return;

            $filename = $_FILES['products_list']['name'];
            $filetype = $_FILES['products_list']['type']; // application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
            $tmp_name = $_FILES['products_list']['tmp_name'];
            $size = $_FILES['products_list']['size'];

            $app_object = app()->getInstance();

            $db =  app()->getDb();

            $arr = explode(".",$filename);

            if($arr[1] != 'xlsx')
                // throw new Bluz\Application\Exception\ApplicationException("Неверное расширение");
                return;

            $path = PATH_DATA . '/files/' ;

            @copy($tmp_name,$path.$filename);

            $logger = new \Bluz\Logger\MyLogger();
            $logger->products("Import started at " . date('Y-m-d H:i:s'));

            // Взять   всех  manufacturers
            $запрос = "SELECT manufacturers_id, manufacturers_name
			FROM manufacturers
			ORDER BY manufacturers_id
			";
            $manufacturers = $db->fetchAll($запрос);

            // Взять все существующие продукты
            $selectBuilder = app()->getDb()
                ->select('p.products_id')
                ->from('products', 'p');
            $products_exist = $selectBuilder->execute();

            $tmp = array();
            if(is_array($products_exist)) {
                foreach( $products_exist as $products_exist_item ) {
                    $tmp[] = $products_exist_item['products_id'];
                }
                if( count( $tmp ) > 0 )
                    $products_exist = $tmp;
            }

            // Пройти все записи Excel-файла
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
            //$objPHPExcel = $objReader->load(PUBLIC_PATH . "/data/files/products1.xlsx");
            $objPHPExcel = $objReader->load($path.$filename);

            $new_products_arr = array();
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                //fb( 'Worksheet - ' . $worksheet->getTitle() ); - название листа

                $ind = 1;
                foreach( $worksheet->getRowIterator() as $row ) {
                    $row_xls = array();
                    if( $row->getRowIndex() == 1 ){
                        // Header
                    } else {
                        // Data
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                        $row_xls[] = $ind;
                        $ind++;
                        foreach ($cellIterator as $cell) {
                            if (!is_null($cell)) {
                                $row_xls[] =  $cell->getCalculatedValue();
                                //$cell->getCoordinate() - A1
                            }
                        }
                        //$new_products_arr[] = $row_xls;

                        // TODO вставлять здесь
                        $current_manufacturer = trim($row_xls[8]);

                        if( (int)$row_xls[1] != 0 AND trim($row_xls[9]) != '' ){
                            $manufacturers_id = null;
                            $manufacturer_exists = false;
                            foreach( $manufacturers as $manufacturer ) {
                                if( $manufacturer['manufacturers_name'] == $current_manufacturer ){
                                    $manufacturer_exists = true;
                                    $manufacturers_id = $manufacturer['manufacturers_id'];
                                    break;
                                }
                            }
                            if($current_manufacturer != '') {
                                // У товара есть производитель, который отсутсвует в базе
                                if($manufacturer_exists === false){
                                    // Добавить производителя
                                    $insertBuilder        = app()->getDb()
                                        ->insert( 'manufacturers' )
                                        ->set( 'manufacturers_name', $current_manufacturer )
                                        ->set( 'date_added', date('Y-m-d H:i:s') )
                                    ;
                                    $manufacturers_id = $insertBuilder->execute();

                                    // Обновить массив производителей
                                    //$запрос = "SELECT * FROM manufacturers ORDER BY manufacturers_id";

                                    $manufacturers[] = array('manufacturers_id' => $manufacturers_id, '$manufacturers_name' => $current_manufacturer);

                                    //$manufacturers = $db->fetchAll($запрос);
                                }
                            } else {
                                // Поле производителя пустое, вставить ссылку на НОНЕЙМ, если он есть
                                if(is_null($manufacturers_id)){
                                    $selectBuilder = app()->getDb()
                                        ->select('*')
                                        ->from('manufacturers', 'm')
                                        ->where('m.manufacturers_name = ?',"noname");
                                    $m = $selectBuilder->execute();
                                    if(isset( $m[0]['manufacturers_id']))
                                        $manufacturers_id = $m[0]['manufacturers_id'];
                                }
                            }

                            $products_id = (int)trim($row_xls[1]);
                            $products_barcode =  trim($row_xls[9]);
                            $products_name =  trim($row_xls[2]);
                            $products_unit =  trim($row_xls[3]);
                            $products_departament =  trim($row_xls[4]);
                            $products_shopping_cart_price =   $row_xls[5];
                            $products_price_wholesale =   $row_xls[6];
                            $products_quantity =   $row_xls[7];

                            if( in_array($products_id,$products_exist) ){
                                // Обновить продукт
                                $updateBuilder = $db
                                    ->update('products')
                                    ->setArray(
                                        array(
                                            'products_id' => $products_id,
                                            'products_barcode' => $products_barcode,
                                            'products_name' => $products_name,
                                            'products_unit' => $products_unit,
                                            'products_departament' => $products_departament,
                                            'products_shopping_cart_price' => $products_shopping_cart_price,
                                            'products_price_wholesale' => $products_price_wholesale,
                                            'products_quantity' => $products_quantity,
                                            'products_last_modified' => date('Y-m-d H:i:s'),
                                            'manufacturers_id' => $manufacturers_id
                                        )
                                    )
                                    ->where('products_id = ?', $products_id);
                                $updateBuilder->execute();
                                $logger->products("Product updated: $products_id ");

                            } else {
                                // Вставить продукт 

                                $insertBuilder        = $db
                                    ->insert( 'products' )
                                    ->set( 'products_id', $products_id )
                                    ->set( 'products_barcode', $products_barcode )
                                    ->set( 'products_name', $products_name )
                                    ->set( 'products_unit', $products_unit )
                                    ->set( 'products_departament', $products_departament )
                                    ->set( 'products_shopping_cart_price', $products_shopping_cart_price )
                                    ->set( 'products_price_wholesale', $products_price_wholesale )
                                    ->set( 'products_quantity', $products_quantity )
                                    ->set( 'manufacturers_id', $manufacturers_id )
                                    ->set( 'products_last_modified', date('Y-m-d H:i:s') )
                                ;
                                $insertBuilder->execute();
                                $logger->products("Product inserted: $products_id ");
                            }
                        }
                    }

                }
            }

            // TODO Взять записи, которые были раньше, но в новом продукт-листе их нет,
            // и перевести их в инвиз










/*
            $selectBuilder
                ->select('pd.products_id')
                ->from('products_description', 'pd');
            $products_description_exist = $selectBuilder->execute();

            $tmp = array();
            if(is_array($products_description_exist)) {
                foreach( $products_description_exist as $products_description_exist_item ) {
                    $tmp[] = $products_description_exist_item['products_id'];
                }
                if( count( $tmp ) > 0 )
                    $products_description_exist = $tmp;
            }
*/



/*
            if(is_array($new_products_arr))
                if(count($new_products_arr) > 0)
                    foreach($new_products_arr as $k => $new_product){

                        if( (int)$new_product[1] === 0) continue;

                        // index|products_id|products_name|products_unit|products_departament|products_shopping_cart_price|products_price_wholesale|products_quantity|manufacturer
                        $manufacturer_exists = false;
                        //$new_manufacturers_id = null;
                        $manufacturers_id = null;
                        if(is_array($manufacturers)) {
                            foreach( $manufacturers as $manufacturer ) {
                                if($manufacturer['manufacturers_name'] == trim($new_product[8])){
                                    $manufacturer_exists = true;
                                    $manufacturers_id = $manufacturer['manufacturers_id'];
                                    break;
                                }
                            }
                        }

                        if(trim($new_product[8]) != '') {
                            // У товара есть производитель, который отсутсвует в базе
                            if($manufacturer_exists === false){
                                // Добавить производителя
                                $insertBuilder        = app()->getDb()
                                    ->insert( 'manufacturers' )
                                    ->set( 'manufacturers_name', trim( $new_product[8] ) )
                                    ->set( 'date_added', date('Y-m-d H:i:s') )
                                ;
                                $manufacturers_id = $insertBuilder->execute();

                                // Обновить массив производителей
                                $запрос = "SELECT * FROM manufacturers ORDER BY manufacturers_id";

                                $manufacturers = $db->fetchAll($запрос);
                            }
                        } else {
                            // Поле производителя пустое, вставить ссылку на НОНЕЙМ, если он есть
                            if(is_null($manufacturers_id)){
                                $selectBuilder = app()->getDb()
                                    ->select('*')
                                    ->from('manufacturers', 'm')
                                    ->where('m.manufacturers_name = ?',"noname");
                                $m = $selectBuilder->execute();
                                if(isset( $m[0]['manufacturers_id']))
                                    $manufacturers_id = $m[0]['manufacturers_id'];
                            }
                        }

                        $products_id = (int)trim($new_product[1]);
                        $products_barcode =  trim($new_product[9]);
                        $products_name =  trim($new_product[2]);
                        $products_unit =  trim($new_product[3]);
                        $products_departament =  trim($new_product[4]);
                        $products_shopping_cart_price =   $new_product[5];
                        $products_price_wholesale =   $new_product[6];
                        $products_quantity =   $new_product[7];

                        if( $products_barcode == '' ) continue;

                       // if(is_array($products_exist)){
                            if( in_array($products_id,$products_exist) ){
                                // Обновить продукт
                                $updateBuilder = $db
                                    ->update('products')
                                    ->setArray(
                                        array(
                                            'products_id' => $products_id,
                                            'products_barcode' => $products_barcode,
                                            'products_name' => $products_name,
                                            'products_unit' => $products_unit,
                                            'products_departament' => $products_departament,
                                            'products_shopping_cart_price' => $products_shopping_cart_price,
                                            'products_price_wholesale' => $products_price_wholesale,
                                            'products_quantity' => $products_quantity,
                                            'products_last_modified' => date('Y-m-d H:i:s'),
                                            'manufacturers_id' => $manufacturers_id
                                        )
                                    )
                                    ->where('products_id = ?', $products_id);
                                $updateBuilder->execute();
                                $logger->products("Product updated: $products_id ");

                            } else {
                                // Вставить продукт
                                $insertBuilder        = $db
                                    ->insert( 'products' )
                                    ->set( 'products_id', $products_id )
                                    ->set( 'products_barcode', $products_barcode )
                                    ->set( 'products_name', $products_name )
                                    ->set( 'products_unit', $products_unit )
                                    ->set( 'products_departament', $products_departament )
                                    ->set( 'products_shopping_cart_price', $products_shopping_cart_price )
                                    ->set( 'products_price_wholesale', $products_price_wholesale )
                                    ->set( 'products_quantity', $products_quantity )
                                    ->set( 'manufacturers_id', $manufacturers_id )
                                    ->set( 'products_last_modified', date('Y-m-d H:i:s') )
                                ;
                                $insertBuilder->execute();
                                $logger->products("Product inserted: $products_id ");
                            }
                        */
                        /*
                        } else {
                            // Вставить продукт
                            $insertBuilder        = $db
                                ->insert( 'products' )
                                ->set( 'products_id', $products_id )
                                ->set( 'products_barcode', $products_barcode )
                                ->set( 'products_name', $products_name )
                                ->set( 'products_unit', $products_unit )
                                ->set( 'products_departament', $products_departament )
                                ->set( 'products_shopping_cart_price', $products_shopping_cart_price )
                                ->set( 'products_price_wholesale', $products_price_wholesale )
                                ->set( 'products_quantity', $products_quantity )
                                ->set( 'manufacturers_id', $manufacturers_id )
                                ->set( 'products_last_modified', date('Y-m-d H:i:s') )
                            ;
                            $insertBuilder->execute();
                            $logger->products("Product inserted: $products_id ");
                        }
            */

                        /*
                        if(is_array($products_description_exist)){
                            if( in_array($products_id,$products_description_exist) ){
                                // Обновить описание продукта
                                $updateBuilder = $db
                                    ->update('products_description')
                                    ->setArray(
                                        array(
                                            'products_id' => $products_id,
                                            // 'products_barcode' => $products_barcode,
                                            'manufacturers_id' => $manufacturers_id,
                                            //'products_visibility' => '0'
                                            'products_last_modified' => date('Y-m-d H:i:s')
                                        )
                                    )
                                    ->where('products_id = ?', $products_id);
                                $updateBuilder->execute();
                                $logger->products("Product description updated for: $products_id ");
                            } else {
                                // Вставить описание продукта

                                $insertBuilder        = $db
                                    ->insert( 'products_description' )
                                    ->set( 'products_id', $products_id )
                                    // ->set( 'products_barcode', $products_barcode )
                                    ->set( 'products_visibility', '0' )
                                    ->set( 'manufacturers_id', $manufacturers_id )
                                    ->set( 'products_last_modified', date('Y-m-d H:i:s') )
                                ;
                                $insertBuilder->execute();
                                $logger->products("Product description inserted for: $products_id ");
                            }
                        } else {
                            // Вставить описание продукта
                            $insertBuilder        = $db
                                ->insert( 'products_description' )
                                ->set( 'products_id', $products_id )
                                //->set( 'products_barcode', $products_barcode )
                                ->set( 'products_visibility', '0' )
                                ->set( 'manufacturers_id', $manufacturers_id )
                                ->set( 'products_last_modified', date('Y-m-d H:i:s') )
                            ;
                            $insertBuilder->execute();
                            $logger->products("Product description inserted for: $products_id ");
                        }
                    */

                   // }

            $logger->products("Import ended at " . date('Y-m-d H:i:s'));

            /**
             *
             *
             * Взять все products_id в $products_arr
             * Взять записи, которые были раньше, но в новом продукт-листе их нет, и перевести в режим invisible.
             *  Для этих записей выставить Остаток = 0 в т products.
             *
             * Проверять $new_products_arr. Если такой products_id уже есть, делать UPDATE, если нет Insert
             * Взять массив артикулов из products_description
             * Проверять, если такого айдишника там нет, вставлять запись(products_id, products_barcode, products_visibility, manufacturers_id)
             * если есть, делать апдейт manufacturers_id
             *
             * Писать в лог айдишники (записанные и проапдейченные)
             *
             * Штрихкод можно сохранять в products_description на случай, если продукт будет удален из products
             * После загрузки продуктов можно Взять все продукты, высосать их products_id и баркод и закинуть в products_description.
             * Это можно делать через редирект.
             * А потом можно вручную заполнять описание каждого продукта
             *
             * Внешние ключи можно сохранить. Но они могут быть для т products_description, а не для products.
             */

            //--

            // echo "<script>  </script>";

        };
