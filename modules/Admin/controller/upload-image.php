<?php
return

    function ($convert = null) use ($view) {

        $ahref       = null;
        $ahref_small = null;

        $name = $type = $tmp_name = $error = $size = null;


        $Yandex = app()->getYandex();
        $token  = $Yandex->getToken();

        $field_name = "title-image";
        //$album = 375464; //451358;
        $products_id = app()->getRequest()->getParam("products_id");

        $file = app()->getRequest()->files;

        if (isset($file[$field_name])) {
            $name     = $file[$field_name]['name']; // 3.jpg
            $type     = $file[$field_name]['type']; // image/jpeg
            $tmp_name = $file[$field_name]['tmp_name']; // /tmp/phpLuQAI7
            $error    = $file[$field_name]['error']; // 0
            $size     = $file[$field_name]['size'];
        }

        $size = getimagesize($tmp_name, $info);

        if(is_array($size)){
            $width = (int)$size[0];
            $height = (int)$size[1];
            $mime = $size['mime'];
        }

        $ext =  substr(strrchr($name, '.'), 1); // расширение исходной картинки
        $imagename = str_replace(".$ext","",$name); // имя картинки без расширения

        if($width > $height) $dimension = $height;
        else $dimension = $width;

        $new_filename = md5($imagename).".".$ext;

        // Переместить загруженную картинку в папку загрузок
        // TODO изменить временную папку с корня апача на public/uploads
        if ($error == UPLOAD_ERR_OK) {
            move_uploaded_file($tmp_name, "public/uploads/$new_filename");
        }

        //return function(){};
        $picture = PATH_PUBLIC . "/uploads/$new_filename";

        // изменение размеров картинки в квадрат
        if ( (string)app()->getRequest()->getParam("convert") === 'true' ) {
            $square_picture = PATH_PUBLIC . "/uploads/s_".$new_filename;

            if($dimension < 150) $dimension = 300;
            $app = app();
            $app::imgCropAndResize($picture, $square_picture, $dimension, $dimension, true);

            unlink($picture);
            $picture = $square_picture;
        }

        if (file_exists($picture)) {

            //$image_id = $Yandex->setAlbum($album)->storePhoto($picture);
            $image_id = $Yandex->storePhoto($picture);

            // Получить ссылку на пиктограммку
            @$ahref = $Yandex->getPhotoInfo($image_id)->img->XL->href;
            @$ahref_small = $Yandex->getPhotoInfo($image_id)->img->XS->href;

            if (is_null($ahref)) $ahref = $ahref_small;

            unlink($picture);
            //@unlink($square_picture);
        }

        if (!is_null($ahref)) {
            $array = ['image_small' => $ahref_small, 'image_large' => $ahref];
            Application\Products\Table::update($array, array('products_id' => $products_id));
        }

        //Debug
        //fb( array('ahref' => $ahref, 'ahref_small' => $ahref_small));
        //return json_encode( array('ahref' => $ahref, 'ahref_small' => $ahref_small) );

        return function () use ($ahref, $ahref_small) {
            header("Content-Type: application/json");
            http_response_code(200);

            echo json_encode(array('ahref' => $ahref, 'ahref_small' => $ahref_small));

            //return array('ahref' => $ahref, 'ahref_small' => $ahref_small);
        };

    };