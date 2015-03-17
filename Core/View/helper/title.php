<?php
/**
 * @namespace
 */
namespace Core\View\Helper; 

 
return


    function ($title = null, $position = 'append', $separator = ' :: '){


     $layout = app()->getLayout();

        $tmp_title = null;
        $tmp_pos = null;
        $tmp_sepr = null;
        $standard_title = '';
        $title_str = $standard_title;

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
                } else {
                    $title = null;
                }

            }
        }

        if(!is_null($tmp_title)) $title = $tmp_title;
        if(!is_null($tmp_pos)) $position = $tmp_pos;
        if(!is_null($tmp_sepr)) $separator = $tmp_sepr;

        if(isset($title) AND is_array($title) AND count($title) == 0)
            $title = null;

        //if( trim($title) === '' ) $title = null;

        //fb(app()->getView()->system('title'));


        if(is_null($title)){
            //if( trim( (string)$title) == '' ){
            // Просто вернуть титлу

            // $title_arr = \Core\Helper\Registry::getInstance()->view->system('title');
            $title_arr = app()->getView()->system('title'); // аналогично

            if( is_array($title_arr) ){
                // TODO сгенерить строку

                $title_str = '';
                foreach( $title_arr as $val ) {
                    if( trim($val) != '' )
                        $title_str .= $val . " $separator ";
                }

                // TODO брать длину $separator и подставлять ее вместо -6
                $title_str =  substr_replace($title_str,'',-6,-1);

                return $title_str;

            } elseif ( is_string(app()->getView()->system('title')) ) {
                return $title_arr;

            } else {
                return $standard_title;
            }
        } else {

            // TODO В режиме добавленгия вкинуть айтем в массив view->system('title')
            // В режиме перезаписи вкинуть титлу в нудевую позицию массива view->system('title') после его обнуления

            if ( app()->hasLayout()) {
                if ($title === null) {
                    return $standard_title;
                } else {
                    switch($position){

                        case 'replace':

                            if ( is_string( $layout->system('title')) ) {
                                //$result = $title . $separator . $layout->system( 'title' );
                                $result = $title;

                            } elseif( is_array(  $layout->system('title') ) ) {
                                $result = $standard_title;

                                $title_arr =  $layout->system('title');
                                if( isset($title_arr[0]) ){
                                    if( is_string($title_arr[0]) AND trim($title_arr[0]) != '' )
                                        $result = $title_arr[0];
                                    else {
                                        $result = $title;
                                    }
                                }

                            } else {
                                $result = array($title); // . (!$layout->system('title') ? '' : $separator . $layout->system('title'));
                            }
                            break;
                        case 'append':
                            $system_title =  $layout->system('title');

                            //$result = (!$layout->system('title') ? : $layout->system('title') . $separator) . $title;


                            if(is_array($system_title)){
                                if(isset($system_title[0])){
                                    if(trim($system_title[0]) == ''){
                                        $result = array($title);
                                    } else {

                                        $system_title[] = $title;
                                        $result = $system_title;
                                    }
                                }
                            } else {
                                $result = array($title);
                            }

                            break;
                        case 'prepend':
                            // TODO
                        default:
                            $result = $title;
                            break;
                    }

                    //fb($position . " " . $title);
                    //fb($layout->system('title'));
                    //fb($result);


                    $layout->system('title', $result)  ;



                    // [!] добавил здесь return (можно эхо). Также можно брать из реестра
                    //return \Core\Helper\Registry::getInstance()->view->system('title');
                    return app()->getInstance()->getLayout()->system('title');
                }
            }
        }



      
      return '';
      
      
    };