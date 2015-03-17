<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 13.02.14
 * Time: 13:22
 */
return array(
    "cache" => array(
        "enabled" => false
    ),
    "auth" => array(
        "equals" => array(
            "encryptFunction" => function ($password, $salt) {
                    return md5(md5($password) . $salt);
                }
        )
    ),
    "debug" => defined('DEBUG')?DEBUG:false,
    "db" => array(
        "connect" => array(
            "type" => "mysql",
            "host" => "localhost",
            "name" => "babagayr_remontik",
            "user" => "babagayr_remont",
            "pass" => ")(I?J7OoBz=_",
        ),
    ),
    "request" => array(
        "baseUrl" => PUBLIC_URL,
    ),
    "session" => array(
        "store" => "session",
        "settings" => array(
            "savepath" => PATH_DATA .'/sessions'
        )
    ),
    "logger" => array(
        "enabled" => false,
    ),
    "translator" => array(
        "domain" => "messages",
        "locale" => "en_US",
        "path" => PATH_DATA .'/locale'
    ),
    "layout" => array(
        "path" => PATH_CORE .'/View/layouts',
        "template" => 'index.phtml',
        "helpersPath" => PATH_CORE .'/View/layouts/helpers'
    ),
    "virtual_modules" => array(
        "виртуальный" => array('my', 'my_virtual_controller'),
        "вход" => array('users', 'signin'),
    ),
);