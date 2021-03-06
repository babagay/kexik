<?php

    namespace Application;

    use Bluz;

    $_this = $this;

    /**
     * Загрузка продуктов
     *
     * Формт файла: xlsx
     * Название файла: латиница
     * Формат таблицы в эксель-файле:
     * первая строка таблицы - заголовок
     * поля:
     *      Артикул
     *      Наименование
     *      Ед.изм
     *      Цена розн
     *      Цена опт
     *      Остаток
     *      Бренд
     *      Штрихкод
     *
     * Активный вариант: загруждются только те продукты , у которых есть категория
     * Вариант: загружаются все продукты
     *
     * Использование:
     * - можно указать последний удачно загруженный продукт ($id)
     * - если задать parse_category = 1 , из 12-го поля будут вытаскиваться категории
     * - если $do_update_only = true, тогда модифицируются только существующие продукты, новые не добавляются
     *
     * ALTERNATE:
     * Происходит только обновление свойств существующих продуктов ($do_update_only = true;)
     *
     * @param null|int $parse_category - если = 1, задействуется парсинг категорий
     * @param null|int $id - последний успешно внесенный продукт. Если указан id, данные в базу пойдут, начиная с id+1
     * @param null|int $package_size - размер пачки вгружаемых данных     *
     * @param bool $do_update_only - Только обновлять существующие продукты
     * @throws \PHPExcel_Calculation_Exception
     * @throws \PHPExcel_Reader_Exception
     * @internal param Bootstrap $this
     * @internal param Bluz\View\View $view
     * @internal param Bootstrap $_this
     *
     * TODO обнулять $id при рефрэше страницы
     *      По завершении обработки сохранять $id в базу
     *      Передавать $id в качестве параметра метода или здесь же выгребать из базы
     *      $package_size хранить в базе или в конфиге
     *      Если выход из цикла произошел раньше, чем $current_item == $package_size,
     *          считать, что это была финальная пачка. Сгенерить сообщение (напр, в лог)
     */
    return

        function (  $parse_category = null,
                    $id = null,
                    $package_size = null,
                    $do_update_only = true)
        use ($_this) {

            // Test
            // total: 31 640
            // $id = 74;
            $package_size = 5000;

            /**
             * Сколько ячеек вытягивать из одной строки эксель-файла
             */
            $CELL_COUNT = 12;

            $operate_the_product = false;

            /**
             * Сколько строк обработано
             */
            $number_of_operatedproducts = -1;

            /**
             * Сколько продуктов реально обновлено
             */
            $products_updated = 0;

            /**
             * Массив категорий продукта
             */
            $category_arr = null;

            /**
             * id производителяпод названием Noname
             */
            $manufacturers_noname_id = null;

            /**
             * Счетчик айтемов в пачке
             */
            $current_item = 1;

            $package_size *= 1;

            $app_object = app()->getInstance();

            $db =  app()->getDb();

            $path = PATH_DATA . '/files/' ;

            $logger = new \Bluz\Logger\MyLogger();

            $_this->resetLayout();
            // $_this->useJson(true);

            if( (int)$id === 0) $id = null;

            if( !isset($_FILES['products_list'] )) return;

            $filename = $_FILES['products_list']['name'];
            $filetype = $_FILES['products_list']['type']; // application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
            $tmp_name = $_FILES['products_list']['tmp_name'];
            $size = $_FILES['products_list']['size'];

            $selectBuilder = app()->getDb()
                ->select('*')
                ->from('manufacturers', 'm')
                ->where('m.manufacturers_name = ?',"noname");
            $m = $selectBuilder->execute();

            if(isset( $m[0]['manufacturers_id'])){
                $manufacturers_noname_id = $m[0]['manufacturers_id'];

            } else {

                $insertBuilder        = app()->getDb()
                    ->insert( 'manufacturers' )
                    ->set( 'manufacturers_name', 'noname' )
                    ->set( 'date_added', date('Y-m-d H:i:s') )
                ;
                $manufacturers_noname_id = $insertBuilder->execute();
            }

            $arr = explode(".",$filename);

            if($arr[1] != "xlsx")
                if($arr[1] != "xls") {
                // throw new Bluz\Application\Exception\ApplicationException("Неверное расширение");
                return;
            }

            @copy($tmp_name,$path.$filename);

            $logger->products("Import started at " . date('Y-m-d H:i:s'));

            if($parse_category == 1){
               $logger->products("Category parsing is enabled");
            } else {
              $logger->products("Category parsing is disabled");
            }

            // Взять   всех  manufacturers
            $запрос = " SELECT manufacturers_id, manufacturers_name
                        FROM manufacturers
                        ORDER BY manufacturers_id
                        ";
            $manufacturers = $db->fetchAll($запрос);
            $logger->products("Existing Manufacturers have been fetched");

            // Взять все существующие продукты
            $selectBuilder = app()->getDb()
                ->select('p.products_id')
                ->from('products', 'p');
            $products_exist = $selectBuilder->execute();
            $logger->products("Existing Products have been fetched");

            $tmp = array();
            if(is_array($products_exist)) {
                foreach( $products_exist as $products_exist_item ) {
                    $tmp[] = $products_exist_item['products_id'];
                }
                if( count( $tmp ) > 0 )
                    $products_exist = $tmp;
            }

            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');

            //$objPHPExcel = $objReader->load(PUBLIC_PATH . "/data/files/products1.xlsx");
            $objPHPExcel = $objReader->load($path.$filename);

            if( !is_object($objPHPExcel) )
                $logger->products("objPHPExcel не является объектом вообще");
            if( !($objPHPExcel instanceof \PHPExcel) )
                $logger->products("objPHPExcel не является объектом PHPExcel");

            $logger->products("File loaded " . $path.$filename);

            $new_products_arr = array();

            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                // Проход по листам документа
                ///fb( 'Worksheet - ' . $worksheet->getTitle() ); - название листа
                $logger->products("Start walking throw product items");
                $ind = 1;

                $header_skipped = false;

                foreach( $worksheet->getRowIterator() as $row ) {

                    // Проход по строкам листа
                    //$logger->products($row);
                    $row_xls = array();
                    $category_arr = array();

                    if(!$header_skipped){
                        if( $row->getRowIndex() == 1 ){
                            // Skip header
                            $header_skipped = true;
                        }
                    } else {
                        // Data
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                        $row_xls[] = $ind;
                        $ind++;

                        // Формируем массив полей текущего продукта
                        $t = 0;
                        foreach ($cellIterator as $cell) {
                            if (!is_null($cell)) {
                                $row_xls[] =  $cell->getCalculatedValue();
                                ///$cell->getCoordinate() - A1
                                $t++;
                            }

                            if($t > $CELL_COUNT)
                                break;
                        }

                        /**
                         * Если задан id,
                         * тогда начинать вносить продукты только после того, как флаг $operate_the_product станет true
                         */
                        if(!is_null($id)){
                            if((int)trim($row_xls[1]) == $id){
                                $operate_the_product = true;
                                continue;
                            } else {
                                if($operate_the_product === false){
                                    continue;
                                }
                            }
                        }

                        // [!]:ALTERNATE scheme of product update
                        $number_of_operatedproducts  = $row_xls[0]; // Номер по порядку
                        $products_id                 = $row_xls[1]; // Артикул
                        $products_name               = $row_xls[2]; // Название продукта
                        $products_unit               = $row_xls[3]; // кг
                        $products_shoppingcart_price = $row_xls[4]; // Розница
                        $products_price              = $row_xls[5]; // Оптовая цена
                        $products_quantity           = $row_xls[6]; // Остаток
                        $products_vendor             = $row_xls[7]; // Брэнд
                        $products_barcode            = $row_xls[8]; // Штрихкод

                        $updateBuilder = $db
                            ->update('products')
                            ->setArray(
                                array(
                                     // 'products_shoppingcart_price' => $products_shoppingcart_price,
                                    'products_price' => $products_price,
                                    'products_quantity' => $products_quantity,
                                    'products_name' => $products_name,
                                    'products_unit' => $products_unit,
                                    'products_last_modified' => date('Y-m-d H:i:s'), // app()->getDate()->today(),
                                )
                            )
                            ->where('products_barcode = ?', $products_barcode);
                        if( $updateBuilder->execute() ) {
                            $products_updated++;
                            $logger->products("Product updated: $products_id (barcode: $products_barcode) ");
                        } else
                            $logger->products("Product not found: $products_id (barcode: $products_barcode) ");

                        $current_item++;

                        if( $package_size > 0  AND $current_item > $package_size )
                            break;

                        // [!]
                        continue;

                        $current_manufacturer = trim($products_vendor);

                        if( (int)$row_xls[1] != 0 AND trim($row_xls[9]) != '' ){
                            $manufacturers_id = null;
                            $manufacturer_exists = false;
                            foreach( $manufacturers as $manufacturer ) {
                              if(isset($manufacturer['manufacturers_name'])){
                                if( $manufacturer['manufacturers_name'] == $current_manufacturer ){
                                    $manufacturer_exists = true;
                                    $manufacturers_id = $manufacturer['manufacturers_id'];
                                    break;
                                }
                              } else {
                              // manufacturers_name отсутсвует - FIXME: узнать, как это возникает
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

                                    $manufacturers[] = array('manufacturers_id' => $manufacturers_id, '$manufacturers_name' => $current_manufacturer);

                                }
                            } else {
                                // Поле производителя пустое, вставить ссылку на НОНЕЙМ
                                if(is_null($manufacturers_id)){
                                    $manufacturers_id = $manufacturers_noname_id;
                                }
                            }

                            $products_id = (int)trim($row_xls[1]);
                            $products_barcode =  trim($row_xls[9]);
                            $products_name =  trim($row_xls[2]);
                            $products_unit =  trim($row_xls[3]);
                            $products_departament =  trim($row_xls[4]);
                            $products_shoppingcart_price =   $row_xls[5];
                            $products_price =   $row_xls[6];
                            $products_quantity =   $row_xls[7];

                            $products_category = null;

                            if($parse_category == 1){
                              if(isset($row_xls[12])){
                                  $row_xls[12] = trim($row_xls[12]);
                                  if($row_xls[12] != ''){
                                      $products_category = $row_xls[12];
                                      $products_category = str_replace(" ","",$products_category);
                                      if(strpos($products_category,",") > 0){
                                          // Поле содержит перечисление категорий
                                          $category_arr = explode(",",$products_category);
                                      } else {
                                          $category_arr[] = $products_category;
                                      }
                                  }
                              }

                              if(count($category_arr) == 0){
                                  // Пропускать продукты, у которых категория пустая
                                  $logger->products("Product skipped (category is empty): $products_id ");
                                  continue;
                              }
                            }

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
                                            'products_shoppingcart_price' => $products_shoppingcart_price,
                                            'products_price' => $products_price,
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
                                if(!$do_update_only){
                                    $insertBuilder        = $db
                                        ->insert( 'products' )
                                        ->set( 'products_id', $products_id )
                                        ->set( 'products_barcode', $products_barcode )
                                        ->set( 'products_name', $products_name )
                                        ->set( 'products_unit', $products_unit )
                                        ->set( 'products_departament', $products_departament )
                                        ->set( 'products_shoppingcart_price', $products_shoppingcart_price )
                                        ->set( 'products_price', $products_price )
                                        ->set( 'products_quantity', $products_quantity )
                                        ->set( 'manufacturers_id', $manufacturers_id )
                                        ->set( 'products_last_modified', date('Y-m-d H:i:s') )
                                    ;
                                    $insertBuilder->execute();
                                    $logger->products("Product inserted: $products_id ");
                                }
                            }

                           // if( !is_null($products_category) ){
                                // Привязать к категории

                               // if(!is_null($category_arr)){
                                    // Категорий несколько
                                  //  if(is_array($category_arr)){
                                      if($parse_category == 1){
                                        foreach($category_arr as $category_item){
                                            // [!] FIXME убрать костыль
                                            if($category_item == 79) $category_item = 80;


                                            $запрос_привязки_к_категории = " SELECT products_id
                                            FROM products_to_categories
                                            WHERE products_id = '$products_id'
                                            AND categories_id = '$category_item'
                                            ";
                                            $linked_products_id = $db->fetchOne($запрос_привязки_к_категории);

                                            if( $linked_products_id == $products_id ){

                                                // update не делаем ,а просто пропускаем
                                                /*
                                                $updateBuilder = $db
                                                    ->update('products_to_categories')
                                                    ->setArray(
                                                        array(
                                                            'products_id' => $products_id,
                                                            'categories_id' => $category_item,
                                                        )
                                                    )
                                                    ->where('products_id = ?', $products_id);
                                                $updateBuilder->execute();
                                                */
                                                $logger->products("Product $products_id is already linked to category: $category_item ");

                                            } else {
                                                // insert
                                                $insertBuilder        = $db
                                                    ->insert( 'products_to_categories' )
                                                    ->set( 'products_id', $products_id )
                                                    ->set( 'categories_id', $category_item )
                                                ;
                                                $insertBuilder->execute();
                                                $logger->products("Product $products_id linked to category: $category_item ");
                                            }
                                        }
                                      }
                                 //   }

                               // }
                                /* else {
                                    // Категория одна
                                    $запрос_привязки_к_категории = " SELECT products_id
                                            FROM products_to_categories
                                            WHERE products_id = $products_id
                                            ";
                                    $linked_products_id = $db->fetchOne($запрос_привязки_к_категории);

                                    if( (int)$linked_products_id == $products_id ){
                                        // update
                                        $updateBuilder = $db
                                            ->update('products_to_categories')
                                            ->setArray(
                                                array(
                                                    'products_id' => $products_id,
                                                    'categories_id' => $products_category,
                                                )
                                            )
                                            ->where('products_id = ?', $products_id);
                                        $updateBuilder->execute();

                                    } else {
                                        // insert
                                        $insertBuilder        = $db
                                            ->insert( 'products_to_categories' )
                                            ->set( 'products_id', $products_id )
                                            ->set( 'categories_id', $products_category )
                                        ;
                                        $insertBuilder->execute();
                                    }

                                    $logger->products("Product linked to category: $products_id ");
                                } */
                            //}
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

                        // index|products_id|products_name|products_unit|products_departament|products_shoppingcart_price|products_price|products_quantity|manufacturer
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
                        $products_shoppingcart_price =   $new_product[5];
                        $products_price =   $new_product[6];
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
                                            'products_shoppingcart_price' => $products_shoppingcart_price,
                                            'products_price' => $products_price,
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
                                    ->set( 'products_shoppingcart_price', $products_shoppingcart_price )
                                    ->set( 'products_price', $products_price )
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
                                ->set( 'products_shoppingcart_price', $products_shoppingcart_price )
                                ->set( 'products_price', $products_price )
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

            $logger->products("Products operated: " . $number_of_operatedproducts);
            $logger->products("Products updated: " . $products_updated);
            $logger->products("Last operated product_id: " . $products_id);
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
