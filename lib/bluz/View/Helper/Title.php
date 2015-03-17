<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/*
 * FIXME: сейчас $result не отображается, т.к. мы создаем новый экземпляр через View::getInstance() либо $layout = app()->getLayout();
 *
 * [!] Внимание! Сейчас первый параметр функции может прийти как массив, а не строка.
 * Вероятно ,вызов изменился - теперь , все параметры упаковываются в массив и прилетают в первый параметр.
 * Возможно, это связано с использованием  call_user_func_array ()
 */

/**
 * @namespace
 */
namespace Bluz\View\Helper;

use Bluz\View\View;

return
    /**
     * @param string $title
     * @param string $position
     * @param string $separator
     * return string|View
     */
    function ($title = null, $position = View::POS_REPLACE, $separator = ' :: ') {
    /** @var View $this */

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


    if (app()->hasLayout()) {
        // it's stack for <head>
        // var_dump($this); // Undefined variable: this

        //\Core\Helper\Registry::getInstance()->view -> system('asd');

        $layout = app()->getLayout();
        if ($title === null) {
            return $layout->system('title');
        } else {
            // switch statement for $position
            switch ($position) {
                case View::POS_PREPEND:
                    $result = $title . (!$layout->system('title') ? : $separator . $layout->system('title'));
                    break;
                case View::POS_APPEND:
                    $result = (!$layout->system('title') ? : $layout->system('title') . $separator) . $title;
                    break;
                case View::POS_REPLACE:
                default:
                    $result = $title;
                    break;
            }
            //return $title;

            // Сохранить в стэк
            $layout->system('title', $result)  ;

            // [?] Почему не было оператора return. Где происходит просмотр стека и вытаскивание title?



            //View::getInstance()->system('title', $result);
        }
    }
    return '';
    };
