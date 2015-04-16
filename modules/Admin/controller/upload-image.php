<?php
return

    function ($param = null) use ($view) {

        $name = $type = $tmp_name = $error = $size = null;

        $Yandex = app()->getYandex();
        $token = $Yandex->getToken();



        $file = app()->getRequest()->files ;

        if(isset($file['illustration'])){
            $name = $file['illustration']['name'] ; // 3.jpg
            $type = $file['illustration']['type'] ; // image/jpeg
            $tmp_name = $file['illustration']['tmp_name'] ; // /tmp/phpLuQAI7
            $error = $file['illustration']['error'] ; // 0
            $size = $file['illustration']['size'] ;
        }

        if($error == UPLOAD_ERR_OK){
            move_uploaded_file($tmp_name, "public/images/$name");
        }

        $picture =  getcwd() . "/public/images/$name" ;
        $album = 451358;
        $ahref = '';
        $ahref_small = '';

        if( file_exists($picture) ){
            $image_id = $Yandex->setAlbum($album)->storePhoto($picture);

            //sleep(1);

            // Получить ссылку на пиктограммку
            $ahref = $Yandex->getPhotoInfo($image_id)->img->XS->href ;
            $ahref_small = $Yandex->getPhotoInfo($image_id)->img->M->href ;

            unlink($picture);
        }

        fb( array('ahref' => $ahref, 'ahref_small' => $ahref_small));
        return json_encode( array('ahref' => $ahref, 'ahref_small' => $ahref_small) );

        return function () use ($ahref,$ahref_small) {
            header("Content-Type: application/json");
            http_response_code(200);
            //echo json_encode( array('asd' => 'zxc') );
            return json_encode( array('ahref' => $ahref, 'ahref_small' => $ahref_small) );
        };

    };