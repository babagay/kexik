<?php
/**
 * Example of DB usage
 *
 * @author   Anton Shevchuk
 * @created  07.09.12 18:28
 */
namespace Application;

$_this = $this;

return
/**
 * @return \closure
 */
function () use ($view, $_this) {
    /**
     * @var \Application\Bootstrap $this
     * @var \closure $bootstrap
     * @var \Bluz\View\View $view
     */
    $_this->getLayout()->breadCrumbs(
        array(
            $view->ahref('Test', array('test', 'index')),
            'Basic DB operations',
        )
    );
    // all examples inside view

    $res_1 = app()->getDb()->fetchOne("SELECT count(*) FROM test LIMIT 1");
    $view->res_1 = $res_1; //fb($res_1);

    $res_2 = app()->getDb()->fetchRow("SELECT * FROM test WHERE name LIKE ?", array("al%"));
    $view->res_2 = $res_2; //fb($res_2);

    $res_3 = app()->getDb()->fetchAll("SELECT * FROM test WHERE name LIKE ? LIMIT 10", array("al%"));
    $view->res_3 = $res_3; //fb($res_3);

    $res = app()->getDb()->fetchColumn("SELECT name FROM test WHERE name LIKE ? LIMIT 10", array("al%"));
    $view->res_4 = $res; //fb($res);

    $res = app()->getDb()->fetchPairs("SELECT name, id FROM test LIMIT 10");
    $view->res_5 = $res; //fb($res);
};
