<?php
/*
Контроллер модуля.

Унаследован от шаблона контроллеров модулей
Вызывает свою модель
Вызывает свое представление

Генерит хтмл или ошибку
*/

class FaceModul extends BasicModulController implements BasicModulControllerInterface {

    /*
    function __construct($registry = null){
    // [!] Вызов парент -конструктора обязателен
        parent::__construct();

    }
    */


    // -------- Actions ----------

    function actionFaceBasic(){

        // Можно поставить редирект
        // header("Location:/about");

        // [пример]
        // $this->view->submitButtonName = '78';


        if( isset($_SERVER['HTTP_REFERER']) ){
        // do nothing - зашли с сайта
            //fb($_SERVER['HTTP_REFERER']);
        } else {

        }

        $this->html = $this->view->render();
    } // --

    function actionFaceAsd(){
        $this->html = 'actionFaceAsd-';
    } // --



















}