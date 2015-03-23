<?php

namespace Application;

use Bluz;

$_this = $this;

return


    function () use ($view, $_this) {

        $user = app()->getAuth()->getIdentity();

        $credit = $user->getCredit();

        return function () use($credit) {

            $response = 'ok';

            $result = array('response' => $response, 'credit' => $credit);

            return $result;
        };
    };