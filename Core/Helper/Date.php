<?php
    /*
 $article['dateline'] = date( "Y-m-d", $article["dateline"] );

        */
namespace Core\Helper;

/*
[!] Что характерно, при первом вызове хелпера, параметры загоняются в массив $param.
Далее, когда видимо он достается из кэша, в переменную $param попадает int.
*/

return
    /**
     * При передаче параметров в замыкание они все сваливаются в элементы массива $param
     */
    function ($dateline) {

        $monthes = array(
            1 => array('январь','января'),
            2 => array('февраль','февраля'),
            3 => array('март','марта'),
            4 => array('апрель','апреля'),
            5 => array('май','мая'),
            6 => array('июнь','июня'),
            7 => array('июль','июля'),
            8 => array('август','августа'),
            9 => array('сентябрь','сентября'),
            10 => array('октябрь','октября'),
            11 => array('ноябрь','ноября'),
            12 => array('декабрь','декабря'),

        );

        $params = array();

        if(is_array($dateline)){
            if(isset($dateline[0])) {
                $params   = $dateline;
                $dateline = $params[0];
            }
        }

        if( isset($dateline) ){
            if( is_string($dateline) ){
                $pos = strpos($dateline,'-');fb($pos);
                if($pos !== false){
                    $date = explode('-',$dateline);
                    //TODO: преобразовать дату "2014-10-12" в 12 дек 2014
                    fb($date);
                }
            } elseif( is_int($dateline) ){
                //$date = date( "Y-m-d H:i:s",$dateline);
                $date = date( "Y-m-d",$dateline);
                $date = explode('-',$date);

                $year = $date[0];
                $month = $monthes[(int)$date[1]][1];
                $day = $date[2];

                $date = $date[2] . " " . $month . " " . $year;

                return $date;
            }
        }

    };


