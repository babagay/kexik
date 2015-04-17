<?php
 
namespace Core\Helper;

return 
    function ($word = "", $number = "") {

        $tmp = false;

        // Bug с параметрами
        if(is_array($word)){
            if(isset($word[0])){
                $tmp = $word[0];
            }
            if(isset($word[1])){
                $number = $word[1];
            }
           /* if(isset($word[2])){
                $param_2 = $word[2];
            }*/
        }

        if($tmp !== false){
            $word = $tmp;
        }

        #
        $end = "";

        #
        $number = trim((string)$number);
        $length = strlen( $number );


        if($length == 1){
            $end_number = substr($number,-1);

            if($end_number == "1")
                $end = "";
            elseif($end_number == "2" OR $end_number == "3" OR $end_number == "4")
                $end = "а";
            else
                //($end_number == "5" OR $end_number == "6" OR $end_number == "7" OR $end_number == "8" OR $end_number == "9" OR $end_number == "0")
                $end = "ов";
        } else {
            $end_number = substr($number,-1);
            $end_2_number = substr($number,-2);

            if($end_2_number == "11" OR $end_2_number == "12" OR $end_2_number == "13"
                OR $end_2_number == "14" OR $end_2_number == "15" OR $end_2_number == "16"
                OR $end_2_number == "17" OR $end_2_number == "18" OR $end_2_number == "19" OR $end_number  == "0")
                $end = "ов";
            elseif($end_number == "1")
                $end = "";
            elseif($end_number == "2" OR $end_number == "3" OR $end_number == "4")
                $end = "а";
        }

        return $word . $end;
    };


