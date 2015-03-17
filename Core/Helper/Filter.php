<?php
    namespace Core\Helper;

    return
        /**
         * При передаче параметров в замыкание они все сваливаются в элементы массива $param
         */
        function ($str, $mode = null) {


            $params = array();

            if( is_array( $str ) ) {
                if( isset( $str[0] ) ) {
                    $params   = $str;
                    $str = $params[0];
                    $mode = $params[1];
                }
            }

            switch($mode){
                case 1:
                    // $article["body"] = preg_replace($search, $replace, $article["body"]);
                    //$article["body"] = str_replace("&amp;","&",$article["body"] );
                    //$article["body"] = preg_replace('/(epilog)*/i',"'",$article["body"] );

                    $str = str_replace("&#039;","\"",$str );
                    $str = str_replace("&lt;","<",$str );
                    $str = str_replace("&gt;",">",$str );
                    $str = str_replace("&acirc;","—",$str );


                    break;
            }

            return $str;

        };