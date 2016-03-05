<?php
/**
 * Get custom reflection data
 *
 * @category Application
 *
 * @author   dark
 * @created  17.05.13 17:05
 */
namespace Application;

return
/**
 * @key Example of custom key-value
 * @key [Array, also, supported]
 * @param int $id
 * @return \closure
 */
function ($id = 0, $other = "default value") use ($view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */

    // $href = $view->ahref('Test', array('test', 'index'));

    $_this = app()->getInstance();



    $_this->getLayout()->breadCrumbs(
        array(
            $view->ahref('Test', array('test', 'index')),
            'Reflection of this controller',
            )
    );


    $view->functionData = file_get_contents(__FILE__);

    ob_start();
    $b = $_this->reflection(__FILE__);
    var_dump($b);
    $a = ob_get_clean();

    fb($b);

    $view->reflectionData = $a;

    $_this->useLayout('small.phtml');
};
