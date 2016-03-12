<?php

$vendorDir = dirname(dirname(__FILE__));
$baseDir   = dirname($vendorDir);

/**
 * [!] $baseDir указывает на корень сайта: C:\wamp\www\zoqa
 */

return array(
    'Bluz\\Tests\\'                    => array($baseDir . '/lib/bluz/tests/src'),
    'Bluz\\'                           => array($baseDir . '/lib/bluz'),
    'Zoqa\\Testspace\\'                => array($baseDir . '/Core/Helper'), // работает
    'Zoqa\\Basket\\'                   => array($baseDir . '/Core/Model/Basket'), // работает
    'Application\\Test\\'              => array($baseDir . '/Core/Model/Test'), // работает
    'Awakenweb\\Livedocx\\'            => array($baseDir . '/lib/Awakenweb/Livedocx'), // работает
    'Application\\Filters\\'           => array($baseDir . '/Core/Model/Filters'), // работает
    'Application\\Manufacturers\\'     => array($baseDir . '/Core/Model/Manufacturers'), // работает
    'Application\\PaymentTypes\\'      => array($baseDir . '/Core/Model/PaymentTypes'), // работает
    'Application\\OrderProducts\\'     => array($baseDir . '/Core/Model/OrderProducts'), // работает
    'Application\\FiltersToProducts\\' => array($baseDir . '/Core/Model/FiltersToProducts'), // работает

);
