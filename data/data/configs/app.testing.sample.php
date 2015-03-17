<?php
/**
 * Application config
 *
 * @author   Anton Shevchuk
 * @created  08.07.11 12:14
 *
 * 'testing' в названии файла должно совпадать со значением переменной $environment,
 *  которая выступает параметром Config::load($environmen)
 */
return array(
    "cache" => array(
        "enabled" => false
    ),
    "db" => array(
        "connect" => array(
            "type" => "mysql",
            "host" => "localhost",
            "name" => "bluz",
            "user" => "root",
            "pass" => "",
        ),
    ),
    "session" => array(
        "store" => "array"
    )
);
