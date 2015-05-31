<?php
/**
 * Created by PhpStorm.
 * User: apanov
 * Date: 13.02.14
 * Time: 13:22
 *
 * Чтобы увидеть, какой контроллер какого модуля вызывается, нужно включить вывод в консоль в Bootstrap::render()
 */
return array(
    "cache" => array(
        "enabled" => false,
        "settings" => array(
            "cacheAdapter" => array(
                "name" => "memcache",
                "extension_name" => "memcache",
                "service_name" => "Memcache",
                "settings" => array(
                    "servers" => array(
                        array("localhost", 11211, 1),
                    )
                )
            )
        )
    ),
    "storage" => array(
        "adaptertype" => "file", // Вместо этого можно установить name:Name, setting:[]
        "cacheDir"    => "storage",
    ),
    "auth" => array(
        "equals" => array(
            "encryptFunction" => function ($password, $salt) {
                    return md5(md5($password) . $salt);
                }
        ),
        "vk" => array(
            "appId"         => 4638314,
            "secret"        => '3NmKv9lHGYGN9XFD6lyS',
            "api_version"   => '5.26',
            "redirect_uri"  => 'http://babagay.ru/vk/auth',
        ),
        // [!] Имя должно совпадать со второй частью ключа массива, содержащего идентификационные данные
        // Напр, "babagay" для "yandex_babagay"
        "default_yandex_account" => "babagay", //"keksik",
        "yandex_babagay" => array(
            "base_url"      => "http://yandex.ru//",
            "api_photo_url" => "http://api-fotki.yandex.ru/",  // /verification_code
            "oauth_url"     => "https://oauth.yandex.ru/",
            "username"      => "babagai",
            "password"      => "djbyk.,db2012",
            "client_id"     => "f8d97173c0394aedaba85f45a4c5a4eb",
            "client_secret" => "6dd3122b52974d939b8f5ac75eaa773e",
            "album_default" => 375464,
        ),
        "yandex_keksik" => array(
            "base_url"      => "http://yandex.ru//",
            "api_photo_url" => "http://api-fotki.yandex.ru/",  // /verification_code
            "oauth_url"     => "https://oauth.yandex.ru/",
            "username"      => "keksik.online",
            "password"      => 'keksik2015',
            "client_id"     => "d0e9ced25bcd40c0bbf02a1f2d735220",
            "client_secret" => "8b4320d1e2744c5eaf979517f9e417f8",
            "album_default" => 472910,
        ),
        "livedocx" => array(
            "base_url"      => "https://api.livedocx.com/1.2/mailmerge.asmx?WSDL",
            "username"      => "babagay",
            "password"      => "zxcVBN123_lkjh",
        ),
    ),
    "debug" => defined('DEBUG')?DEBUG:false,
    "db" => array(
        "connect" => array(
            "type" => "mysql",
            "host" => "localhost",
            "name" => "keks",
            "user" => "root",
            "pass" => "",
            "options" => array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''),
        ),
    ),
    "request" => array(
        "baseUrl" => PUBLIC_URL,
    ),
    "mailer" => array(
        "subjectTemplate" => "Bluz - %s",
        // email is required
        "from" => array(
            "email" => "no-reply@domain.com",
            "name" => "Bluz"
        ),
        // PHPMailer settings
        // read more at https://github.com/Synchro/PHPMailer
        "settings" => array(
            "CharSet" => "UTF-8",
            "Mailer" => "smtp", // mail, sendmail, smtp, qmail
            "SMTPSecure" => "ssl",
            "Host" => "10.10.0.114",
            "Port" => "2525",
            "SMTPAuth" => true,
            "Username" => "user@domain.com",
            "Password" => "pass",
        ),
        // Custom Headers
        "headers" => array(
            "PROJECT" => "Bluz",
        ),
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
        "выход" => array('users', 'signout'),
        "регистрация" => array('users', 'signup'),
        "блог" => array('blog', 'Base'),
        "автор" => array('autor', 'Base'),
        "каталог" => array('catalog', 'Base'),
        "поиск" => array('catalog', 'search'),
        "авторизация" => array('facebook', 'auth'),
        "кабинет" => array('my', 'Base'),
        "корзина" => array('basket', 'Base'),
        "404.php" => array('error', '404'),
    ),
);