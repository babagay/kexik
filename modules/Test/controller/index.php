<?php
/**
 * Test of test of test
 *
 * @author   Anton Shevchuk
 * @created  21.08.12 12:39
 */
namespace Application;

use Bluz;

return
/**
 * @return \closure
 */
function () use ($bootstrap, $view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \closure $bootstrap
     * @var Bluz\View\View $view
     */

    /* Добавили код */
     $a = new \Core\Helper\Asd();

    foreach($a() as $item){
        fb($item);
    }



    $view->title('Test Module');
    $view->title('Append', $view::POS_APPEND);
    $view->title('Prepend', $view::POS_PREPEND);
    $this->getLayout()->breadCrumbs(array('Test'));
};
