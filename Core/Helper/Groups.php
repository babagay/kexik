<?php
/*
 * FIXME: 2 раза уже файл затирался на сервере
 */


    namespace Bluz\Application\Helper;

//use Bluz\Application\Exception\RedirectException;

    return

        function ($param) {

            if(isset($param[0])) $selector = $param[0]; else $selector = null;


            $groups = app()->getDb()->fetchAll(" SELECT * FROM `zoqa_article_groups` ");

            $tmp = false;
            if(is_array($groups)){
                foreach($groups as $k => $group){
                    $tmp[] = array($group['groups_id'] => $group['name']);
                }
            }

            if(is_array($tmp))
                $groups = $tmp;

            return $groups;

        };
