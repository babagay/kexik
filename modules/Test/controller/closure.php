<?php
/**
 * Disable view, like for backbone.js
 *
 * @author   Anton Shevchuk
 * @created  22.08.12 17:14
 */
namespace Application;

use Bluz;

return
/**
 * @return \closure
 */
function () use ($view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */

    //$this->getLayout()->breadCrumbs(

    app()->getInstance()->
    getLayout()->breadCrumbs(
        array(
            $view->ahref('Test', array('test', 'index')),
            'Closure',
        )
    );
    return function () {
        echo "<div class='jumbotron'><div class='container'><p>Работает!</p>";
        echo "<h3>Closure is back</h3>";
        echo "<p class='text-warning text-primary'>Executed before render layout</p>";
        echo "</div></div>";
    };
};
