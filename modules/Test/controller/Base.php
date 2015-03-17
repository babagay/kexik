<?php
/**
 * Test of test of test
 *
 * @author   Anton Shevchuk
 * @created  21.08.12 12:39
 */
namespace Application;

use Bluz;

$_this = $this;

return
/**
 * @return \closure
 */
function () use ($bootstrap, $view, $_this) {
    /**
     * @var \Application\Bootstrap $_this
     * @var \closure $bootstrap
     * @var Bluz\View\View $view
     */
    $view->title('Test Module');
    $view->title('Append', $view::POS_APPEND);
    $view->title('Prepend', $view::POS_PREPEND);
    $_this->getLayout()->breadCrumbs(array('Test'));

    // change layout
    $_this->useLayout('front_end.phtml');
};
