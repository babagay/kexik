<?php

namespace Core\Helper;

/**
 * Class getDate
 * @package Core\Helper
 */
class getDate
{

    private $config = [];

    function __construct()
    {
    }

    function setData($data)
    {
        $this->config = $data;
    }

    /**
     * Текущая дата
     *
     * @param string $format
     * @return string
     *
     * "F j, Y, g:i a"
     */
    function now($format = 'Y\-m\-d\ H:i:s')
    {
        $tz_object = new \DateTimeZone('Europe/Kiev'); // TODO брать из конфиг-файла

        $datetime = new \DateTime();
        $datetime->setTimezone($tz_object);

        return $datetime->format($format);
    }

    /**
     * Текущая дата на русском
     * @return string
     */
    function today()
    {
        return $this->transformDate($this->now());
    }

    /**
     * From 2015-06-05 14:26:29 to 5 июня 2015, 14:26
     * @param $date_full
     * @return string
     */
    function transformDate($date_full)
    {
        $arr = explode(" ", $date_full);

        $date = explode("-", $arr[0]);
        $time = explode(":", $arr[1]);

        switch ((int)$date[1]) {
            case 1:
                $date[1] = "января";
                break;
            case 2:
                $date[1] = "февраля";
                break;
            case 3:
                $date[1] = "марта";
                break;

            case 4:
                $date[1] = "апреля";
                break;
            case 5:
                $date[1] = "мая";
                break;
            case 6:
                $date[1] = "июня";
                break;

            case 7:
                $date[1] = "июля";
                break;
            case 8:
                $date[1] = "августа";
                break;
            case 9:
                $date[1] = "сентября";
                break;

            case 10:
                $date[1] = "октября";
                break;
            case 11:
                $date[1] = "ноября";
                break;
            case 12:
                $date[1] = "декабря";
                break;
        }

        switch ((int)$date[2]) {
            case 1:
                $date[2] = 1;
                break;
            case 2:
                $date[2] = 2;
                break;
            case 3:
                $date[2] = 3;
                break;
            case 4:
                $date[2] = 4;
                break;
            case 5:
                $date[2] = 5;
                break;
            case 6:
                $date[2] = 6;
                break;
            case 7:
                $date[2] = 7;
                break;
            case 8:
                $date[2] = 8;
                break;
            case 9:
                $date[2] = 9;
                break;
        }

        return $date[2] . " " . $date[1] . " " . $date[0] . ", " . $time[0] . ":" . $time[1];
    }

    /**
     * Подготавливает дату перед внеением в базу, преобразуя ее из формата дэйтпикера 03/06/2015 18:57 в 2015-06-03 17:26:13
     * @param $date_full
     * @return string
     */
    function prepare($date_full)
    {
        $arr = explode(" ", $date_full);

        $date = explode("/", $arr[0]);
        $time = explode(":", $arr[1]);

        if (!isset($time[2]))
            $time[2] = "00";

        return $date[2] . "-" . $date[1] . "-" . $date[0] . " " . $time[0] . ":" . $time[1] . ":" . $time[2];
    }

    /**
     * Is the date already in the Past
     * @param $date in format 2016-03-12 21:10:32
     * @return bool (returns true if the date is in the Past)
     */
    public function isInThePast ( $date )
    {
        $isPast = false;

        $date = $this->prepare($date);
        $now = $this->now();

        $d =  date_create($date);
        $n =  date_create($now);
        $d_stamp = date_timestamp_get($d);
        $n_stamp = date_timestamp_get($n);

        if( ($d_stamp - $n_stamp) < 0 )
            $isPast = true;

        return $isPast;
    }

    /**
     * TODO присоединить функцию ниже
     */
    /* function ($dateline) {

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
    */

}