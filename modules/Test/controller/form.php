<?php
/**
 * Example of forms handle
 *
 * @category Application
 *
 * @author   dark
 * @created  13.12.13 18:12
 */
namespace Application;

return
/**
 * @return \closure
 */
function ($int, $string, $array, $optional = 0) use ($view) {
    /**
     * @var \Application\Bootstrap $this
     * @var \Bluz\View\View $view
     */
    $this->getLayout()->breadCrumbs(
        array(
            $view->ahref('Test', array('test', 'index')),
            'Form Example',
        )
    );
    if ($this->getRequest()->isPost()) {
        ob_start();
        var_dump($int, $string, $array, $optional);
        $view->inside = ob_get_contents();
        ob_end_clean();
        $view->params = $this->getRequest()->getAllParams();
    }
};
