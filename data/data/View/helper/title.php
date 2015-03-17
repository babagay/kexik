<?php
/**
 * @namespace
 */
namespace Core\View\Helper; 

 
return


    function ($title = null, $position = 'replace', $separator = ' :: '){
        /*
         * FIXME Почему аргумент попадает в замыкание в виде массива
         */


     $layout = app()->getLayout();

        $tmp_title = null;
        $tmp_pos = null;
        $tmp_sepr = null;



        if(!is_null($title)){
            if(is_array($title)){
                if(isset($title[0])){
                    $tmp_title = $title[0];
                    if(isset($title[1])){
                        $tmp_pos = $title[1];
                        if(isset($title[2])){
                            $tmp_sepr = $title[2];
                        }
                    }
                }
            }
        }

        if(!is_null($tmp_title)) $title = $tmp_title;
        if(!is_null($tmp_pos)) $position = $tmp_pos;
        if(!is_null($tmp_sepr)) $separator = $tmp_sepr;

        if(isset($title) AND is_array($title) AND count($title) == 0)
            $title = null;



      if (app()->hasLayout()) {
         if ($title === null) {
            return 'title';
         } else {
            switch($position){

                case 'replace':

                    if ( is_string( $layout->system('title')) )
                        $result = $title . $separator . $layout->system('title');
                    else
                        $result = $title; // . (!$layout->system('title') ? '' : $separator . $layout->system('title'));

                    break;
                case 'append':
                    $result = (!$layout->system('title') ? : $layout->system('title') . $separator) . $title;
                    break;
                case 'prepend':
                default:
                    $result = $title;
                    break;
            }


            $layout->system('title', $result)  ;



            // FIXME добавил здесь return (можно эхо). Также можно брать из реестра
            // echo \Core\Helper\Registry::getInstance()->view->system('title');
            return app()->getInstance()->getLayout()->system('title');
         }
      }
      
      return '';
      
      
    };